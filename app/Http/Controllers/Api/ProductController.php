<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $products = Product::with('category:id,name')
            ->orderBy('id', 'desc')
            ->get()
            ->map(function (Product $product) {
                return $this->transformProduct($product);
            });

        return response()->json([
            'data' => $products,
        ]);
    }

    public function show(Product $product): JsonResponse
    {
        $product->load('category:id,name');

        return response()->json([
            'data' => $this->transformProduct($product),
        ]);
    }

    private function transformProduct(Product $product): array
    {
        $image = $product->image ? url('images/products/'.$product->image) : null;

        return [
            'id' => $product->id,
            'name' => $product->name,
            'brand' => $product->brand,
            'category' => optional($product->category)->name,
            'description' => $product->description,
            'price' => (float) $product->price,
            'stock' => $product->stock,
            'image' => $product->image,
            'image_url' => $image,
            'screen' => $product->screen,
            'cpu' => $product->cpu,
            'ram' => $product->ram,
            'storage' => $product->storage,
            'battery' => $product->battery,
            'os' => $product->os,
        ];
    }
}
