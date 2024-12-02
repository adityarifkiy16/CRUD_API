<?php

namespace App\Http\Controllers\Api;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = Mahasiswa::all();
        return response()->json([
            'code' => 200,
            'data' => $mahasiswa
        ]);
    }

    public function store(Request $request)
    {
        $request->merge(['nim' => str_replace('.', '', $request->nim)]);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nim' => 'required|string|max:13|unique:mahasiswa,nim',
            'jurusan' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'data' => [
                    'message' => $validator->errors()
                ]
            ], 400);
        }

        // get 1 char nim
        $kode_nim = substr($request->nim, 0, 1);
        $kode_jurusan = ['A', 'B', 'C', 'D', 'F', 'G'];

        // validasi kode jurusan
        if (in_array($kode_nim, $kode_jurusan)) {
            $mahasiswa = Mahasiswa::create([
                'name' => $request->name,
                'nim' => $request->nim,
                'jurusan' => $request->jurusan
            ]);

            return response()->json([
                'code' => 201,
                'message' => 'Mahasiswa created successfully',
                'data' => $mahasiswa,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Invalid kode jurusan. The first character of NIM must be one of: ' . implode(', ', $kode_jurusan),
            ], 400);
        }
    }

    public function update(Request $request, $id)
    {
        $request->merge(['nim' => str_replace('.', '', $request->nim)]);
        $mahasiswa = Mahasiswa::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nim' => ['required', 'string', 'max:13',  Rule::unique('mahasiswa', 'nim')->ignore($id)],
            'jurusan' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'data' => [
                    'message' => $validator->errors()
                ]
            ], 400);
        }

        // get 1 char nim
        $kode_nim = substr($request->nim, 0, 1);
        $kode_jurusan = ['A', 'B', 'C', 'D', 'F', 'G'];

        // validasi kode jurusan
        if (in_array($kode_nim, $kode_jurusan)) {
            $mahasiswa->update([
                'name' => $request->name,
                'nim' => $request->nim,
                'jurusan' => $request->jurusan
            ]);

            return response()->json([
                'code' => 201,
                'message' => 'Mahasiswa created successfully',
                'data' => $mahasiswa,
            ], 201);
        } else {
            return response()->json([
                'message' => 'Invalid kode jurusan. The first character of NIM must be one of: ' . implode(', ', $kode_jurusan),
            ], 400);
        }
    }

    public function destroy($id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();

        return response()->json([
            'code' => 200,
            'data' => [
                'message' => 'Data deleted successfully',
                'mahasiswa' => $mahasiswa
            ]
        ], 200);
    }
}
