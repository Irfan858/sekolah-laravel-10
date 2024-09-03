<?php

namespace App\Http\Controllers\API;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::latest()->paginate(10);

        return response()->json([
            "response" => [
                "status" => 200,
                "message" => "List Data Tags"
            ],
            "data" => $tags
        ], 200);
    }

    public function show($slug)
    {
        $tag = Tag::where('slug', $slug)->first();

        if ($tag) {
            return response()->json([
                "response" => [
                    "status" => 200,
                    "message" => "Data Post Berdasrkan Tag: " . $tag->name
                ],
                "data" => $tag->posts()->latest()->paginate(6)
            ], 200);
        } else {
            return response()->json([
                "response" => [
                    "status" => 404,
                    "message" => "Data Post Berdasarkan Tag Tidak Ditemukan!"
                ],
                "data" => null
            ], 404);
        }
    }
}
