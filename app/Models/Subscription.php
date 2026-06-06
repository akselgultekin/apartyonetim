<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

#[Fillable(['utility_type', 'subscriber_number', 'company', 'due_date', 'bill_amount', 'payment_status', 'location_id', 'room_id', 'notes'])]
class Subscription extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return ['due_date' => 'date'];
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeFilter(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->filled('location_id'), fn ($q) => $q->where('location_id', $request->integer('location_id')))
            ->when($request->filled('room_id'), fn ($q) => $q->where('room_id', $request->integer('room_id')))
            ->when($request->filled('payment_status'), fn ($q) => $q->where('payment_status', $request->payment_status));
    }
}
