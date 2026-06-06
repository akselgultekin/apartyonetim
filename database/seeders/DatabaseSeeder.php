<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Location;
use App\Models\MaintenanceLog;
use App\Models\Room;
use App\Models\Stay;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $center = Location::create([
            'name' => 'Merkez Apart',
            'address' => 'Ataturk Cd. No: 12',
            'description' => 'Günlük ve aylık kiralamaların ana lokasyonu.',
            'is_active' => true,
        ]);

        $sea = Location::create([
            'name' => 'Sahil Apart',
            'address' => 'Liman Sk. No: 8',
            'description' => 'Yaz sezonu agirlikli daireler.',
            'is_active' => true,
        ]);

        $rooms = collect([
            ['location_id' => $center->id, 'name' => '101', 'type' => 'Studyo', 'capacity' => 2, 'daily_price' => 1250, 'weekly_price' => 7000, 'monthly_price' => 24000, 'yearly_price' => 240000, 'deposit' => 5000, 'status' => 'occupied'],
            ['location_id' => $center->id, 'name' => '102', 'type' => '1+1', 'capacity' => 3, 'daily_price' => 1500, 'weekly_price' => 8500, 'monthly_price' => 28500, 'yearly_price' => 280000, 'deposit' => 6500, 'status' => 'available'],
            ['location_id' => $center->id, 'name' => '201', 'type' => '2+1', 'capacity' => 4, 'daily_price' => 2200, 'weekly_price' => 12500, 'monthly_price' => 38000, 'yearly_price' => 365000, 'deposit' => 9000, 'status' => 'maintenance', 'cleaning_status' => 'waiting'],
            ['location_id' => $sea->id, 'name' => 'A1', 'type' => '1+1 Deniz', 'capacity' => 3, 'daily_price' => 2100, 'weekly_price' => 12000, 'monthly_price' => 41000, 'yearly_price' => 390000, 'deposit' => 8000, 'status' => 'occupied'],
            ['location_id' => $sea->id, 'name' => 'A2', 'type' => '2+1 Deniz', 'capacity' => 5, 'daily_price' => 3100, 'weekly_price' => 18000, 'monthly_price' => 59000, 'yearly_price' => 540000, 'deposit' => 12000, 'status' => 'available'],
        ])->map(fn ($data) => Room::create(array_merge([
            'cleaning_status' => 'clean',
            'maintenance_status' => 'normal',
            'notes' => null,
        ], $data)));

        $customers = collect([
            ['full_name' => 'Ayse Yilmaz', 'phone' => '0555 111 22 33', 'identity_number' => '12345678910', 'email' => 'ayse@example.com'],
            ['full_name' => 'Mehmet Demir', 'phone' => '0555 444 55 66', 'identity_number' => 'P9876543', 'email' => 'mehmet@example.com'],
            ['full_name' => 'Elif Kaya', 'phone' => '0555 777 88 99', 'identity_number' => '23456789101', 'email' => 'elif@example.com'],
        ])->map(fn ($data) => Customer::create(array_merge(['is_active' => true], $data)));

        $stayOne = Stay::create([
            'customer_id' => $customers[0]->id,
            'room_id' => $rooms[0]->id,
            'check_in' => now()->subDays(2)->toDateString(),
            'check_out' => now()->addDays(3)->toDateString(),
            'rental_type' => 'daily',
            'total_rent' => 6250,
            'paid_amount' => 4000,
            'payment_status' => 'partial',
        ]);

        $stayTwo = Stay::create([
            'customer_id' => $customers[1]->id,
            'room_id' => $rooms[3]->id,
            'check_in' => now()->subWeek()->toDateString(),
            'check_out' => now()->addWeeks(3)->toDateString(),
            'rental_type' => 'monthly',
            'total_rent' => 41000,
            'paid_amount' => 41000,
            'payment_status' => 'paid',
        ]);

        foreach ([$stayOne, $stayTwo] as $stay) {
            Income::create([
                'title' => 'Kira geliri - '.$stay->customer->full_name,
                'type' => $stay->rental_type.'_rent',
                'amount' => $stay->total_rent,
                'paid_amount' => $stay->paid_amount,
                'date' => $stay->check_in,
                'payment_method' => 'cash',
                'payment_status' => $stay->payment_status,
                'location_id' => $stay->room->location_id,
                'room_id' => $stay->room_id,
                'customer_id' => $stay->customer_id,
            ]);
        }

        Income::create(['title' => 'Depozito', 'type' => 'deposit', 'amount' => 8000, 'paid_amount' => 8000, 'date' => now()->subDays(5), 'payment_method' => 'bank_transfer', 'payment_status' => 'paid', 'location_id' => $sea->id, 'room_id' => $rooms[3]->id, 'customer_id' => $customers[1]->id]);
        Expense::create(['title' => 'Elektrik faturasi', 'category' => 'electricity', 'amount' => 6200, 'date' => now()->subDays(4), 'payment_status' => 'paid', 'location_id' => $center->id]);
        Expense::create(['title' => 'Temizlik personeli', 'category' => 'cleaning', 'amount' => 3500, 'date' => now()->subDay(), 'payment_status' => 'paid', 'location_id' => $sea->id]);
        Expense::create(['title' => 'İnternet faturası', 'category' => 'internet', 'amount' => 1250, 'date' => now()->addDays(6), 'payment_status' => 'unpaid', 'location_id' => $center->id]);

        Subscription::create(['utility_type' => 'electricity', 'subscriber_number' => 'ELK-100-22', 'company' => 'Elektrik AS', 'due_date' => now()->addDays(6), 'bill_amount' => 1250, 'payment_status' => 'unpaid', 'location_id' => $center->id]);
        Subscription::create(['utility_type' => 'water', 'subscriber_number' => 'SU-884-10', 'company' => 'Su Idaresi', 'due_date' => now()->addDays(12), 'bill_amount' => 860, 'payment_status' => 'unpaid', 'location_id' => $sea->id]);

        MaintenanceLog::create(['room_id' => $rooms[2]->id, 'type' => 'cleaning', 'status' => 'open', 'title' => 'Çıkış sonrası temizlik']);
    }
}
