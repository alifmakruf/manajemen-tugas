<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Tugas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TugasController extends Controller
{
    public function index(){
        $data = array(
            'title' => 'Data Tugas',
            'menuAdminTugas' => 'active',
            'tugas' => Tugas::with('user')->get(),
        );
        return view('admin/tugas/index', $data);
    }

    public function create(){
        $data = array(
            'title' => 'Tambah Data Tugas',
            'menuAdminTugas' => 'active',
            'user' => User::where('jabatan','Karyawan')->where('is_tugas', false)->get(),
            
        );
        return view('admin/tugas/create', $data);
    }

    public function store(Request $request){
        $request->validate([
            'user_id' => 'required',
            'tugas' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
        ],[
            'user_id.required' => 'nama tidak boleh kosong',
            'tugas.required' => 'Tugas tidak boleh kosong',
            'tanggal_mulai.required' => 'tanggal tidak boleh kosong',
            'tanggal_selesai.required' => 'tanggal tidak boleh kosong',
        ]);


        $user = User::findOrFail($request->user_id);
        $tugas = new Tugas;
        $tugas->user_id = $request->user_id;
        $tugas->tugas = $request->tugas;
        $tugas->tanggal_mulai = $request->tanggal_mulai;
        $tugas->tanggal_selesai = $request->tanggal_selesai;
        $tugas->save();


        $user->is_tugas = true;

        $user->save();

        return redirect()->route('tugas')->with('success','tugas berhasil ditambahkan');
    }
}
