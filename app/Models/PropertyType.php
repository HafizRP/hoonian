<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\never;

class PropertyType extends Model
{
    use HasFactory;
    protected $table = "properties_type";
}
