<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DevisExport;

class ExportController extends Controller
{
    public function export(Request $request)
    {
        return Excel::download(new DevisExport($request["id"]), 'devis.xlsx');
    }
}
