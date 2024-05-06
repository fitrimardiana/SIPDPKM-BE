<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Admin\ArsipDokumenModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ArsipDokumenController extends Controller
{
    public function index()
    {
        $dokumen = ArsipDokumenModel::getData();

        $response = [
            "status"    => true,
            "data"   => [
                'dokumen' => $dokumen
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function detail($id, $jenis)
    {
        if ($jenis == 'proposal') {
            $detail = ArsipDokumenModel::getDetailProposalById($id);
        } else {
            $detail = ArsipDokumenModel::getDetailLPJById($id);
        }
        if ($detail) {
            $response = [
                "status"    => true,
                "data" => [
                    'detail' => $detail,
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

    public function detailFile($id)
    {
        $file = ArsipDokumenModel::getDetailById($id);
        if (!empty($file)) {
            $response = [
                "status"    => true,
                "data" => [
                    'file' => $file,
                ]
            ];
            return response()->json($response)->setStatusCode(200);
        } else {
            $response = [
                "status"    => false,
                "message"   => 'Data tidak ditemukan.'
            ];
        }

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

        $update =  ArsipDokumenModel::updateLaporan($request->id, $params);
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

    public function delete($id)
    {
        $delete = ArsipDokumenModel::delete($id);
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
