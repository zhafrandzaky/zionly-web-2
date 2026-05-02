<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'code'    => 200,
            'success' => true,
            'message' => 'Data produk berhasil diambil',
            'data'    => Product::all(),
        ], 200);
    }

    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'code'    => 404,
                'success' => false,
                'message' => 'Produk tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'code'    => 200,
            'success' => true,
            'message' => 'Produk ditemukan',
            'data'    => $product,
        ], 200);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        if (Product::where('name', $validated['name'])->exists()) {
            return response()->json([
                'code'    => 409,
                'success' => false,
                'message' => 'Produk dengan nama ini sudah ada',
                'data'    => null,
            ], 409);
        }

        $product = Product::create($validated);

        return response()->json([
            'code'    => 201,
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data'    => $product,
        ], 201);
    }

    public function update(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'code'    => 404,
                'success' => false,
                'message' => 'Produk tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        $validated = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
        ]);

        $product->update($validated);

        return response()->json([
            'code'    => 200,
            'success' => true,
            'message' => 'Produk berhasil diperbarui',
            'data'    => $product,
        ], 200);
    }

    public function destroy(int $id): \Illuminate\Http\JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'code'    => 404,
                'success' => false,
                'message' => 'Produk tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        $product->delete();

        return response()->json([
            'code'    => 200,
            'success' => true,
            'message' => 'Produk berhasil dihapus',
            'data'    => null,
        ], 200);
    }
}
