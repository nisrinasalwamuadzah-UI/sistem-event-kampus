<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    protected $fillable = [
        'nama_event',
        'tanggal',
        'tempat',
        'deskripsi',
        'poster'
    ];

    public function kehadirans()
    {
        return $this->hasMany(Kehadiran::class);
    }
}