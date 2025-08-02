<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class QuotaUsageExport implements FromQuery, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $membershipType;

    public function __construct($startDate, $endDate, $membershipType)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->membershipType = $membershipType;
    }

    public function query()
    {
        $query = DB::table('s_members as m')
            ->join('s_member_quotas as mq', 'm.id', '=', 'mq.member_id')
            ->leftJoin('s_quota_history as qh', 'mq.id', '=', 'qh.quota_id')
            ->select(
                'm.name',
                'm.membership_type',
                DB::raw('SUM(mq.total_quota) as total_quota'),
                DB::raw('SUM(mq.remaining_quota) as remaining_quota'),
                DB::raw('SUM(mq.total_quota - mq.remaining_quota) as used_quota'),
                DB::raw('ROUND(SUM(mq.total_quota - mq.remaining_quota) / SUM(mq.total_quota) * 100, 2) as usage_rate')
            )
            ->groupBy('m.id', 'm.name', 'm.membership_type')
            ->orderBy('used_quota', 'desc');

        if ($this->startDate && $this->endDate) {
            $query->where(function($q) {
                $q->whereBetween('mq.start_date', [$this->startDate, $this->endDate])
                  ->orWhereBetween('mq.end_date', [$this->startDate, $this->endDate]);
            });
        }

        if ($this->membershipType) {
            $query->where('m.membership_type', $this->membershipType);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Nama Member',
            'Tipe Member',
            'Total Kuota',
            'Kuota Terpakai',
            'Sisa Kuota',
            'Tingkat Penggunaan (%)'
        ];
    }

    public function map($quota): array
    {
        return [
            $quota->name,
            $quota->membership_type,
            $quota->total_quota,
            $quota->used_quota,
            $quota->remaining_quota,
            $quota->usage_rate
        ];
    }
}