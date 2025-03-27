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
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LecturerInvoiceExport implements WithStyles, WithEvents, WithColumnWidths, FromArray, WithDrawings
{
    protected $invoice;
    protected $grouped_student_attendances_by_month;
    protected $data_count;
    protected $total;
    protected $month_separation_pos = [];
    protected $fill_gray_pos = [];

    public function __construct($invoice, $grouped_student_attendances_by_month){
        $this->invoice = $invoice;
        $this->grouped_student_attendances_by_month = $grouped_student_attendances_by_month;

        $count = 0;
        foreach($grouped_student_attendances_by_month as $grouped_by_month){
            $count++;
            foreach($grouped_by_month as $grouped_by_student){
                foreach($grouped_by_student as $sa){
                    $count++;

                    $start_time = Carbon::parse($sa->start_time);
                    $end_time = Carbon::parse($sa->end_time);
                    $working_hours = number_format($start_time->diffInMinutes($end_time) / 60, 1);
                    $subtotal = $working_hours * $this->invoice->rate;

                    $this->total += $subtotal;
                }
            }
        }

        $this->data_count = $count;
    }

    /**
     * Retrieve product data and return as an array.
     */
    public function array() : array
    {
        $rows = [];

        $rows[] = [
            ['', '', '', '', '', '', ''],
            ['', 'Invoice ' . $this->invoice->number . ' (' . Auth::user()->full_name . ')'],
            ['', 'Date: ' . Carbon::today()->format('d - F - Y')],
            ['', 'Bill To', '', 'From'],
            ['', 'Finance', '', Auth::user()->full_name],
            ['', 'PT. Sangnila Interaktif Media dan Teknologi'],
            ['', 'Phone: 0856-9325-7411'],
            ['', 'Description', 'Task', 'Time Range', 'Working Hours', 'Rate', 'Cost'],
        ];

        $pos = 8;

        foreach($this->grouped_student_attendances_by_month as $period => $grouped_by_month){
            $pos++;

            if(($pos) % 2 == 0){
                $this->fill_gray_pos[] = $pos;
            }

            $rows[] = ['', $period, '', '', '', '', ''];
            
            $this->month_separation_pos[] = $pos;

            foreach($grouped_by_month as $grouped_by_student){
                foreach($grouped_by_student as $sa){
                    $pos++;
                    
                    if(($pos) % 2 == 0){
                        $this->fill_gray_pos[] = $pos;
                    }

                    $start_time = Carbon::parse($sa->start_time);
                    $end_time = Carbon::parse($sa->end_time);
                    $working_hours = number_format($start_time->diffInMinutes($end_time) / 60, 1);
                    $subtotal = $working_hours * $this->invoice->rate;

                    $rows[] = [
                        '',
                        $this->invoice->course->course_name . " " . $sa->student->full_name . 
                            " (" . Carbon::parse($sa->attendance->attendance_date)->format('l d M') . ")",
                        'Lecturer',
                        $start_time->format('H:i') . ' - ' . $end_time->format('H:i'),
                        $working_hours === 0? '0' : $working_hours,
                        $this->invoice->rate === 0? '0' : $this->invoice->rate,
                        $subtotal === 0? '0' : $subtotal,
                    ];
                }
            }
        }

        $rows[] = [
            ['', '', '', '', '', '', ''],
            ['', '', '', '', '', 'Subtotal   ', $this->total],
            ['', '', '', '', '', 'Tax Rate   ', '0'],
            ['', 'Please transfer to bank account below:', '', '', 'Total Cost   ', '', $this->total],
            ['', $this->invoice->bank_data],
            ['', 'Thank you for your business!'],
        ];

        return $rows;
    }

    public function columnWidths(): array
    {
        return [
            'A' => 2,
            'B' => 52.56,
            'C' => 27.11,
            'D' => 17.11,
            'E' => 17.44,
            'F' => 17.11,
            'G' => 22.56,
        ];
    }

    /**
     * Apply styles to the Excel sheet.
     */
    public function styles(Worksheet $sheet)
    {
        return [
            2 => [
                'font' => [
                    'size' => 17,
                    'name' => 'Constantia',
                    'color' => ['rgb' => '1F4E78']
                ]
            ],
            4 => [
                'font' => [
                    'size' => 17,
                    'name' => 'Constantia',
                    'color' => ['rgb' => '1F4E78']
                ]
            ],
        ];
    }

    /**
     * Auto-size columns and set text alignment for body cells.
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;

                $sheet->getParent()->getDefaultStyle()->applyFromArray([
                    'font' => [
                        'size' => 9,
                        'name' => 'Franklin Gothic Book'
                    ]
                ]);
    
                $sheet->getDelegate()->getRowDimension(1)->setRowHeight(99);
                $sheet->getDelegate()->getRowDimension(2)->setRowHeight(29);
                $sheet->getDelegate()->getRowDimension(4)->setRowHeight(48);
                $sheet->getDelegate()->getRowDimension(7)->setRowHeight(43);
                $sheet->getDelegate()->getRowDimension(8)->setRowHeight(21);

                for($i = 0; $i < $this->data_count; $i++){
                    $sheet->getDelegate()->getRowDimension(9 + $i)->setRowHeight(21);
                }

                $sheet->getStyle('B8:G8')->applyFromArray([
                    'font' => [
                        'bold' => true, 
                        'size' => 11, 
                        'name' => 'Constantia',
                        'color' => ['rgb' => 'FFFFFF']
                    ], 
                    'fill' => [
                        'fillType' => 'solid', 
                        'color' => ['rgb' => '1F4E78']
                    ], 
                ]);

                $sheet->mergeCells('D5:D7');

                $sheet->getStyle('D5')->applyFromArray([
                    'alignment' => [
                        'wrapText' => true,
                        'vertical' => Alignment::VERTICAL_TOP,
                        'horizontal' => Alignment::HORIZONTAL_LEFT
                    ]
                ]);

                $sheet->getStyle('B8:G8')->applyFromArray([
                    'alignment' => [
                        'wrapText' => true,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ]
                ]);

                $sheet->getStyle('B9:B' . ($this->data_count + 8))->applyFromArray([
                    'alignment' => [
                        'wrapText' => true,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ]
                ]);

                $sheet->getStyle('C9:G' . ($this->data_count + 8))->applyFromArray([
                    'alignment' => [
                        'wrapText' => true,
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ]
                ]);

                $sheet->getStyle('B9:F' . ($this->data_count + 8))->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => 'FF0000']
                    ], 
                ]);

                $sheet->getStyle('B9:E' . ($this->data_count + 8))->applyFromArray([
                    'font' => [
                        'italic' => true
                    ], 
                ]);

                $sheet->getStyle('F9:G' . ($this->data_count + 8))->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)');
                $sheet->getStyle('E9:E' . ($this->data_count + 8))->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);
                $sheet->getStyle('F9:G' . ($this->data_count + 8))->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    ],
                ]);

                $sheet->getStyle('B9:G' . ($this->data_count + 7))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'b8bcd4'],
                        ],
                    ],
                ]);

                $sheet->getStyle('C8:G8')->applyFromArray([
                    'borders' => [
                        'inside' => [ // Left border only
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'FFFFFF'],
                        ],
                    ],
                ]);

                foreach (range('C', 'F') as $col) {
                    $cell = $col . ($this->data_count + 8); // Example: B10, C10, D10, etc.
                    $sheet->getStyle($cell)->applyFromArray([
                        'borders' => [
                            'top' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'b8bcd4'],
                            ],
                            'left' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'b8bcd4'],
                            ],
                            'right' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => 'b8bcd4'],
                            ],
                            'bottom' => [
                                'borderStyle' => Border::BORDER_THICK,
                                'color' => ['rgb' => '1F4E78'],
                            ],
                        ],
                    ]);
                }

                $sheet->getStyle('B8:B' . $this->data_count + 8)->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'b8bcd4'],
                        ],
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '1F4E78'],
                        ],
                        'right' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'b8bcd4'],
                        ],
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'b8bcd4'],
                        ],
                    ],
                ]);

                $sheet->getStyle('G8:G' . $this->data_count + 8)->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'b8bcd4'],
                        ],
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'b8bcd4'],
                        ],
                        'right' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '1F4E78'],
                        ],
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'b8bcd4'],
                        ],
                    ],
                ]);

                $sheet->getStyle('B' . $this->data_count + 8)->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'b8bcd4'],
                        ],
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '1F4E78'],
                        ],
                        'right' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'b8bcd4'],
                        ],
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '1F4E78'],
                        ],
                    ],
                ]);

                $sheet->getStyle('G' . $this->data_count + 8)->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'b8bcd4'],
                        ],
                        'left' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'b8bcd4'],
                        ],
                        'right' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '1F4E78'],
                        ],
                        'bottom' => [
                            'borderStyle' => Border::BORDER_THICK,
                            'color' => ['rgb' => '1F4E78'],
                        ],
                    ],
                ]);

                foreach($this->month_separation_pos as $p){
                    $sheet->getStyle('B' . $p)->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                        ]
                    ]);
                }

                foreach($this->fill_gray_pos as $g){
                    $sheet->getStyle('B' . $g . ':G' . $g)->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'F2F2F2'],
                        ]
                    ]);
                }

                $row_after_table = $this->data_count + 10;

                $sheet->getStyle('G' . $row_after_table . ':G' . ($row_after_table + 2))->getNumberFormat()->setFormatCode('_("Rp"* #,##0.00_);_("Rp"* \(#,##0.00\);_("Rp"* "-"??_);_(@_)');

                $sheet->mergeCells('E' . ($row_after_table + 2) . ':F' . ($row_after_table + 2));
                $sheet->getStyle('E' . ($row_after_table + 2))->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'font' => [
                        'size' => 16,
                        'color' => ['rgb' => '1F4E78'],
                    ]
                ]);

                $sheet->getStyle('F' . ($row_after_table) . ':F' . ($row_after_table + 2))->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ]
                ]);

                $sheet->getStyle('G' . $row_after_table . ':G' . ($row_after_table + 2))->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '1F4E78'],
                        ]
                    ],
                ]);

                $sheet->getStyle('G' . ($row_after_table + 2))->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '1F4E78'],
                    ],
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF']
                    ]
                ]);

                for($i = 0; $i < 6; $i++){
                    $sheet->getDelegate()->getRowDimension($i + $row_after_table - 1)->setRowHeight(21);
                }

                $sheet->getStyle('B' . ($row_after_table + 2) . ':B' . ($row_after_table + 4))->applyFromArray([
                    'font' => [
                        'size' => 10,
                    ]
                ]);

                $sheet->getStyle('B' . ($row_after_table + 3))->applyFromArray([
                    'font' => [
                        'italic' => true,
                    ]
                ]);
            },
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Header Image');
        $drawing->setDescription('Company Header');
        $drawing->setPath(public_path('img/invoice-header.png')); // Replace with actual image path
        $drawing->setHeight(131);
        $drawing->setCoordinates('B1'); // Set image at the top

        return [$drawing];
    }
}