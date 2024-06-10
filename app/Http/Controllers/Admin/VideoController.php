<?php

namespace App\Http\Controllers\Admin;

use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VideoController extends Controller
{
    /**
     *  _construct
     *
     *  @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:videos.index|videos.create|videos.edit|videos.delete']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $videos = Video::latest()->when(request()->q, function ($videos) {
            $videos = $videos->where('name', 'like', '%' . request()->q . '%');
        })->paginate(10);

        return view("admin.video.index", compact("videos"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.video.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "title" => "required",
            "embed" => "required"
        ]);

        $video = Video::create([
            "title" => $request->input("title"),
            "embeded" => $request->input("embed")
        ]);

        if ($video) {
            // redirect dengan pesan sukses
            return redirect()->route('admin.video.index')->with(['success' => 'Data Berhasil Disimpan !']);
        } else {
            // redirect dengan pesan error
            return redirect()->route('admin.video.index')->with(['error' => 'Data Gagal Disimpan !']);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Video $video)
    {
        return view("admin.video.edit", compact("video"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        $this->validate($request, [
            "title" => "required",
            "embed" => "required"
        ]);

        $video = Video::findOrFail($video->id);
        $video->update([
            "title" => $request->input("title"),
            "embeded" => $request->input("embed")
        ]);

        if ($video) {
            // redirect dengan pesan sukses
            return redirect()->route('admin.video.index')->with(['success' => 'Data Berhasil Di Update !']);
        } else {
            // redirect dengan pesan error
            return redirect()->route('admin.video.index')->with(['error' => 'Data Gagal Di Update !']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $video = Video::findOrFail($id);
        $video->delete();

        if ($video) {
            return response()->json([
                'status' => 'success'
            ]);
        } else {
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
