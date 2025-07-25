<?php

namespace App\Http\Controllers\Coffeshop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

class SalesReportExportController extends Controller implements FromCollection, WithHeadings, WithMapping, WithTitle
{
    protected $transactions;

    public function __construct($transactions)
    {
        $this->transactions = $transactions;
    }

    public function collection()
    {
        return collect($this->transactions);
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Produk',
            'Qty',
            'Harga',
            'Subtotal'
        ];
    }

    public function map($row): array
    {
        return [
            \Carbon\Carbon::parse($row->transaction_date)->format('d M Y'),
            $row->product_name,
            $row->quantity,
            $row->price,
            $row->subtotal,
        ];
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }


    public function export(Request $request)
    {
        $filterType = $request->input('filter_type', 'daily');
        $date = $request->input('date', now()->format('Y-m-d'));

        $query = DB::table('cs_transaction_details')
            ->join('cs_transactions', 'cs_transaction_details.transaction_id', '=', 'cs_transactions.id')
            ->join('cs_products', 'cs_transaction_details.product_id', '=', 'cs_products.id')
            ->where('cs_transactions.is_deleted', 0);

        switch ($filterType) {
            case 'daily':
                $query->whereDate('cs_transactions.transaction_date', $date);
                break;
            case 'weekly':
                $startOfWeek = \Carbon\Carbon::parse($date)->startOfWeek();
                $endOfWeek = \Carbon\Carbon::parse($date)->endOfWeek();
                $query->whereBetween('cs_transactions.transaction_date', [$startOfWeek, $endOfWeek]);
                break;
            case 'monthly':
                $year = date('Y', strtotime($date));
                $month = date('m', strtotime($date));
                $query->whereYear('cs_transactions.transaction_date', $year)
                    ->whereMonth('cs_transactions.transaction_date', $month);
                break;
            case 'yearly':
                $year = date('Y', strtotime($date));
                $query->whereYear('cs_transactions.transaction_date', $year);
                break;
        }

        $transactions = $query
            ->select(
                'cs_transaction_details.*',
                'cs_products.name as product_name',
                'cs_products.selling_price',
                'cs_transactions.transaction_date'
            )
            ->get();

        return Excel::download(new SalesReportExportController($transactions), 'laporan-penjualan.xlsx');
    }
}