<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Events\AfterSheet;

class SolicitudesExcelExport implements FromCollection, WithHeadings, WithStyles, WithDrawings, WithEvents, WithCustomStartCell
{
    protected $datos;

    public function __construct(array $datos)
    {
        $this->datos = $datos;
    }

    public function collection()
    {
        return new Collection($this->datos);
    }

    public function headings(): array
    {
        return [
            'Cuenta',
            'Nombres',
            'Email',
            'Teléfono',
            'Fecha Ingreso',
            'Estado',
            'Última Modificación',
        ];
    }

    public function startCell(): string
    {
        return 'A7'; // Empieza a escribir encabezados y datos desde la fila 5
    }

    public function styles(Worksheet $sheet)
    {
        return [
            7 => [ // fila 1 (cabeceras)
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => 'solid',
                    'startColor' => ['rgb' => '4F81BD'], // azul
                ],
                'alignment' => [
                    'horizontal' => 'center',
                    'vertical' => 'center',
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {

                $sheet = $event->sheet->getDelegate();

                // Título personalizado en la parte superior
                $sheet->setCellValue('A4', 'Reporte de Solicitudes');
                $sheet->setCellValue('A5', 'Generado el: ' . now()->format('d/m/Y H:i'));

                // Opcional: unir celdas para el título (si deseas centrarlo visualmente)
                $sheet->mergeCells('A4:G4');
                $sheet->mergeCells('A5:G5');

                // Estilos: Negrita, tamaño, alineación centrada
                $sheet->getStyle('A4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A5')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 10,
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Ancho de columnas personalizado
                $sheet->getColumnDimension('A')->setWidth(15); // Cuenta
                $sheet->getColumnDimension('B')->setWidth(35); // Nombres
                $sheet->getColumnDimension('C')->setWidth(35); // Email
                $sheet->getColumnDimension('D')->setWidth(15); // Teléfono
                $sheet->getColumnDimension('E')->setWidth(15); // Fecha Ingreso
                $sheet->getColumnDimension('F')->setWidth(25); // Estado
                $sheet->getColumnDimension('G')->setWidth(25); // Usuario

                // Insertar 4 filas vacías al principio
                //$event->sheet->insertNewRowBefore(1, 2);
            },

            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Congelar fila 7 (encabezado)
                $sheet->freezePane('A8');

                // Obtener número de filas de datos
                $numDatos = count($this->collection());
                $ultimaFila = 7 + $numDatos; // fila 7 es donde comienzan los datos

                // Rango a aplicar bordes: desde A7 (encabezado) hasta G{última fila}
                $rangoConBordes = 'A7:G' . $ultimaFila;
                
                // Aplicar bordes finos
                $sheet->getStyle($rangoConBordes)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            }
        ];
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo de la empresa');
        $drawing->setPath(public_path('files-img/logo.png')); // coloca aquí tu logo
        $drawing->setHeight(45);
        $drawing->setCoordinates('A2');
        return $drawing;
    }
}
