<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'nim',
        'nama',
        'jurusan',
        'semester',
        'waktu_scan'
    ];

    protected $casts = [
        'waktu_scan' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}