<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class mhscontroller extends Controller
{
    public function index(Request $request)
    {
        $katakunci = $request->katakunci;
        $jumlahbaris = 5;
        if (strlen($katakunci)){
            $data = mahasiswa::where('nim','like', "%$katakunci%")
            ->orWhere('nama','like', "%$katakunci%")
            ->orWhere('jurusan','like', "%$katakunci%")
            ->paginate($jumlahbaris);
        }else{
            $data = mahasiswa::orderBy('nama', 'asc')->paginate($jumlahbaris);
        }
        return view('mahasiswa.index')->with('data', $data);
    }

    public function create()
    {
        return view('mahasiswa.create');
    }

    public function store(Request $request)
    {
        Session::flash('nim', $request->nim);
        Session::flash('nama', $request->nama);
        Session::flash('jurusan', $request->jurusan);
        $request->validate([
            'nim'=>'required|numeric|unique:mahasiswa,nim',
            'nama'=>'required',
            'jurusan'=>'required',
        ],[
            'nim.required'=>'NIM wajib diisi',
            'nim.numeric'=>'NIM wajib dalam angka',
            'nim.unique'=>'NIM sudah terdaftar',
            'nama.required'=>'Nama wajib diisi',
            'jurusan.required'=>'Jurusan wajib diisi',

        ]);
        $data = [
            'nim'=>$request->nim,
            'nama'=>$request->nama,
            'jurusan'=>$request->jurusan,
        ]; 
        Mahasiswa::create($data);
        return redirect()->to('mahasiswa')->with('success',"Data berhasil ditambahkan");
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $data = mahasiswa::where('nim', $id)->first();
        return view('mahasiswa.edit')->with('data', $data);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([ 
            'nama'=>'required',
            'jurusan'=>'required',
        ],[
            'nama.required'=>'Nama wajib diisi',
            'jurusan.required'=>'Jurusan wajib diisi',

        ]);
        $data = [
            'nama'=>$request->nama,
            'jurusan'=>$request->jurusan,
        ]; 
        Mahasiswa::where('nim', $id)->update($data);
        return redirect()->to('mahasiswa')->with('success',"Data berhasil diupdate");
    }

    public function destroy(string $id)
    {
        mahasiswa::where('nim',$id)->delete();
        return redirect()->to('mahasiswa')->with('success',"Data berhasil dihapus");
    }
}
