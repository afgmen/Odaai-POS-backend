<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Payment::with('order');

        if ($request->has('order_id')) {
            $query->where('order_id', $request->order_id);
        }

        $payments = $query->latest()->get();

        return response()->json($payments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|in:cash,card,other',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            $payment = Payment::create([
                'order_id' => $validated['order_id'],
                'payment_method' => $validated['payment_method'],
                'amount' => $validated['amount'],
                'status' => 'completed',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Check if order is fully paid
            $order = Order::findOrFail($validated['order_id']);
            $totalPaid = $order->payments()->where('status', 'completed')->sum('amount');

            if ($totalPaid >= $order->total) {
                $order->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);

                // Update table status
                if ($order->table_id) {
                    $order->table()->update(['status' => 'available']);
                }
            }

            return response()->json($payment->load('order'), 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::with('order')->findOrFail($id);

        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payment = Payment::findOrFail($id);

        $validated = $request->validate([
            'payment_method' => 'sometimes|in:cash,card,other',
            'amount' => 'sometimes|numeric|min:0',
            'status' => 'in:pending,completed,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        $payment->update($validated);

        return response()->json($payment->load('order'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json(['message' => 'Payment deleted successfully']);
    }
}
