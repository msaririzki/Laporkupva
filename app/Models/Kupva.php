<?php

namespace App\Models;

use Database\Factories\KupvaFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'license_number',
    'license_status',
    'address',
    'regency',
    'district',
    'village',
    'latitude',
    'longitude',
    'license_expires_at',
    'is_active',
])]
class Kupva extends Model
{
    /** @use HasFactory<KupvaFactory> */
    use HasFactory;

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'license_expires_at' => 'date',
            'is_active' => 'boolean',
        ];
    }
}
