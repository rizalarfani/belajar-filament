<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailFaktur extends Model
{
    use HasFactory;

    protected $table = 'detail';
    protected $guarded  = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class)
            ->withDefault();
    }

    public function faktur()
    {
        return $this->belongsTo(Faktur::class);
    }
}
