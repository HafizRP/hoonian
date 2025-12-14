<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'address',
        'city',
        'thumbnail',
        'description',
        'land_area',
        'building_area',
        'bedrooms',
        'bathrooms',
        'floors',
        'maps_url',
        'status',
        'featured',
        'popular',
        'owner_id',
        'property_type'
    ];

    public function owner() {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function gallery() {
        return $this->hasMany(PropertyGallery::class, 'property_id');
    }
}
