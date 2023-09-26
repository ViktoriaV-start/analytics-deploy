<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'service_id'
    ];

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function service(): HasOne
    {
        return $this->hasOne(Service::class);
    }
}
