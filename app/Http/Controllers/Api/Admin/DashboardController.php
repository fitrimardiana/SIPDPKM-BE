<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Api\Admin\DashboardModel;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use Illuminate\Support\Facades\Auth;
use App\Models\Api\Admin\RiwayatModel;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function getAllData()
    {
        $user_type = Auth::user()->type;
        if ($user_type == 'mahasiswa') {
            $jml_proposal = DashboardModel::countProposalMhs();
            $jml_lpj = DashboardModel::countlpjMhs();
            $jml_dokumen_acc = DashboardModel::countLaporanAcc();
            $jml_revisi = DashboardModel::countRevisi();
            $all_data = DashboardModel::getAllDataMhs();
            $data_dashboard = DashboardModel::getDashboardMhs();
            $data = [
                "jml_proposal" => $jml_proposal,
                "jml_lpj" => $jml_lpj,
                "jml_dokumen_acc" => $jml_dokumen_acc,
                "jml_revisi" => $jml_revisi,
                "all_data" => $all_data,
                "data_dashboard" => $data_dashboard,
            ];
        } elseif ($user_type == 'admin') {
            $jml_proposal = DashboardModel::countProposal();
            $jml_lpj = DashboardModel::countlpj();
            $jml_kegiatan = DashboardModel::countkegiatan();
            $jml_user = DashboardModel::countuser();
            $all_data = DashboardModel::getAllData();
            $data = [
                "jml_proposal" => $jml_proposal,
                "jml_lpj" => $jml_lpj,
                "jml_kegiatan" => $jml_kegiatan,
                "jml_user" => $jml_user,
                "all_data" => $all_data,
            ];
        } elseif ($user_type == 'dosen') {
            $jml_proposal = DashboardModel::countProposalDosen();
            $jml_lpj = DashboardModel::countLPJDosen();
            $jml_dokumen_acc = DashboardModel::countLaporanAccDosen();
            $jml_dokumen_acc_kadep = DashboardModel::countLaporanAccKadep();
            $jml_revisi = DashboardModel::countRevisiDosen();
            $all_data = DashboardModel::getAllDataDosen();
            $data_dashboard = DashboardModel::getDashboardDosen();
            $data = [
                "jml_proposal" => $jml_proposal,
                "jml_lpj" => $jml_lpj,
                "jml_dokumen_acc" => $jml_dokumen_acc,
                "jml_dokumen_acc_kadep" => $jml_dokumen_acc_kadep,
                "jml_revisi" => $jml_revisi,
                "all_data" => $all_data,
                "data_dashboard" => $data_dashboard,
            ];
        } elseif ($user_type == 'kadep') {
            $jml_proposal = DashboardModel::countProposalKadep();
            $jml_lpj = DashboardModel::countLPJKadep();
            $jml_dokumen_acc = DashboardModel::countLaporanAccDosen();
            $jml_dokumen_acc_kadep = DashboardModel::countLaporanAccKadep();
            $jml_revisi = DashboardModel::countRevisiDosen();
            $all_data = DashboardModel::getAllDataKadep();
            $data_dashboard = DashboardModel::getDashboardKadep();
            $data = [
                "jml_proposal" => $jml_proposal,
                "jml_lpj" => $jml_lpj,
                "jml_dokumen_acc" => $jml_dokumen_acc,
                "jml_dokumen_acc_kadep" => $jml_dokumen_acc_kadep,
                "jml_revisi" => $jml_revisi,
                "all_data" => $all_data,
                "data_dashboard" => $data_dashboard,
            ];
        }


        $response = [
            "status"    => true,
            "message"   => 'Ok',
            "data" => $data,
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function detail($id, $jenis)
    {
        if ($jenis == 'proposal') {
            $riwayat = DashboardModel::getDetailProposalById($id);
        } else {
            $riwayat = DashboardModel::getDetailLPJById($id);
        }

        $response = [
            'status' => true,
            'data' => [
                'riwayat' => $riwayat
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }


    public function detailDokumen($id)
    {
        $riwayat = RiwayatModel::getDataById($id);

        $response = [
            'status' => true,
            'data' => [
                'riwayat' => $riwayat
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function update(Request $request)
    {
        if ($request->hasFile('file')) {

            $file                       = $request->file('file');

            if ($request->jenis == 'proposal') {
                $file_name        = Str::slug("proposal", '-') . '-' . uniqid() . '.' . 'pdf';
                // upload path
                $upload_path      = '/file/proposal/';
            } else {
                $file_name        = Str::slug("lpj", '-') . '-' . uniqid() . '.' . 'pdf';
                // upload path
                $upload_path      = '/file/lpj/';
            }

            // cek folder                    
            if (!is_dir(public_path($upload_path))) {
                // buat folder jika belum ada                        
                mkdir(public_path($upload_path), 0755, true);
            }

            $new_file_name = $upload_path . $file_name;
            // upload process
            if (!$file->move(public_path($upload_path), $file_name)) {
                // flash message
                $response = [
                    "status"    => true,
                    "message"   => 'File gagal di upload.'
                ];
                return response()->json($response)->setStatusCode(200);
            }
        }
        $params = [
            'user_id' => Auth::user()->id,
            'nama_file'         => isset($new_file_name) ? $new_file_name : NULL,
            'modified_date'  => date('Y-m-d H:i:s'),
        ];

        $update =  RiwayatModel::updateLaporan($request->id, $params);
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

    public function dosenApproval($id, Request $request)
    {
        $param = [
            'status' => $request->status,
            'komentar_dosen' => $request->komentar_dosen,
            'modified_date' => Date('Y-m-d H:i:s'),
        ];

        $update = DashboardModel::updateLaporan($id, $param);

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

    public function kadepApproval($id, Request $request)
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

        $update = DashboardModel::updateLaporan($id, $param);

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
}
