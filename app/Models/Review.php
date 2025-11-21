<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = "reviews";
    protected $fillable = ['customer_id', 'property_id', 'rating', 'description'];

    public function propertyReviews()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }

    public function customerReviews()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }
}
