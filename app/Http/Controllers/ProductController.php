<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\ProductCollection;
use Illuminate\Http\Request as HttpRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of all products.
     */
    public function index(HttpRequest $request)
    {
        $products = Product::with(['sizes', 'collections'])
            ->when($request->input('type') != '', function ($query) use ($request) {
                $query->whereHas('collections', fn($query) => $query->where('name', $request->input('type')));
            })
            ->paginate(9);
        return new ProductCollection(resource: $products);
    }

    public function show($id)
    {
        return new ProductResource(Product::findOrFail($id)->load(['sizes', 'collections']));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(StoreProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());

        return new ProductResource($product);
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
