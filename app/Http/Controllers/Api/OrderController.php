<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with(['table', 'user', 'items.product', 'payments']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('table_id')) {
            $query->where('table_id', $request->table_id);
        }

        $orders = $query->latest()->get();

        return response()->json($orders);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'table_id' => 'nullable|exists:tables,id',
            'user_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'order_type' => 'nullable|in:dine_in,takeaway,delivery',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.modifiers' => 'nullable|array',
            'items.*.notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($validated) {
            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(Order::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_number' => $orderNumber,
                'table_id' => $validated['table_id'] ?? null,
                'user_id' => $validated['user_id'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'order_type' => $validated['order_type'] ?? 'dine_in',
                'status' => 'pending',
            ]);

            $subtotal = 0;

            foreach ($validated['items'] as $item) {
                $product = \App\Models\Product::findOrFail($item['product_id']);
                $totalPrice = $product->price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->price,
                    'total_price' => $totalPrice,
                    'modifiers' => $item['modifiers'] ?? null,
                    'notes' => $item['notes'] ?? null,
                ]);

                $subtotal += $totalPrice;
            }

            // Get current settings for calculations
            $settingsSnapshot = Setting::getSnapshot();
            
            // Calculate service charge
            $serviceCharge = 0;
            if ($settingsSnapshot['service_charge_enabled']) {
                $serviceCharge = $subtotal * ($settingsSnapshot['service_charge_rate'] / 100);
            }
            
            // Calculate VAT (on subtotal + service charge)
            $vat = 0;
            if ($settingsSnapshot['vat_enabled']) {
                $vat = ($subtotal + $serviceCharge) * ($settingsSnapshot['vat_rate'] / 100);
            }
            
            $total = $subtotal + $serviceCharge + $vat;

            $order->update([
                'subtotal' => $subtotal,
                'service_charge' => $serviceCharge,
                'tax' => $vat,
                'total' => $total,
                'settings_snapshot' => $settingsSnapshot,
            ]);

            // Update table status if table_id provided
            if ($order->table_id) {
                $order->table()->update(['status' => 'occupied']);
            }

            return response()->json($order->load(['items.product', 'table', 'user']), 201);
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['table', 'user', 'items.product', 'payments'])->findOrFail($id);

        return response()->json($order);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'table_id' => 'nullable|exists:tables,id',
            'status' => 'in:pending,sent_to_kitchen,preparing,ready,completed,cancelled,on_hold',
            'order_type' => 'nullable|in:dine_in,takeaway,delivery',
            'discount' => 'nullable|numeric|min:0',
            'service_charge' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if (isset($validated['status'])) {
            if ($validated['status'] === 'sent_to_kitchen' && !$order->sent_to_kitchen_at) {
                $validated['sent_to_kitchen_at'] = now();
            }
            if ($validated['status'] === 'completed' && !$order->completed_at) {
                $validated['completed_at'] = now();
            }
        }

        // Recalculate total if discount/service_charge/tax changed
        if (isset($validated['discount']) || isset($validated['service_charge']) || isset($validated['tax'])) {
            $discount = $validated['discount'] ?? $order->discount;
            $serviceCharge = $validated['service_charge'] ?? $order->service_charge;
            $tax = $validated['tax'] ?? $order->tax;

            $validated['total'] = $order->subtotal - $discount + $serviceCharge + $tax;
        }

        $order->update($validated);

        return response()->json($order->load(['items.product', 'table', 'user']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        
        // Update table status if needed
        if ($order->table_id) {
            $order->table()->update(['status' => 'available']);
        }

        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }

    /**
     * Send order to kitchen
     */
    public function sendToKitchen(string $id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'status' => 'sent_to_kitchen',
            'sent_to_kitchen_at' => now(),
        ]);

        return response()->json($order->load(['items.product', 'table']));
    }

    /**
     * Complete order
     */
    public function complete(string $id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        // Update table status
        if ($order->table_id) {
            $order->table()->update(['status' => 'available']);
        }

        return response()->json($order->load(['items.product', 'table', 'payments']));
    }
}
