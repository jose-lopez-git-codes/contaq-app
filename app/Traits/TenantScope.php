<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait TenantScope
{
    protected static function bootTenantScope()
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('created_by', Auth::id());
            }
        });

        static::creating(function ($model) {
            if (Auth::check() && !$model->created_by) {
                $model->created_by = Auth::id();
            }
        });
    }

    public function scopeForCurrentUser(Builder $query)
    {
        return $query->where('created_by', Auth::id());
    }

    public function scopeForUser(Builder $query, $userId)
    {
        return $query->where('created_by', $userId);
    }
}