<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(10);

        return response()->json([
            "response" => [
                "status" => 200,
                "message" => "List Data Categories"
            ],
            "data" => $categories
        ], 200);
    }

    public function show($slug)
    {
        $category = Category::where('slug', $slug)->first();

        if ($category) {
            return response()->json([
                "response" => [
                    "status" => 200,
                    "message" => "Data post Berdasarkan Kategori: " . $category->name
                ],
                "data" => $category->posts()->latest()->paginate(6)
            ], 200);
        } else {
            return response()->json([
                "response" => [
                    "status" => 404,
                    "message" => "Data Post Berdasarkan Kategori Tidak Ditemukan"
                ],
                "data" => null
            ], 404);
        }
    }
}
