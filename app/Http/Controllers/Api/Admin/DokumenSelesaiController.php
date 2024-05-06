<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Api\Admin\DokumenSelesaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DokumenSelesaiController extends Controller
{
    public function index()
    {
        $dokumen = DokumenSelesaiModel::getData();

        $response = [
            "status"    => true,
            "data" => [
                'dokumen' => $dokumen
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function edit($id)
    {
        $dokumen = DokumenSelesaiModel::getDataById($id);

        $response = [
            'status' => true,
            'data' => [
                'dokumen' => $dokumen
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function update(Request $request)
    {
        $param = [
            'status' => $request->status,
            'komentar_dosen' => $request->komentar_dosen,
            'modified_date' => Date('Y-m-d H:i:s'),
        ];

        $update = DokumenSelesaiModel::updateLaporan($request->id, $param);

        if ($update) {
            $response = [
                "status"    => true,
                "message"   => 'Data berhasil disimpan.',

            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data gagal disimpan.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }

    public function updateKadep(Request $request)
    {
        if ($request->status == 'Disetujui Kadep') {
            $param = [
                'status' => $request->status,
                'komentar_kadep' => $request->komentar_kadep,
                'tgl_disetujui' => Date('Y-m-d H:i:s'),
                'modified_date' => Date('Y-m-d H:i:s'),
            ];
        } else {
            $param = [
                'status' => $request->status,
                'komentar_kadep' => $request->komentar_kadep,
                'modified_date' => Date('Y-m-d H:i:s'),
            ];
        }

        $update = DokumenSelesaiModel::updateLaporan($request->id, $param);

        if ($update) {
            $response = [
                "status"    => true,
                "message"   => 'Data berhasil disimpan.',

            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data gagal disimpan.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }

    public function detail($id, $jenis)
    {
        if ($jenis == 'proposal') {
            $riwayat = DokumenSelesaiModel::getDetailProposalById($id);
        } else {
            $riwayat = DokumenSelesaiModel::getDetailLPJById($id);
        }

        $response = [
            'status' => true,
            'data' => [
                'riwayat' => $riwayat
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }
}
