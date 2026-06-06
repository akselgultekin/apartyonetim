<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Location;
use App\Models\MaintenanceLog;
use App\Models\Room;
use App\Models\Stay;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $today = today();
        $rooms = Room::with('location')->active()->get();
        $occupiedRoomIds = Stay::activeOn($today)->pluck('room_id')->unique();
        $periods = $this->periodTotals();

        return view('dashboard', [
            'rooms' => $rooms,
            'occupiedRooms' => $occupiedRoomIds->count(),
            'emptyRooms' => max($rooms->count() - $occupiedRoomIds->count(), 0),
            'todayCheckins' => Stay::whereDate('check_in', $today)->with(['customer', 'room'])->get(),
            'todayCheckouts' => Stay::whereDate('check_out', $today)->with(['customer', 'room'])->get(),
            'unpaidRent' => Income::whereIn('payment_status', ['unpaid', 'partial'])->sum(DB::raw('amount - paid_amount')),
            'periods' => $periods,
            'locationOccupancy' => $this->locationOccupancy(),
            'topRooms' => Room::with('location')
                ->withSum('incomes as revenue', 'paid_amount')
                ->orderByDesc('revenue')
                ->limit(5)
                ->get(),
            'upcomingPayments' => Subscription::with(['location', 'room'])->where('payment_status', 'unpaid')->whereDate('due_date', '>=', $today)->orderBy('due_date')->limit(8)->get(),
            'upcomingCheckouts' => Stay::with(['customer', 'room'])->whereDate('check_out', '>=', $today)->orderBy('check_out')->limit(8)->get(),
            'activities' => ActivityLog::latest()->limit(10)->get(),
        ]);
    }

    public function locations(Request $request): View
    {
        return view('locations.index', [
            'locations' => Location::withCount('rooms')->filter($request)->latest()->paginate(12),
            'editing' => $request->filled('edit') ? Location::find($request->integer('edit')) : null,
        ]);
    }

    public function storeLocation(Request $request): RedirectResponse
    {
        $location = Location::create($this->validateLocation($request));
        $this->log('Lokasyon eklendi', $location->name);

        return back()->with('status', 'Lokasyon kaydedildi.');
    }

    public function updateLocation(Request $request, Location $location): RedirectResponse
    {
        $location->update($this->validateLocation($request));
        $this->log('Lokasyon guncellendi', $location->name);

        return redirect()->route('locations.index')->with('status', 'Lokasyon guncellendi.');
    }

    public function rooms(Request $request): View
    {
        return view('rooms.index', [
            'rooms' => Room::with('location')->filter($request)->latest()->paginate(12),
            'locations' => Location::active()->orderBy('name')->get(),
            'editing' => $request->filled('edit') ? Room::find($request->integer('edit')) : null,
        ]);
    }

    public function storeRoom(Request $request): RedirectResponse
    {
        $room = Room::create($this->validateRoom($request));
        $this->log('Oda eklendi', $room->name);

        return back()->with('status', 'Oda/daire kaydedildi.');
    }

    public function updateRoom(Request $request, Room $room): RedirectResponse
    {
        $room->update($this->validateRoom($request));
        $this->log('Oda guncellendi', $room->name);

        return redirect()->route('rooms.index')->with('status', 'Oda/daire guncellendi.');
    }

    public function customers(Request $request): View
    {
        return view('customers.index', [
            'customers' => Customer::filter($request)->latest()->paginate(12),
            'editing' => $request->filled('edit') ? Customer::find($request->integer('edit')) : null,
        ]);
    }

    public function storeCustomer(Request $request): RedirectResponse
    {
        $customer = Customer::create($this->validateCustomer($request));
        $this->log('Musteri eklendi', $customer->full_name);

        return back()->with('status', 'Musteri kaydedildi.');
    }

    public function updateCustomer(Request $request, Customer $customer): RedirectResponse
    {
        $customer->update($this->validateCustomer($request));
        $this->log('Musteri guncellendi', $customer->full_name);

        return redirect()->route('customers.index')->with('status', 'Musteri guncellendi.');
    }

    public function stays(Request $request): View
    {
        return view('stays.index', [
            'stays' => Stay::with(['customer', 'room.location'])->filter($request)->latest()->paginate(12),
            'customers' => Customer::active()->orderBy('full_name')->get(),
            'rooms' => Room::active()->with('location')->orderBy('name')->get(),
        ]);
    }

    public function storeStay(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'exists:customers,id'],
            'room_id' => ['required', 'exists:rooms,id'],
            'check_in' => ['required', 'date'],
            'check_out' => ['required', 'date', 'after_or_equal:check_in'],
            'rental_type' => ['required', Rule::in(['daily', 'weekly', 'monthly', 'yearly'])],
            'total_rent' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);
        $data['payment_status'] = $this->paymentStatus($data['total_rent'], $data['paid_amount']);

        $overlap = Stay::where('room_id', $data['room_id'])
            ->where(fn (Builder $query) => $query->whereDate('check_in', '<=', $data['check_out'])->whereDate('check_out', '>=', $data['check_in']))
            ->exists();

        if ($overlap) {
            return back()->withErrors(['check_in' => 'Bu oda secilen tarih araliginda zaten dolu veya rezerve.'])->withInput();
        }

        $stay = Stay::create($data);
        $stay->room->update(['status' => 'occupied']);

        Income::create([
            'title' => 'Kira geliri - '.$stay->customer->full_name,
            'type' => $data['rental_type'].'_rent',
            'amount' => $data['total_rent'],
            'paid_amount' => $data['paid_amount'],
            'date' => $data['check_in'],
            'payment_method' => 'cash',
            'payment_status' => $data['payment_status'],
            'location_id' => $stay->room->location_id,
            'room_id' => $stay->room_id,
            'customer_id' => $stay->customer_id,
            'notes' => 'Konaklama kaydindan otomatik olustu.',
        ]);

        $this->log('Konaklama eklendi', $stay->customer->full_name.' / '.$stay->room->name);

        return back()->with('status', 'Giris/cikis kaydi olusturuldu.');
    }

    public function checkoutStay(Stay $stay): RedirectResponse
    {
        $stay->update(['checked_out_at' => now()]);
        $stay->room->update(['status' => 'maintenance', 'cleaning_status' => 'waiting']);
        $this->log('Cikis tamamlandi', $stay->customer->full_name.' / '.$stay->room->name);

        return back()->with('status', 'Cikis yapildi, oda temizlik bekliyor durumuna alindi.');
    }

    public function incomes(Request $request): View
    {
        return view('finance.incomes', $this->financeViewData($request, Income::class, 'incomes'));
    }

    public function storeIncome(Request $request): RedirectResponse
    {
        $income = Income::create($this->validateIncome($request));
        $this->log('Gelir eklendi', $income->title);

        return back()->with('status', 'Gelir kaydedildi.');
    }

    public function expenses(Request $request): View
    {
        return view('finance.expenses', $this->financeViewData($request, Expense::class, 'expenses'));
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        $expense = Expense::create($this->validateExpense($request));
        $this->log('Gider eklendi', $expense->title);

        return back()->with('status', 'Gider kaydedildi.');
    }

    public function subscriptions(Request $request): View
    {
        return view('subscriptions.index', [
            'subscriptions' => Subscription::with(['location', 'room'])->filter($request)->latest()->paginate(12),
            'locations' => Location::active()->orderBy('name')->get(),
            'rooms' => Room::active()->orderBy('name')->get(),
        ]);
    }

    public function storeSubscription(Request $request): RedirectResponse
    {
        $subscription = Subscription::create($request->validate([
            'utility_type' => ['required', Rule::in(['water', 'electricity', 'gas', 'internet', 'other'])],
            'subscriber_number' => ['required', 'string', 'max:120'],
            'company' => ['nullable', 'string', 'max:160'],
            'due_date' => ['required', 'date'],
            'bill_amount' => ['required', 'numeric', 'min:0'],
            'payment_status' => ['required', Rule::in(['paid', 'unpaid'])],
            'location_id' => ['nullable', 'exists:locations,id'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'notes' => ['nullable', 'string'],
        ]));

        if ($subscription->payment_status === 'unpaid') {
            Expense::create([
                'title' => 'Abonelik faturasi - '.$subscription->subscriber_number,
                'category' => $subscription->utility_type,
                'amount' => $subscription->bill_amount,
                'date' => $subscription->due_date,
                'payment_status' => 'unpaid',
                'location_id' => $subscription->location_id,
                'room_id' => $subscription->room_id,
                'notes' => 'Abonelik kaydindan otomatik olustu.',
            ]);
        }

        $this->log('Abonelik eklendi', $subscription->subscriber_number);

        return back()->with('status', 'Abonelik kaydedildi.');
    }

    public function maintenance(Request $request): View
    {
        return view('maintenance.index', [
            'logs' => MaintenanceLog::with('room.location')->filter($request)->latest()->paginate(12),
            'rooms' => Room::with('location')->orderBy('name')->get(),
        ]);
    }

    public function storeMaintenance(Request $request): RedirectResponse
    {
        $log = MaintenanceLog::create($request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'type' => ['required', Rule::in(['cleaning', 'repair', 'inspection'])],
            'status' => ['required', Rule::in(['open', 'in_progress', 'done'])],
            'title' => ['required', 'string', 'max:180'],
            'notes' => ['nullable', 'string'],
        ]));

        $roomStatus = $log->type === 'repair' && $log->status !== 'done' ? 'maintenance' : 'available';
        $cleaning = $log->type === 'cleaning' && $log->status === 'done' ? 'clean' : $log->room->cleaning_status;
        $log->room->update(['status' => $roomStatus, 'cleaning_status' => $cleaning]);
        $this->log('Bakim/temizlik kaydi', $log->title);

        return back()->with('status', 'Bakim/temizlik kaydi olusturuldu.');
    }

    public function calendar(Request $request): View
    {
        $start = Carbon::parse($request->input('start', now()->startOfMonth()))->startOfDay();
        $end = Carbon::parse($request->input('end', now()->endOfMonth()))->endOfDay();

        return view('calendar.index', [
            'start' => $start,
            'end' => $end,
            'rooms' => Room::with(['location', 'stays' => fn ($query) => $query->whereDate('check_in', '<=', $end)->whereDate('check_out', '>=', $start)->with('customer')])->orderBy('name')->get(),
        ]);
    }

    public function reports(Request $request): View
    {
        $start = Carbon::parse($request->input('start', now()->startOfMonth()))->startOfDay();
        $end = Carbon::parse($request->input('end', now()->endOfMonth()))->endOfDay();

        return view('reports.index', [
            'start' => $start,
            'end' => $end,
            'summary' => $this->profitSummary($start, $end),
            'locationRows' => $this->profitRows($start, $end, 'location'),
            'roomRows' => $this->profitRows($start, $end, 'room'),
            'categoryRows' => Expense::whereBetween('date', [$start, $end])->select('category', DB::raw('SUM(amount) as amount'))->groupBy('category')->orderByDesc('amount')->get(),
        ]);
    }

    public function exportProfitLoss(Request $request): Response
    {
        $start = Carbon::parse($request->input('start', now()->startOfMonth()))->startOfDay();
        $end = Carbon::parse($request->input('end', now()->endOfMonth()))->endOfDay();
        $rows = $this->profitRows($start, $end, 'location');
        $csv = "Baslik,Gelir,Gider,Net\n";

        foreach ($rows as $row) {
            $csv .= sprintf("\"%s\",%.2f,%.2f,%.2f\n", str_replace('"', '""', $row['name']), $row['income'], $row['expense'], $row['net']);
        }

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="kar-zarar.csv"',
        ]);
    }

    private function validateLocation(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:160'],
            'address' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);
    }

    private function validateRoom(Request $request): array
    {
        return $request->validate([
            'location_id' => ['required', 'exists:locations,id'],
            'name' => ['required', 'string', 'max:120'],
            'type' => ['required', 'string', 'max:80'],
            'capacity' => ['required', 'integer', 'min:1'],
            'daily_price' => ['required', 'numeric', 'min:0'],
            'weekly_price' => ['required', 'numeric', 'min:0'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'yearly_price' => ['required', 'numeric', 'min:0'],
            'deposit' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['available', 'occupied', 'reserved', 'maintenance', 'passive'])],
            'cleaning_status' => ['required', Rule::in(['clean', 'dirty', 'waiting'])],
            'maintenance_status' => ['required', Rule::in(['normal', 'maintenance', 'faulty'])],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function validateCustomer(Request $request): array
    {
        return $request->validate([
            'full_name' => ['required', 'string', 'max:160'],
            'phone' => ['nullable', 'string', 'max:60'],
            'identity_number' => ['nullable', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:160'],
            'address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);
    }

    private function validateIncome(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'type' => ['required', Rule::in(['daily_rent', 'weekly_rent', 'monthly_rent', 'yearly_rent', 'deposit', 'service', 'other'])],
            'amount' => ['required', 'numeric', 'min:0'],
            'paid_amount' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
            'payment_method' => ['required', Rule::in(['cash', 'bank_transfer', 'credit_card', 'other'])],
            'location_id' => ['nullable', 'exists:locations,id'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'customer_id' => ['nullable', 'exists:customers,id'],
            'notes' => ['nullable', 'string'],
        ]);
        $data['payment_status'] = $this->paymentStatus($data['amount'], $data['paid_amount']);

        return $data;
    }

    private function validateExpense(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'category' => ['required', Rule::in(['personnel', 'water', 'electricity', 'gas', 'internet', 'dues', 'cleaning', 'maintenance', 'inventory', 'tax', 'rent', 'other'])],
            'amount' => ['required', 'numeric', 'min:0'],
            'date' => ['required', 'date'],
            'payment_status' => ['required', Rule::in(['paid', 'unpaid'])],
            'location_id' => ['nullable', 'exists:locations,id'],
            'room_id' => ['nullable', 'exists:rooms,id'],
            'notes' => ['nullable', 'string'],
        ]);
    }

    private function financeViewData(Request $request, string $model, string $key): array
    {
        return [
            $key => $model::with(['location', 'room', 'customer'])->filter($request)->latest()->paginate(12),
            'locations' => Location::active()->orderBy('name')->get(),
            'rooms' => Room::active()->orderBy('name')->get(),
            'customers' => Customer::active()->orderBy('full_name')->get(),
        ];
    }

    private function periodTotals(): array
    {
        $periods = [
            'daily' => [now()->startOfDay(), now()->endOfDay()],
            'weekly' => [now()->startOfWeek(), now()->endOfWeek()],
            'monthly' => [now()->startOfMonth(), now()->endOfMonth()],
            'yearly' => [now()->startOfYear(), now()->endOfYear()],
        ];

        return collect($periods)->map(fn ($range) => $this->profitSummary($range[0], $range[1]))->all();
    }

    private function profitSummary(Carbon $start, Carbon $end): array
    {
        $income = (float) Income::whereBetween('date', [$start, $end])->sum('paid_amount');
        $expense = (float) Expense::whereBetween('date', [$start, $end])->sum('amount');

        return ['income' => $income, 'expense' => $expense, 'net' => $income - $expense];
    }

    private function profitRows(Carbon $start, Carbon $end, string $type): array
    {
        $model = $type === 'room' ? Room::query() : Location::query();

        return $model->orderBy('name')->get()->map(function ($item) use ($start, $end, $type) {
            $column = $type === 'room' ? 'room_id' : 'location_id';
            $income = (float) Income::where($column, $item->id)->whereBetween('date', [$start, $end])->sum('paid_amount');
            $expense = (float) Expense::where($column, $item->id)->whereBetween('date', [$start, $end])->sum('amount');

            return ['name' => $item->name, 'income' => $income, 'expense' => $expense, 'net' => $income - $expense];
        })->all();
    }

    private function locationOccupancy()
    {
        return Location::withCount('rooms')->get()->map(function (Location $location) {
            $occupied = Stay::activeOn(today())->whereHas('room', fn ($query) => $query->where('location_id', $location->id))->count();
            $rate = $location->rooms_count > 0 ? round(($occupied / $location->rooms_count) * 100) : 0;

            return ['name' => $location->name, 'occupied' => $occupied, 'total' => $location->rooms_count, 'rate' => $rate];
        });
    }

    private function paymentStatus(float $amount, float $paid): string
    {
        if ($paid <= 0) {
            return 'unpaid';
        }

        return $paid >= $amount ? 'paid' : 'partial';
    }

    private function log(string $title, ?string $body = null): void
    {
        ActivityLog::create(['user_id' => auth()->id(), 'title' => $title, 'body' => $body]);
    }
}
