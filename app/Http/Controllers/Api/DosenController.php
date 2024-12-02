<?php

namespace App\Http\Controllers\Api;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class DosenController extends Controller
{
    public function index()
    {
        $dosen = Dosen::all();
        return response()->json([
            'code' => 200,
            'data' => $dosen
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nidn' => 'required|string|max:13|unique:dosen,nidn',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'data' => [
                    'message' => $validator->errors()
                ]
            ], 400);
        }

        $dosen = Dosen::create([
            'name' => $request->name,
            'nidn' => $request->nidn,
        ]);

        return response()->json([
            'code' => 201,
            'message' => 'Dosen created successfully',
            'data' => $dosen,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $dosen = Dosen::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nidn' => ['required', 'string', 'max:255',  Rule::unique('dosen', 'nidn')->ignore($id)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'data' => [
                    'message' => $validator->errors()
                ]
            ], 400);
        }

        $dosen->update([
            'name' => $request->name,
            'nidn' => $request->nidn,
        ]);

        return response()->json([
            'code' => 201,
            'message' => 'Dosen updated successfully',
            'data' => $dosen,
        ], 201);
    }

    public function destroy($id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();

        return response()->json([
            'code' => 200,
            'data' => [
                'message' => 'Data deleted successfully',
                'dosen' => $dosen
            ]
        ], 200);
    }
}
