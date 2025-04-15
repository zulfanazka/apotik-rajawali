<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
    protected $table = 'inventory';  // Pastikan nama tabel di sini sesuai
    protected $guarded = ['id'];
}

