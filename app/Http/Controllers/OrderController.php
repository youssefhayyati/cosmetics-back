<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\OrderRequest;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function store(OrderRequest $request)
    {
        $data = $request->validated();
        info($data);
        $order = Order::create([
            'client_name' => $data['firstName'] . ' ' . $data['lastName'],
            'client_email' => $data['email'],
            'client_phone' => $data['phone'],
            'client_address' => $data['address'],
            'client_city' => $data['city'],
        ]);

        foreach ($data['products'] as $productData) {
            $product = Product::find($productData['id']);
            if ($product) {
                $order->products()->attach($product->id, [
                    'quantity' => $productData['quantity'],
                    'size' => $productData['size'],
                    'color' => $productData['color'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Order created successfully',
        ]);
    }
}
