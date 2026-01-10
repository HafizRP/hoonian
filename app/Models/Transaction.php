<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'property_id',
        'amount',
        'status',
        'invoice_number',
        'tax_rate',
        'tax_amount',
        'total_amount',
        'payment_method',
        'paid_at',
        'due_date',
        'notes'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'due_date' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Property
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // Invoice Helper Methods
    public static function generateInvoiceNumber()
    {
        $prefix = 'INV';
        $date = now()->format('Ymd');
        $invoicePrefix = $prefix . '-' . $date . '-';

        // Cari invoice number terakhir yang berawalan prefix hari ini
        $lastInvoice = self::where('invoice_number', 'like', $invoicePrefix . '%')
            ->orderBy('invoice_number', 'desc')
            ->first();

        if ($lastInvoice) {
            // Ambil 4 digit terakhir
            $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $invoicePrefix . $newNumber;
    }

    public function hasInvoice()
    {
        return !empty($this->invoice_number);
    }

    public function isPaid()
    {
        return $this->status === 'accepted' && $this->paid_at !== null;
    }

    public function isOverdue()
    {
        return !$this->isPaid() && $this->due_date && $this->due_date->isPast();
    }

    public function getStatusBadgeClass()
    {
        if ($this->isPaid()) {
            return 'badge-success';
        }

        if ($this->isOverdue()) {
            return 'badge-warning';
        }

        return match ($this->status) {
            'accepted' => 'badge-info',
            'leading' => 'badge-primary',
            'outbid' => 'badge-secondary',
            'pending' => 'badge-warning',
            default => 'badge-secondary'
        };
    }

    public function getInvoiceStatus()
    {
        if ($this->isPaid()) {
            return 'Paid';
        }

        if ($this->isOverdue()) {
            return 'Overdue';
        }

        if ($this->hasInvoice()) {
            return 'Sent';
        }

        return 'Draft';
    }
}
