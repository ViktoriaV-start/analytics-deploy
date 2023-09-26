<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'login',
        'password'
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
