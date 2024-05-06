<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Admin\PengunjungModel;
use App\Models\Api\Admin\ArsipDokumenModel;

class PengunjungController extends Controller
{
    public function dashboard()
    {
        $rs_dashboard = PengunjungModel::getAllData();

        $response = [
            "status"    => true,
            "data"   => [
                'rs_dashboard' => $rs_dashboard
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function proposal()
    {
        $proposal = PengunjungModel::getDataProposal();

        $response = [
            "status"    => true,
            "data"   => [
                'proposal' => $proposal
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function lpj()
    {
        $lpj = PengunjungModel::getDataLPJ();

        $response = [
            "status"    => true,
            "data"   => [
                'lpj' => $lpj
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function detailDashboard($id, $jenis)
    {
        if ($jenis == 'proposal') {
            $detail = PengunjungModel::getDetailProposalById($id);
        } else {
            $detail = PengunjungModel::getDetailLPJById($id);
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

    public function detailFileDashboard($id)
    {
        $file = PengunjungModel::getDetailById($id);
        if ($file) {
            $response = [
                "status"    => true,
                "data" => [
                    'file' => $file,
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

    public function agenda()
    {
        $agenda = PengunjungModel::getAllAgenda();


        $response = [
            "status"    => true,
            "message"   => 'Ok',
            "data"      => [
                "agenda" => $agenda
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function detailAgenda($id)
    {
        $agenda = PengunjungModel::getDetailAgendaById($id);
        if ($agenda) {
            $response = [
                "status"    => true,
                "data" => [
                    'agenda' => $agenda,
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
        $proposal = PengunjungModel::getDetailFileById($id);
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

    public function allJadwal()
    {
        $agenda = PengunjungModel::getDataJadwal();


        $response = [
            "status"    => true,
            "message"   => 'Ok',
            "data"      => [
                "agenda" => $agenda
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function jadwal()
    {
        $agenda = PengunjungModel::getAllJadwal();


        $response = [
            "status"    => true,
            "message"   => 'Ok',
            "data"      => [
                "agenda" => $agenda
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function detailJadwal($id)
    {
        $agenda = PengunjungModel::getDetailAgendaById($id);
        if ($agenda) {
            $response = [
                "status"    => true,
                "data" => [
                    'agenda' => $agenda,
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

    public function arsip()
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

    public function detailArsip($id, $jenis)
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

    public function detailFileArsip($id)
    {
        $file = ArsipDokumenModel::getDetailById($id);
        if ($file) {
            $response = [
                "status"    => true,
                "data" => [
                    'file' => $file,
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
}
