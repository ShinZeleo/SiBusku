<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'seat_number',
        'row_index',
        'col_index',
        'deck',
        'section',
        'default_price',
        'is_active',
    ];

    protected $casts = [
        'default_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Relasi: BusSeat dimiliki oleh satu Bus
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    /**
     * Scope: Ambil kursi aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Urutkan berdasarkan baris dan kolom
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('row_index')->orderBy('col_index');
    }
}
