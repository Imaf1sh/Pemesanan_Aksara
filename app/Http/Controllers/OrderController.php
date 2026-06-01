<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')->orderBy('timestamp', 'desc')->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
            'total' => 'required|numeric',
        ]);

        try {
            $order = DB::transaction(function () use ($request) {
                $orderId = uniqid('ord_');
                
                $customerName = $request->customer_name ?? $request->customer ?? 'Guest';
                
                $order = Order::create([
                    'id' => $orderId,
                    'customer_name' => $customerName,
                    'total' => $request->total,
                    'payment_method' => $request->payment_method ?? 'Cash',
                    'order_type' => $request->order_type ?? 'Dine In',
                    'status' => 'pending',
                    'timestamp' => Carbon::now(),
                ]);

                foreach ($request->items as $item) {
                    $productId = $item['id'] ?? null;
                    $itemName = $item['name'] ?? $item['title'] ?? 'Product';
                    
                    // Save item
                    OrderItem::create([
                        'order_id' => $orderId,
                        'product_id' => $productId,
                        'name' => $itemName,
                        'price' => $item['price'],
                        'qty' => $item['qty'],
                        'notes' => $item['notes'] ?? null,
                    ]);

                    // Reduce stock if product is linked
                    if ($productId) {
                        $product = Product::find($productId);
                        if ($product) {
                            $product->stock = max(0, $product->stock - $item['qty']);
                            $product->save();
                        }
                    }
                }

                return $order;
            });

            $order->load('items');

            return response()->json([
                'success' => true,
                'status' => 'success',
                'order' => $order
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'status' => 'required|string',
        ]);

        $order = Order::find($request->id);

        if ($order) {
            $order->status = $request->status;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Order not found'
        ], 404);
    }
}
