<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Epresence;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class EpresenceController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'type' => 'required',
                'waktu'  => 'required'
            ]);

            if($validator->fails()){
                return response()->json($validator->errors(), 400);
            }

            // dd($request->user()->id);

            $absen = Epresence::create([
                'id_users' => $request->user()->id,
                'type'  => $request->type,
                'waktu' => $request->waktu
            ]);

            return response()->json([
                'meta' => [
                    'status' => 'Sukses',
                    'message' => 'Berhasil menambahkan data'
                ],
                'data' => $absen,
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'meta' => [
                    'status' => 'Failed',
                    'message' => 'Something went wrong',
                    'error' => $error
                ],
                'data' => '',
            ], 500);
        }
    }

    public function approve(Request $request, $id)
    {

        // dd($request->approve);
        $approve = '';
        if ($request->approve == 'APPROVE') {
            $approve = 'TRUE';
        } else {
            $approve = 'FALSE';
        }

        // dd($approve);

        $absen = Epresence::find($id);
        $absen->update([
            'is_approve' => $approve
        ]);

        // ddd($absen);
    }

    public function getDataAbsen()
    {
        
        $absen = Epresence::all();

        $absensi = $absen->groupBy(function($val) {
            return date('Y-m-d', strtotime($val->waktu)); 
        });
        // ddd($absen);
        // return $absen;

        return response()->json([
            'meta' => [
                'status' => 'Sukses',
                'message' => 'Berhasil Get Data'
            ],
            'data' => $absensi,
        ], 200);
    }
}
