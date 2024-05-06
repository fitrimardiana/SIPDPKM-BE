<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Api\Admin\JadwalModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwal = JadwalModel::getData();

        $response = [
            "status"    => true,
            "message"   => 'Ok',
            "data"      => [
                "jadwal" => $jadwal
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function add()
    {
        $proposal = JadwalModel::getProposal();
        $lpj = JadwalModel::getLpj();

        $response = [
            "status"    => true,
            "message"   => 'Ok',
            "data"      => [
                "proposal" => $proposal,
                "lpj" => $lpj
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'     => 'required',
            'tempat'     => 'required',
            'bidang'   => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai'   => 'required',
            'tgl_mulai'   => 'required',
            'tgl_selesai'   => 'required',
            'proposal_id'   => 'required',
            'lpj_id'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $param = [
            'nama'      => $request->nama,
            'bidang'      => $request->bidang,
            'tempat'      => $request->tempat,
            'jam_mulai'      => $request->jam_mulai,
            'jam_selesai'      => $request->jam_selesai,
            'tgl_mulai'      => $request->tgl_mulai,
            'tgl_selesai'      => $request->tgl_selesai,
            'proposal_id'      => $request->proposal_id,
            'lpj_id'      => $request->lpj_id,
            'tujuan'      => $request->tujuan,
            'jadwal' => 'yes',
            'created_by' => Auth::user()->type,
            'created_date'  => date('Y-m-d H:i:s'),
        ];
        $lpj_id = JadwalModel::insert($param);

        if ($lpj_id) {
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

    public function edit($id)
    {
        $jadwal = JadwalModel::getDataById($id);
        $proposal = JadwalModel::getProposal();
        $lpj = JadwalModel::getLpj();
        if ($jadwal) {
            $response = [
                "status"    => true,
                "data" => [
                    'jadwal' => $jadwal,
                    'proposal' => $proposal,
                    'lpj' => $lpj,
                ]
            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data tidak ditemukan.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama'     => 'required',
            'tempat'     => 'required',
            'bidang'   => 'required',
            'jam_mulai'   => 'required',
            'jam_selesai'   => 'required',
            'tgl_mulai'   => 'required',
            'tgl_selesai'   => 'required',
            // 'lpj_id'   => 'required',
            // 'proposal_id'   => 'required',
        ]);

        //check if validation fails
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $param = [
            'nama'      => $request->nama,
            'bidang'      => $request->bidang,
            'tempat'      => $request->tempat,
            'jam_mulai'      => $request->jam_mulai,
            'jam_selesai'      => $request->jam_selesai,
            'tgl_mulai'      => $request->tgl_mulai,
            'tgl_selesai'      => $request->tgl_selesai,
            // 'proposal_id'      => $request->proposal_id,
            // 'lpj_id'      => $request->lpj_id,
            'tujuan'      => $request->tujuan,
            'jadwal' => 'yes',
            'modified_date'  => date('Y-m-d H:i:s'),
        ];
        $update = JadwalModel::update($request->id, $param);

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

    public function detail($id)
    {
        $jadwal = JadwalModel::getDetailById($id);
        if ($jadwal) {
            $response = [
                "status"    => true,
                "data" => [
                    'jadwal' => $jadwal,
                ]
            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data tidak ditemukan.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }
    public function detailProposal($id)
    {
        $proposal = JadwalModel::getDetailProposalById($id);
        if ($proposal) {
            $response = [
                "status"    => true,
                "data" => [
                    'proposal' => $proposal,
                ]
            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data tidak ditemukan.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }
    public function detailLPJ($id)
    {
        $lpj = JadwalModel::getDetailLPJById($id);
        if ($lpj) {
            $response = [
                "status"    => true,
                "data" => [
                    'lpj' => $lpj,
                ]
            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data tidak ditemukan.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }

    public function delete($id)
    {
        $delete = JadwalModel::delete($id);
        if ($delete) {
            $response = [
                "status"    => true,
                "message"   => 'Data berhasil dihapus.',
            ];
        } else {
            $response = [
                "status"    => true,
                "message"   => 'Data gagal dihapus.'
            ];
        }

        return response()->json($response)->setStatusCode(200);
    }
}
