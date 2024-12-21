<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualan';
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id');
    }

    public function faktur()
    {
        return $this->hasMany(Faktur::class, 'id');
    }
}
