<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     *  __construct()
     * @return void
     */
    public function __construct()
    {
        $this->middleware(["permission:sliders.index|sliders.create|sliders.delete"]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sliders = Slider::latest()->when(request()->q, function ($sliders) {
            $sliders = $sliders->where("title", "like", "%" . request()->q . "%");
        })->paginate(10);

        return view("admin.slider.index", compact("sliders"));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "image" => "required|image",
        ]);

        // Upload Image
        $image = $request->file("image");
        $image->storeAs("public/sliders", $image->hashName());

        $slider = Slider::create([
            "image" => $image->hashName(),
        ]);

        if ($slider) {
            // redirect dengan pesan sukses
            return redirect()->route('admin.slider.index')->with(['success' => 'Data Berhasil Disimpan !']);
        } else {
            // redirect dengan pesan error
            return redirect()->route('admin.slider.index')->with(['error' => 'Data Gagal Disimpan !']);
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
    // public function edit(string $id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $slider = Slider::findOrFail($id);
        $image = Storage::disk('local')->delete('public/sliders/' . basename($slider->image));
        $slider->delete();

        if ($slider) {
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
