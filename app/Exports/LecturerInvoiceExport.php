<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Carbon\Carbon;

class LecturerInvoiceExport implements WithStyles, WithEvents, WithColumnWidths, WithHeadings, FromArray
{
    protected $invoice;
    protected $grouped_student_attendances;

    public function __construct($invoice, $grouped_student_attendances){
        $this->invoice = $invoice;
        $this->grouped_student_attendances = $grouped_student_attendances;
    }

    /**
     * Retrieve product data and return as an array.
     */
    public function array() : array
    {
        $rows = [];

        foreach ($this->grouped_student_attendances as $student_id => $gsa) {
            foreach ($gsa as $sa) {
                $start_time = Carbon::parse($sa->start_time);
                $end_time = Carbon::parse($sa->end_time);
                $working_hours = $start_time->diffInHours($end_time);

                $rows[] = [
                    'course' => $this->invoice->course->course_name . " " . $sa->student->full_name . 
                        " (" . Carbon::parse($sa->attendance->attendance_date)->format('l d M') . ")",
                    'role' => 'Lecturer',
                    'time' => $start_time->format('H:i') . ' - ' . $end_time->format('H:i'),
                    'hours' => $working_hours,
                    'rate' => 'Rp ' . number_format($this->invoice->rate, 2),
                    'total' => 'Rp ' . number_format($working_hours * $this->invoice->rate, 2),
                ];
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Course', 'Role', 'Time', 'Hours', 'Rate', 'Total'];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 1,
            'B' => 53.56,
            'C' => 24.11,
            'D' => 13.11,
            'E' => 13.44,
            'F' => 13.11,
            'G' => 18.56,
        ];
    }

    /**
     * Apply styles to the Excel sheet.
     */
    public function styles(Worksheet $sheet)
    {

    }

    /**
     * Auto-size columns and set text alignment for body cells.
     */
    public function registerEvents(): array
    {
        return [];
    }
}