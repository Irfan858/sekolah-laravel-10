<?php

namespace App\Http\Controllers\Api;

use App\Models\Photo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use function PHPSTORM_META\map;

class PhotoController extends Controller
{
    public function index()
    {
        $photos = Photo::latest()->paginate(6);

        return response()->json([
            "response" => [
                "status" => 200,
                "message" => "List Data Foto"
            ],
            "data" => $photos
        ], 200);
    }

    public function PhotoHomePage()
    {
        $photos = Photo::latest()->take(2)->get();

        return response()->json([
            "response" => [
                "status" => 200,
                "message" => "List Data Foto Homepage"
            ],
            "data" => $photos
        ], 200);
    }
}
