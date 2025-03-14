<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TodosExport implements FromCollection, WithHeadings, WithMapping, WithCustomStartCell, WithStyles, ShouldAutoSize
{
    protected $todos;
    protected $totalTodos;
    protected $totalTimeTracked;

    public function __construct($todos)
    {
        $this->todos = $todos;
        $this->totalTodos = $todos->count();
        $this->totalTimeTracked = $todos->sum('time_tracked');
    }

    public function collection()
    {
        return $this->todos;
    }

    public function headings(): array
    {
        return [
            'Title',
            'Assignee',
            'Due Date',
            'Time Tracked',
            'Status',
            'Priority'
        ];
    }

    public function map($todo): array
    {
        return [
            $todo->title,
            $todo->assignee ?? 'Unassigned',
            $todo->due_date->format('Y-m-d'),
            $todo->time_tracked,
            $todo->status,
            $todo->priority
        ];
    }

    public function startCell(): string
    {
        return 'A1';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'D3D3D3',
                ],
            ],
        ]);

        $lastRow = $this->totalTodos + 2; 
        $sheet->setCellValue('A' . ($lastRow + 1), 'Summary:');
        $sheet->setCellValue('A' . ($lastRow + 2), 'Total Todos:');
        $sheet->setCellValue('B' . ($lastRow + 2), $this->totalTodos);
        $sheet->setCellValue('A' . ($lastRow + 3), 'Total Time Tracked:');
        $sheet->setCellValue('B' . ($lastRow + 3), $this->totalTimeTracked);

        $sheet->getStyle('A' . ($lastRow + 1) . ':B' . ($lastRow + 3))->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => [
                    'rgb' => 'F0F0F0',
                ],
            ],
        ]);

        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}