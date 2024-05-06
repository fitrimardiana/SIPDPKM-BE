<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Admin\AgendaModel;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index()
    {
        $user_type = Auth::user()->type;
        if ($user_type == 'mahasiswa') {
            $agenda = AgendaModel::getAgendaMahasiswa();
        } else {
            $agenda = AgendaModel::getAllAgenda();
        }

        $response = [
            "status"    => true,
            "message"   => 'Ok',
            "data"      => [
                "agenda" => $agenda
            ]
        ];

        return response()->json($response)->setStatusCode(200);
    }

    public function detail($id)
    {
        $agenda = AgendaModel::getDetailById($id);
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
    public function detailProposal($id)
    {
        $proposal = AgendaModel::getDetailProposalById($id);
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
        $lpj = AgendaModel::getDetailLPJById($id);
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
}
