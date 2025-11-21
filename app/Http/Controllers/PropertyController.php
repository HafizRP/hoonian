<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil query parameter
        $name = $request->query('name');
        $city = $request->query('city');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $type = $request->query('type');

        // Query builder awal
        $query = Property::query();

        // Filter berdasarkan nama (LIKE)
        if ($name) {
            $query->where('name', 'LIKE', "%{$name}%");
        }

        // Filter berdasarkan kota
        if ($city) {
            $query->where('city', 'LIKE', "%{$city}%");
        }

        // Filter berdasarkan rentang harga
        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }

        if ($type) {
            $query->where('property_type', '=', $type);
        }

        // Ambil data dengan pagination
        $properties = $query->paginate(10)->withQueryString();
        $properties_type = PropertyType::all();
        $featured = Property::where('featured', true)->get();

        return view('property.list', compact('properties', 'featured', 'properties_type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $property = Property::with(['owner'])->findOrFail($id);

        $reviews = Review::with(['propertyReviews', 'customerReviews'])
            ->whereHas('propertyReviews', function ($q) use ($property) {
                $q->where('owner_id', $property->owner->id);
        })->limit(15)->get();

        return view('property.detail', compact('property', 'reviews'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        //
    }

    public function home()
    {
        $popular_properties = Property::where('popular', true)->get();
        $top_agents = User::where('role', 2)->limit(3)->get();

        return view('index', compact('popular_properties', 'top_agents'));
    }
}
