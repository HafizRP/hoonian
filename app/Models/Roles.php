<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;
    public $timestamps = false;

    // Pastikan nama tabel sesuai
    protected $table = 'roles';

    // Tambahkan field yang bisa diisi massal
    protected $fillable = ['name'];
}
