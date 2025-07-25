<?php

namespace App\Http\Controllers\Apotek\Master_Data;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use App\Supplier;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierController extends Controller
{
    public function index()
    {
        return view('apotek.master_data.supplier.index');
    }

    public function getDataSupplier(Request $request)
    {
        $data_supplier = DB::table('m_supplier')
            ->select(
                'm_supplier.id',
                'm_supplier.kode_supplier',
                'm_supplier.nama_supplier',
                'm_supplier.alamat',
                'm_supplier.cp',
                'm_supplier.tlp',
                'm_supplier.email',
                'm_supplier.id_user_input',
                'users.name'
            )
            ->join('users', 'm_supplier.id_user_input', '=', 'users.id');

        if (!isset($request->value)) {
        } else {
            $data_supplier->where('m_supplier.nama_supplier', 'like', "%$request->value%");
        }

        $data  = $data_supplier->get();
        $count = ($data_supplier->count() == 0) ? 0 : $data->count();
        $output = [
            'status'  => true,
            'message' => 'success',
            'count'   => $count,
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function store(Request $request)
    {
        $getRow = Supplier::orderBy('kode_supplier', 'ASC')->get();
        $rowCount = $getRow->count();

        if ($rowCount > 0) {
            if ($rowCount < 9) {
                $kode = "SPL-" . '' . "0000" . '' . ($rowCount + 1);
            } else if ($rowCount < 99) {
                $kode = "SPL-" . '' . "000" . '' . ($rowCount + 1);
            } else if ($rowCount < 999) {
                $kode = "SPL-" . '' . "00" . '' . ($rowCount + 1);
            } else if ($rowCount < 9999) {
                $kode = "SPL-" . '' . "0" . '' . ($rowCount + 1);
            } else if ($rowCount < 99999) {
                $kode = "SPL-" . '' . '' . ($rowCount + 1);
            }
        } else {
            $kode = "SPL-" . '' . "00001";
        }

        DB::table('m_supplier')->insert([
            'kode_supplier' => $kode,
            'nama_supplier' => $request->nama_supplier,
            'alamat' => $request->alamat,
            'cp' => $request->cp,
            'tlp' => $request->tlp,
            'email' => $request->email,
            'status' => 1,
            'id_user_input' => Auth::user()->id
        ]);

        $output = [
            'msg'  => 'Data Supplier Berhasil Ditambah',
            'res'  => true,
            'type' => 'success'
        ];
        return response()->json($output, 200);
    }

    public function getDataSupplierDetail(Request $request)
    {
        $data = DB::table('m_supplier')
            ->where('m_supplier.kode_supplier', $request->kode_supplier)
            ->first();

        $output = [
            'status'  => true,
            'message' => 'success',
            'data'    => $data
        ];

        return response()->json($output, 200);
    }

    public function update(Request $request)
    {
        DB::table('m_supplier')->where('kode_supplier', $request->kode_supplier)->update([
            'nama_supplier' =>  $request->nama_supplier,
            'alamat' => $request->alamat,
            'cp' => $request->cp,
            'tlp' => $request->tlp,
            'email' => $request->email,
        ]);

        $output = [
            'message'  => 'Data Supplier Berhasil Diubah',
            'status'  => true,
        ];
        return response()->json($output, 200);
    }

    public function view(Request $request)
    {
        $cari_supplier = $request->input('cari_supplier');
        $tombol_excel = $request->input('button_excel');
        $tombol_pdf = $request->input('button_pdf');

        if ($tombol_excel == 'excel') {
            if ($cari_supplier != '') {
                $data_supplier_excel = DB::table('m_supplier')
                    ->where('m_supplier.nama_supplier', 'like', "%$cari_supplier%")
                    ->get();
            } else {
                $data_supplier_excel =  DB::table('m_supplier')
                    ->get();
            }
            return view('apotek.master_data.supplier.view', compact('data_supplier_excel'));
        } elseif ($tombol_pdf == 'pdf') {
            if ($cari_supplier != '') {
                $data_supplier_pdf =  DB::table('m_supplier')
                    ->where('m_supplier.nama_supplier', 'like', "%$cari_supplier%")
                    ->get();
            } else {
                $data_supplier_pdf = DB::table('m_supplier')
                    ->get();
            }
            $pdf = PDF::loadview('master_data.supplier.pdf', compact('data_supplier_pdf'))->setPaper('a4', 'landscape');
            return $pdf->stream();
        }
    }
}
