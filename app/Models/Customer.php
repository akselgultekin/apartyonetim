<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

#[Fillable(['full_name', 'phone', 'identity_number', 'email', 'address', 'notes', 'is_active'])]
class Customer extends Model
{
    use HasFactory;

    public function stays()
    {
        return $this->hasMany(Stay::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFilter(Builder $query, Request $request): Builder
    {
        return $query
            ->when($request->filled('q'), fn ($q) => $q->where(fn ($inner) => $inner->where('full_name', 'like', '%'.$request->q.'%')->orWhere('phone', 'like', '%'.$request->q.'%')->orWhere('identity_number', 'like', '%'.$request->q.'%')))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status === 'active'));
    }
}
