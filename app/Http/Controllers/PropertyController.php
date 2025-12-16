<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyGallery;
use App\Models\PropertyType;
use App\Models\Review;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
        $properties_type = PropertyType::all();
        return view('property.create', compact('properties_type'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input sesuai struktur table
        $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric',
            'address'       => 'required|string',
            'city'          => 'required|string',
            'thumbnail'     => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'images.*'      => 'image|mimes:jpeg,png,jpg|max:2048', // Untuk Gallery
            'property_type' => 'required|exists:properties_type,id',
            'owner_id'      => 'required|exists:users,id',
        ]);

        // Gunakan Transaction agar jika salah satu gagal, data tidak tersimpan setengah-setengah
        DB::beginTransaction();

        try {
            // 2. Upload Thumbnail Utama
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('properties/thumbnails', 'public');
            }

            // 3. Simpan ke table properties
            $property = Property::create([
                'name'          => $request->name,
                'price'         => $request->price,
                'address'       => $request->address,
                'city'          => $request->city,
                'thumbnail'     => $thumbnailPath,
                'description'   => $request->description,
                'land_area'     => $request->land_area,
                'building_area' => $request->building_area,
                'bedrooms'      => $request->bedrooms,
                'bathrooms'     => $request->bathrooms,
                'floors'        => $request->floors,
                'maps_url'      => $request->maps_url,
                'featured'      => $request->featured ?? 0,
                'popular'       => $request->popular ?? 0,
                'status'        => $request->status ?? '1',
                'owner_id'      => $request->owner_id,
                'property_type' => $request->property_type,
            ]);

            // 4. Proses Multiple Images untuk table properties_gallery
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $galleryPath = $file->store('properties/gallery', 'public');

                    PropertyGallery::create([
                        'property_id' => $property->id,
                        'url'         => $galleryPath,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('properties.index')->with('success', 'Property created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
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

        // Check if current user has already bid on this property
        $userHasBid = false;
        if (auth()->check()) {
            $userHasBid = Transaction::where('property_id', $id)
                ->where('user_id', auth()->id())
                ->exists();
        }

        return view('property.detail', compact('property', 'reviews', 'userHasBid'));
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
    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);

        // 1. Validasi
        $request->validate([
            'name'          => 'required|string|max:255',
            'price'         => 'required|numeric',
            'thumbnail'     => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'images.*'      => 'image|mimes:jpeg,png,jpg|max:2048',
            'property_type' => 'required|exists:properties_type,id',
        ]);

        DB::beginTransaction();

        try {
            $data = $request->all();

            // 2. Logika Update Thumbnail (Jika ada file baru)
            if ($request->hasFile('thumbnail')) {
                // Hapus thumbnail lama dari storage jika ada
                if ($property->thumbnail) {
                    Storage::disk('public')->delete($property->thumbnail);
                }
                // Simpan thumbnail baru
                $data['thumbnail'] = $request->file('thumbnail')->store('properties/thumbnails', 'public');
            }

            // 3. Konversi checkbox (featured/popular) agar tidak null
            $data['featured'] = $request->has('featured') ? 1 : 0;
            $data['popular'] = $request->has('popular') ? 1 : 0;

            // 4. Update data di tabel properties
            $property->update($data);

            // 5. Tambah Gallery Baru (Jika ada file gallery baru diupload)
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $file) {
                    $galleryPath = $file->store('properties/gallery', 'public');

                    PropertyGallery::create([
                        'property_id' => $property->id,
                        'url'         => $galleryPath,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('properties.index')->with('success', 'Property updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        try {
            DB::beginTransaction();

            // 1. Delete Thumbnail
            if ($property->thumbnail && Storage::disk('public')->exists($property->thumbnail)) {
                Storage::disk('public')->delete($property->thumbnail);
            }

            // 2. Delete Gallery Images
            foreach ($property->gallery as $gal) {
                if ($gal->url && Storage::disk('public')->exists($gal->url)) {
                    Storage::disk('public')->delete($gal->url);
                }
            }
            // Delete gallery records (cascade will handle this if set, but explicit is safer)
            $property->gallery()->delete();

            // 3. Delete Property Record
            $property->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Property deleted successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Failed to delete property: ' . $e->getMessage());
        }
    }

    public function home()
    {
        $popular_properties = Property::where('popular', true)->get();
        $top_agents = User::where('role', 2)->limit(3)->get();

        return view('index', compact('popular_properties', 'top_agents'));
    }

    public function properties(Request $request)
    {
        // Base query
        $query = Property::with(['owner']);

        // Apply filters
        if ($request->filled('city')) {
            $query->where('city', 'LIKE', '%' . $request->city . '%');
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('owner_id')) {
            $query->where('owner_id', $request->owner_id);
        }

        if ($request->filled('property_type')) {
            $query->where('property_type', $request->property_type);
        }

        // Get filtered properties
        $properties = $query->get();

        // Calculate statistics
        $stats = [
            'total_count' => $properties->count(),
            'total_value' => $properties->sum('price'),
            'available_count' => $properties->where('status', '1')->count(),
            'sold_count' => $properties->where('status', '0')->count(),
            'featured_count' => $properties->where('featured', true)->count(),
        ];

        // Get all owners and types for filter dropdowns
        $owners = User::whereIn('role', [1, 2])->get(); // Admin and Agents
        $types = PropertyType::all();

        return view('admin.property.index', compact('properties', 'stats', 'owners', 'types'));
    }

    public function backofficeCreate()
    {
        $types = PropertyType::all();
        $users = User::all();
        return view('admin.property.create', compact('types', 'users'));
    }

    public function backofficeEdit(Request $request, $id) {
        $property = Property::findOrFail($id);
        $types = PropertyType::all();
        $users = User::all();
        return view('admin.property.edit', compact('property', 'types', 'users'));
    }
}
