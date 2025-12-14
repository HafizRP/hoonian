<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use function PHPUnit\Framework\never;

class PropertyGallery extends Model
{
    use HasFactory;
    protected $table = "properties_gallery";
    protected $fillable = ['url', 'property_id'];
}
