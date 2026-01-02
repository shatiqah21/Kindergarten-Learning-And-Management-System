<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\Section;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $section_id, $date;

    public function __construct($section_id, $date)
    {
        $this->section_id = $section_id;
        $this->date = $date;
    }

    public function collection()
    {
        return Attendance::with(['student.user', 'section'])
            ->where('section_id', $this->section_id)
            ->whereDate('date', $this->date)
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Student Name',
            'Section',
            'Status',
            'Date',
            'Marked By'
        ];
    }

    public function map($attendance): array
    {
        static $i = 1;
        return [
            $i++,
            $attendance->student->user->name ?? '-',
            $attendance->section->name ?? '-',
            ucfirst($attendance->status),
            $attendance->date,
            $attendance->marked_by ?? '-'
        ];
    }
}
