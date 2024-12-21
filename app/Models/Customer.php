<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customer';
    protected $fillable = ['kode_customer', 'name', 'email', 'alamat', 'no_hp'];

    public function faktur()
    {
        return $this->hasMany(Faktur::class);
    }
}
