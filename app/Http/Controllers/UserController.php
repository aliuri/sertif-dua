<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('user-index');
    }

    public function getUsersData(Request $request)
    {
        if ($request->ajax()) {
            $data = User::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-primary btn-sm editUser">Edit</a>';
                    $btn .= ' <a href="javascript:void(0)" data-id="'.$row->id.'" class="btn btn-danger btn-sm deleteUser">Hapus</a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        // Gunakan pencarian manual untuk menghindari error primary key kosong pada updateOrCreate
        $user = User::find($request->user_id) ?: new User;
        
        $user->name = $request->name;
        $user->email = $request->email;

        // Hanya update password jika diisi (penting saat edit)
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        } elseif (!$user->exists) {
            // Jika user baru tapi password kosong, berikan password default atau error
            $user->password = bcrypt('password123'); 
        }

        $user->save();

        return response()->json(['success' => 'User berhasil disimpan.']);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return response()->json($user);
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return response()->json(['success' => 'User berhasil dihapus.']);
    }
}
