<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private array $products = [
        ['id' => 1, 'name' => 'Laptop', 'price' => 15000000, 'stock' => 10],
        ['id' => 2, 'name' => 'Mouse', 'price' => 250000, 'stock' => 50],
        ['id' => 3, 'name' => 'Keyboard', 'price' => 450000, 'stock' => 30],
    ];

    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Data produk berhasil diambil',
            'data' => $this->products,
        ]);
    }

    public function show(int $id): \Illuminate\Http\JsonResponse
    {
        $product = collect($this->products)->firstWhere('id', $id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk ditemukan',
            'data' => $product,
        ]);
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'name'  => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $newProduct = [
            'id'    => count($this->products) + 1,
            'name'  => $request->name,
            'price' => $request->price,
            'stock' => $request->stock,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => $newProduct,
        ], 201);
    }
}
