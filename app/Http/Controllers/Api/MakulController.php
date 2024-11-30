<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Makul;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class MakulController extends Controller
{
    public function index()
    {
        $makul = Makul::all();
        return response()->json([
            'code' => 200,
            'data' => $makul
        ]);
    }

    public function store(Request $request)
    {
        dd($request->all());
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'jadwal' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'data' => [
                    'message' => $validator->errors()
                ]
            ], 400);
        }

        $makul = Makul::create([
            'name' => $request->name,
            'nim' => $request->email,
            'jurusan' => $request->jurusan
        ]);

        return response()->json([
            'code' => 200,
            'data' => [
                'message' => 'Berhasil menambah data makul',
                'makul' => $makul,
            ]
        ]);
    }

    public function update($id, Request $request)
    {
        $makul = Makul::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'nim' => 'nullable|string|max:10',
            'jurusan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'data' => [
                    'message' => $validator->errors()
                ]
            ], 400);
        }

        $makul->update([
            'name' => $request->name,
            'nim' => $request->nim,
            'jurusan' => $request->jurusan
        ]);

        return response()->json([
            'code' => 200,
            'data' => [
                'message' => 'Data updated successfully',
                'makul' => $makul
            ]
        ], 200);
    }

    public function destroy($id)
    {
        $makul = Makul::findOrFail($id);
        $makul->delete();

        return response()->json([
            'code' => 200,
            'data' => [
                'message' => 'Data updated successfully',
                'makul' => $makul
            ]
        ], 200);
    }
}
