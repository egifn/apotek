<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;

class InstructorSessionsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $instructorId;

    public function __construct($startDate, $endDate, $instructorId)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->instructorId = $instructorId;
    }

    public function query()
    {
        $query = DB::table('s_instructors as i')
            ->join('s_class_schedule as cs', 'i.id', '=', 'cs.instructor_id')
            ->leftJoin('s_class_bookings as cb', 'cs.id', '=', 'cb.class_schedule_id')
            ->select(
                'i.name',
                DB::raw('COUNT(DISTINCT cs.id) as total_sessions'),
                DB::raw('COUNT(cb.id) as total_participants'),
                DB::raw('ROUND(COUNT(cb.id) / COUNT(DISTINCT cs.id), 2) as avg_participants')
            )
            ->groupBy('i.id', 'i.name')
            ->orderBy('total_sessions', 'desc');

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('cs.start_datetime', [$this->startDate, $this->endDate]);
        }

        if ($this->instructorId) {
            $query->where('i.id', $this->instructorId);
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Nama Instruktur',
            'Total Sesi',
            'Total Peserta',
            'Rata-rata Peserta per Sesi'
        ];
    }

    public function map($instructor): array
    {
        return [
            $instructor->name,
            $instructor->total_sessions,
            $instructor->total_participants,
            $instructor->avg_participants
        ];
    }
}