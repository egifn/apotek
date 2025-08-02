<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class ClassParticipationExport implements FromQuery, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $classTypeId;

    public function __construct($startDate, $endDate, $classTypeId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->classTypeId = $classTypeId;
    }

    public function query()
    {
        $query = DB::table('s_class_schedule as cs')
            ->join('s_class_types as ct', 'cs.class_type_id', '=', 'ct.id')
            ->leftJoin('s_class_bookings as cb', 'cs.id', '=', 'cb.class_schedule_id')
            ->select(
                'ct.name as class_name',
                'cs.start_datetime',
                'cs.max_participants',
                DB::raw('COUNT(cb.id) as total_participants'),
                DB::raw('ROUND(COUNT(cb.id) / cs.max_participants * 100, 2) as participation_rate')
            )
            ->groupBy('cs.id', 'ct.name', 'cs.start_datetime', 'cs.max_participants')
            ->orderBy('cs.start_datetime', 'desc');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('cs.start_datetime', [$this->startDate, $this->endDate]);
        }

        if ($this->classTypeId) {
            $query->where('cs.class_type_id', $this->classTypeId);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Nama Kelas',
            'Tanggal & Waktu',
            'Kapasitas',
            'Jumlah Peserta',
            'Tingkat Partisipasi (%)'
        ];
    }

    public function map($class): array
    {
        return [
            $class->class_name,
            $class->start_datetime,
            $class->max_participants,
            $class->total_participants,
            $class->participation_rate
        ];
    }
}