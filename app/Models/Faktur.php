<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Faktur extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'faktur';
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function detail()
    {
        return $this->hasMany(DetailFaktur::class, 'faktur_id');
    }

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'faktur_id');
    }
}
