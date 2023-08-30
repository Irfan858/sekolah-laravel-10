<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['permission:users.index|users.create|users.edit|users.delete']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->when(request()->q, function($users) {
            $users = $users->where('name','like','%'. request()->q .'%');
        })->paginate(10);

        return view('admin.user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::latest()->get();
        return view('admin.user.create', compact('roles'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('name'))
        ]);

        $user->assignRole($request->input('role'));

        if($user)
        {
            // redirect dengan pesan sukses
            return redirect()->route('admin.user.index')->with(['success' => 'Data Berhasil Disimpan']);
        }
        else
        {
            // redirect dengan pesan sukses
            return redirect()->route('admin.user.index')->with(['error' => 'Data Gagal Tersimpan']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::latest()->get();
        return view('admin.user.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
        ]);

        $user = User::findOrFail($user->id);

        if($request->input('password') == "")
        {
            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
            ]);
        }
        else
        {
            $user->update([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('name'))
            ]);
        }

        $user->syncRoles($request->input('role'));

        if($user)
        {
            // redirect dengan pesan sukses
            return redirect()->route('admin.user.index')->with(['success' => 'Data Berhasil Diupdate']);
        }
        else
        {
            // redirect dengan pesan gagal
            return redirect()->route('admin.user.index')->with(['error' => 'Data Gagal Diupdate']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        if($user)
        {
            // redirect dengan pesan sukses
            return response()->json([
                'status' => 'success'
            ]);
        }
        else
        {
            // redirect dengan pesan gagal
            return response()->json([
                'status' => 'error'
            ]);
        }
    }
}
