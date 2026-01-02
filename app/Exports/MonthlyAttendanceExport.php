<?php

namespace App\Exports;

use App\Models\Attendance;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MonthlyAttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    public function collection()
    {
        return Attendance::with('student')
            ->whereMonth('date', $this->month)
            ->orderBy('date', 'asc')
            ->get();
    }

    public function map($attendance): array
    {
        return [
            $attendance->student->name ?? 'N/A',
            $attendance->date,
            ucfirst($attendance->status),
        ];
    }

    public function headings(): array
    {
        return [
            'Student Name',
            'Date',
            'Status',
        ];
    }
}
