<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function listBidding(Request $request)
    {
        $userId = auth()->id();

        // Ambil list properti milik user untuk dropdown filter
        $properties = Property::where('owner_id', $userId)->get();

        // Selling (Incoming Bids) - Orang lain nawar properti kita
    $sellingQuery = Transaction::with(['property', 'user'])
        ->whereHas('property', function ($query) use ($userId) {
            $query->where('owner_id', $userId);
        });

    // Buying (Outbound Bids) - Kita nawar properti orang
    $buyingQuery = Transaction::with(['property.owner']) // Eager load owner
        ->where('user_id', $userId);

    // Apply Filters to BOTH queries
    // Filter by Property
    if ($request->has('property_id') && $request->property_id != '') {
        $sellingQuery->where('property_id', $request->property_id);
        $buyingQuery->where('property_id', $request->property_id);
    }

    // Filter by Status
    if ($request->has('status') && $request->status != '') {
        $sellingQuery->where('status', $request->status);
        $buyingQuery->where('status', $request->status);
    }

    $sellingBids = $sellingQuery->orderBy('created_at', 'desc')->get();
    $buyingBids = $buyingQuery->orderBy('created_at', 'desc')->get();

    return view('transaction.list', compact('sellingBids', 'buyingBids', 'properties'));
    }

    public function createBidding(Request $request)
    {
        // Hanya validasi property_id
        $request->validate([
            'property_id' => 'required|exists:properties,id',
        ]);

        $property = Property::findOrFail($request->property_id);

    // Prevent owner from bidding on their own property
    if ($property->owner_id == auth()->id()) {
        return back()->with('error', 'You cannot bid on your own property!');
    }

        // // Contoh logika: Bid otomatis naik $1,000 dari harga tertinggi saat ini
        // $increment = 1000;
        // $newBidAmount = $property->current_price + $increment;

        // Set semua bid lama jadi outbid
        Transaction::where('property_id', $property->id)
            ->where('status', 'leading')
            ->update(['status' => 'outbid']);

        // Simpan bid baru
        Transaction::create([
            'user_id' => Auth::id(),
            'property_id' => $property->id,
            'amount' => $property->price,
            'status' => 'leading',
        ]);

        // Update harga properti
        return back()->with('success', 'Bid berhasil ditempatkan!');
    }


    /**
     * Menerima Bid
     */
    public function accept($id)
    {
        $bid = Transaction::findOrFail($id);
        $property = Property::findOrFail($bid->property_id);

        DB::beginTransaction();
        try {
            // 1. Set bid ini menjadi 'accepted'
            $bid->update(['status' => 'accepted']);

            // 2. Tolak semua bid lain untuk properti ini
            Transaction::where('property_id', $property->id)
                ->where('id', '!=', $id)
                ->update(['status' => 'outbid']);

            $property->update(['status' => '0']);

            DB::commit();
            return back()->with('success', 'Bid berhasil diterima dan properti ditandai terjual.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat memproses data.');
        }
    }

    /**
     * Menolak Bid
     */
    public function decline($id)
    {
        $bid = Transaction::findOrFail($id);

        // Cek jika yang ditolak adalah bid tertinggi saat ini (leading)
        // Anda mungkin perlu mencari penawar tertinggi kedua untuk dijadikan 'leading' kembali

        $bid->update(['status' => 'outbid']);

        return back()->with('info', 'Bid telah ditolak.');
    }

    public function backofficeList(Request $request) {
        // Base query
        $query = Transaction::with(['property', 'user']);

        // Apply filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('property_id')) {
            $query->where('property_id', $request->property_id);
        }

        // Get filtered transactions
        $transactions = $query->orderBy('created_at', 'desc')->get();

        // Calculate statistics
        $stats = [
            'total_count' => $transactions->count(),
            'total_revenue' => $transactions->where('status', 'accepted')->sum('amount'),
            'success_count' => $transactions->where('status', 'accepted')->count(),
            'pending_count' => $transactions->where('status', 'leading')->count(),
            'failed_count' => $transactions->where('status', 'outbid')->count(),
        ];

        // Get all properties for filter dropdown
        $properties = Property::all();

        return view('admin.transaction.index', compact('transactions', 'properties', 'stats'));
    }
}
