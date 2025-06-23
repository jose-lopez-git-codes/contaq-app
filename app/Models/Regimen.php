<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Regimen extends Model
{
    protected $fillable = ['nombre', 'created_by', 'updated_by'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });

        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
    }

    public function contribuyentes()
    {
        return $this->hasMany(Contribuyente::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
