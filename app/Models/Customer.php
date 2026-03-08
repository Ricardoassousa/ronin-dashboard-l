<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Customer extends Model
{
    use HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'tax_number',
        'is_blocked',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('customer')
            ->logOnly(['first_name', 'last_name', 'email', 'phone', 'tax_number', 'is_blocked'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}