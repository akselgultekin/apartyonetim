<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

#[Fillable(['customer_id', 'room_id', 'check_in', 'check_out', 'rental_type', 'total_rent', 'paid_amount', 'payment_status', 'checked_out_at', 'notes'])]
class Stay extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return ['check_in' => 'date', 'check_out' => 'date', 'checked_out_at' => 'datetime'];
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeActiveOn(Builder $query, CarbonInterface $date): Builder
    {
        return $query->whereDate('check_in', '<=', $date)->whereDate('check_out', '>=', $date)->whereNull('checked_out_at');
    }

    public function scopeFilter(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->filled('room_id'), fn ($q) => $q->where('room_id', $request->integer('room_id')))
            ->when($request->filled('customer_id'), fn ($q) => $q->where('customer_id', $request->integer('customer_id')))
            ->when($request->filled('payment_status'), fn ($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->filled('start'), fn ($q) => $q->whereDate('check_in', '>=', $request->date('start')))
            ->when($request->filled('end'), fn ($q) => $q->whereDate('check_out', '<=', $request->date('end')));
    }
}
