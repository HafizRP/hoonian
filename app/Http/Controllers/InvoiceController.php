<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Generate invoice for a transaction and download as PDF
     */
    public function generateAndDownload($transactionId)
    {
        $transaction = Transaction::with(['property', 'user'])->findOrFail($transactionId);

        // Check if transaction is accepted
        if ($transaction->status !== 'accepted') {
            return back()->with('error', 'Invoice can only be generated for accepted transactions.');
        }

        DB::beginTransaction();
        try {
            // Generate invoice number if not exists
            if (!$transaction->hasInvoice()) {
                $invoiceNumber = Transaction::generateInvoiceNumber();

                // Calculate tax and total
                $taxRate = 10; // 10% tax
                $subtotal = $transaction->amount;
                $taxAmount = $subtotal * ($taxRate / 100);
                $totalAmount = $subtotal + $taxAmount;

                // Set due date (30 days from now)
                $dueDate = now()->addDays(30);

                // Update transaction with invoice details
                $transaction->update([
                    'invoice_number' => $invoiceNumber,
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'total_amount' => $totalAmount,
                    'due_date' => $dueDate,
                ]);

                // Reload to get updated data
                $transaction->refresh();
            }

            DB::commit();

            // Generate PDF
            $pdf = Pdf::loadView('admin.invoice.pdf', [
                'transaction' => $transaction
            ]);

            // Download PDF
            return $pdf->download('invoice-' . $transaction->invoice_number . '.pdf');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to generate invoice: ' . $e->getMessage());
        }
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        if (!$transaction->hasInvoice()) {
            return back()->with('error', 'Invoice not found.');
        }

        if ($transaction->isPaid()) {
            return back()->with('info', 'Invoice is already marked as paid.');
        }

        $request->validate([
            'payment_method' => 'required|string|max:255',
            'paid_at' => 'nullable|date',
        ]);

        $transaction->update([
            'payment_method' => $request->payment_method,
            'paid_at' => $request->paid_at ?? now(),
        ]);

        return back()->with('success', 'Invoice marked as paid successfully!');
    }

    /**
     * Cancel invoice
     */
    public function cancel($id)
    {
        $transaction = Transaction::findOrFail($id);

        if (!$transaction->hasInvoice()) {
            return back()->with('error', 'Invoice not found.');
        }

        if ($transaction->isPaid()) {
            return back()->with('error', 'Cannot cancel a paid invoice.');
        }

        DB::beginTransaction();
        try {
            $transaction->update([
                'invoice_number' => null,
                'tax_rate' => 0,
                'tax_amount' => 0,
                'total_amount' => null,
                'payment_method' => null,
                'paid_at' => null,
                'due_date' => null,
                'notes' => null,
            ]);

            DB::commit();
            return back()->with('success', 'Invoice cancelled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to cancel invoice: ' . $e->getMessage());
        }
    }
}
