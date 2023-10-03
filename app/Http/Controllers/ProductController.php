<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function store(ProductStoreRequest $request)
    {
        $validatedData = $request->validated();

        $response = $this->productService->store($validatedData);

        return response()->json($response)->setStatusCode($response['statusCode']);
    }

    public function getAll()
    {
        $response = $this->productService->getAll();

        return response()->json($response)
            ->setStatusCode($response['statusCode']);
    }

    public function getOneById(Product $product)
    {
        $response = $this->productService->getOneById($product);

        return response()->json($response)
            ->setStatusCode($response['statusCode']);
    }

    public function destroy(Product $product)
    {
        $response = $this->productService->destroy($product);

        return response()->json($response)
            ->setStatusCode($response['statusCode']);
    }
}
