<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class AuditAnswerExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths, WithEvents, WithDrawings
{
    protected $formattedData;
    protected $auditAnswer;
    protected $signatures;
    protected $grade;
    protected $rowHeights = [];

    public function __construct($formattedData, $auditAnswer, $grade, $signatures = null)
    {
        $this->formattedData = $formattedData;
        $this->auditAnswer = $auditAnswer;
        $this->grade = $grade;
        $this->signatures = $signatures;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $data[] = [
            'Kategori' => 'CATATAN:',
            'Tema' => 'Foto standar dan foto temuan ditampilkan langsung di dalam dokumen Excel.',
            'Standar' => '',
            'Foto Standar' => '',
            'Variabel' => '',
            'Score' => '',
            'Temuan' => '',
            'Foto Temuan' => ''
        ];

        // Tambahkan baris kosong
        $data[] = [
            'Kategori' => '',
            'Tema' => '',
            'Standar' => '',
            'Foto Standar' => '',
            'Variabel' => '',
            'Score' => '',
            'Temuan' => '',
            'Foto Temuan' => ''
        ];

        $currentRow = 4; // Mulai dari baris 4 (setelah header dan catatan)

        foreach ($this->formattedData as $detail) {
            // Tambahkan temuan auditees jika ada
            $temuan = '';
            foreach ($detail['auditees'] as $auditee) {
                $nameKey = isset($auditee['auditee']) ? 'auditee' : 'name';
                $temuan .= $auditee[$nameKey] . ': ' . $auditee['temuan'] . "\n";
            }

            $row = [
                'Kategori' => $detail['kategori'],
                'Tema' => $detail['tema'],
                'Standar' => $detail['standar_variabel'],
                'Foto Standar' => $detail['standar_foto'] ? '(Lihat gambar)' : 'Tidak ada foto',
                'Variabel' => $detail['variabel'],
                'Score' => $detail['score'],
                'Temuan' => $temuan ?: 'Tidak ada temuan',
                'Foto Temuan' => count($detail['images']) > 0 ? '(Lihat gambar)' : 'Tidak ada foto'
            ];

            $data[] = $row;

            // Calculate row height based on content
            $baseHeight = 80; // Base height for rows
            $imageHeight = 120; // Height per image

            // Set height for standar foto
            if ($detail['standar_foto']) {
                $this->rowHeights[$currentRow] = $imageHeight;
            } else {
                $this->rowHeights[$currentRow] = $baseHeight;
            }

            // Adjust height for temuan images if needed
            if (count($detail['images']) > 0) {
                $this->rowHeights[$currentRow] = max(
                    $this->rowHeights[$currentRow],
                    $baseHeight + ($imageHeight * min(count($detail['images']), 3))
                ); // Limit to 3 images in height calc
            }

            $currentRow++;
        }

        // Tambahkan total score dan grade di baris terakhir
        $data[] = [
            'Kategori' => '',
            'Tema' => '',
            'Standar' => '',
            'Foto Standar' => '',
            'Variabel' => 'Total Score',
            'Score' => $this->auditAnswer->total_score,
            'Temuan' => '',
            'Foto Temuan' => ''
        ];

        $data[] = [
            'Kategori' => '',
            'Tema' => '',
            'Standar' => '',
            'Foto Standar' => '',
            'Variabel' => 'Grade',
            'Score' => $this->grade,
            'Temuan' => '',
            'Foto Temuan' => ''
        ];

        $data[] = [
            'Kategori' => '',
            'Tema' => '',
            'Standar' => '',
            'Foto Standar' => '',
            'Variabel' => '',
            'Score' => '',
            'Temuan' => '',
            'Foto Temuan' => ''
        ];

        // Tambahkan header tanda tangan
        $data[] = [
            'Kategori' => 'TANDA TANGAN',
            'Tema' => '',
            'Standar' => '',
            'Foto Standar' => '',
            'Variabel' => '',
            'Score' => '',
            'Temuan' => '',
            'Foto Temuan' => ''
        ];

        // Tambahkan row untuk tanda tangan (3 kolom)
        $data[] = [
            'Kategori' => '',
            'Tema' => '',
            'Standar' => '',
            'Foto Standar' => '',
            'Variabel' => '',
            'Score' => '',
            'Temuan' => '',
            'Foto Temuan' => ''
        ];

        // Tambahkan row kosong untuk ruang tanda tangan
        $data[] = [
            'Kategori' => 'Auditor:',
            'Tema' => '',
            'Standar' => 'Fasilitator:',
            'Foto Standar' => '',
            'Variabel' => 'Auditee:',
            'Score' => '',
            'Temuan' => '',
            'Foto Temuan' => ''
        ];

        // Tambahkan nama-nama penanda tangan
        $data[] = [
            'Kategori' => $this->auditAnswer->auditor->name ?? 'N/A',
            'Tema' => '',
            'Standar' => $this->signatures->manager_name ?? 'N/A',
            'Foto Standar' => '',
            'Variabel' => $this->signatures->auditee_name ?? 'N/A',
            'Score' => '',
            'Temuan' => '',
            'Foto Temuan' => ''
        ];

        // Tambahkan tanggal tanda tangan
        $data[] = [
            'Kategori' => 'Tanggal: ' . ($this->auditAnswer->created_at ? $this->auditAnswer->created_at->format('d-m-Y') : 'N/A'),
            'Tema' => '',
            'Standar' => 'Tanggal: ' . ($this->auditAnswer->created_at ? $this->auditAnswer->created_at->format('d-m-Y') : 'N/A'),
            'Foto Standar' => '',
            'Variabel' => 'Tanggal: ' . ($this->auditAnswer->created_at ? $this->auditAnswer->created_at->format('d-m-Y') : 'N/A'),
            'Score' => '',
            'Temuan' => '',
            'Foto Temuan' => ''
        ];

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Kategori',
            'Tema',
            'Standar',
            'Foto Standar',
            'Variabel',
            'Score',
            'Temuan',
            'Foto Temuan'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = $sheet->getHighestRow();
        $lastColumn = $sheet->getHighestColumn();

        // Set wrap text untuk semua sel
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->getAlignment()->setWrapText(true);

        // Set vertical alignment ke top untuk semua sel
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        // Set border untuk semua sel
        $sheet->getStyle('A1:' . $lastColumn . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

        // Style untuk header
        $sheet->getStyle('A1:' . $lastColumn . '1')->getFont()->setBold(true);
        $sheet->getStyle('A1:' . $lastColumn . '1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFCCCCCC');
        $sheet->getStyle('A1:' . $lastColumn . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Style untuk baris catatan
        $sheet->getStyle('A2:H2')->getFont()->setBold(true);
        $sheet->getStyle('A2:H3')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFEEEEEE');

        // Merge cells untuk catatan
        $sheet->mergeCells('B2:H2');

        // Style untuk baris data
        $dataStartRow = 4;
        $dataEndRow = $lastRow - 9; // Adjusted for additional signature rows
        $sheet->getStyle('A' . $dataStartRow . ':' . $lastColumn . $dataEndRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

        // Style untuk total score dan grade
        $totalScoreRow = $lastRow - 8;
        $gradeRow = $lastRow - 7;
        $sheet->getStyle('A' . $totalScoreRow . ':' . $lastColumn . $gradeRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $totalScoreRow . ':' . $lastColumn . $gradeRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDDDDDD');

        // Style untuk header tanda tangan
        $signatureHeaderRow = $lastRow - 5;
        $sheet->getStyle('A' . $signatureHeaderRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $signatureHeaderRow . ':' . $lastColumn . $signatureHeaderRow)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FFDDDDDD');
        $sheet->mergeCells('A' . $signatureHeaderRow . ':H' . $signatureHeaderRow);

        // Style untuk baris tanda tangan
        $signatureRow = $lastRow - 4;
        $sheet->getStyle('A' . $signatureRow)->getFont()->setBold(true);
        $sheet->getStyle('C' . $signatureRow)->getFont()->setBold(true);
        $sheet->getStyle('E' . $signatureRow)->getFont()->setBold(true);

        // Merge cells untuk setiap bagian tanda tangan
        $sheet->mergeCells('A' . $signatureRow . ':B' . $signatureRow);
        $sheet->mergeCells('C' . $signatureRow . ':D' . $signatureRow);
        $sheet->mergeCells('E' . $signatureRow . ':F' . $signatureRow);

        // Merge cells untuk ruang tanda tangan
        $signatureImageRow = $lastRow - 3;
        $sheet->mergeCells('A' . $signatureImageRow . ':B' . $signatureImageRow);
        $sheet->mergeCells('C' . $signatureImageRow . ':D' . $signatureImageRow);
        $sheet->mergeCells('E' . $signatureImageRow . ':F' . $signatureImageRow);
        $sheet->getRowDimension($signatureImageRow)->setRowHeight(100); // Tinggi untuk ruang tanda tangan - INCREASED

        // Merge cells untuk nama tanda tangan
        $nameSignatureRow = $lastRow - 2;
        $sheet->mergeCells('A' . $nameSignatureRow . ':B' . $nameSignatureRow);
        $sheet->mergeCells('C' . $nameSignatureRow . ':D' . $nameSignatureRow);
        $sheet->mergeCells('E' . $nameSignatureRow . ':F' . $nameSignatureRow);

        // Merge cells untuk tanggal tanda tangan
        $dateSignatureRow = $lastRow - 1;
        $sheet->mergeCells('A' . $dateSignatureRow . ':B' . $dateSignatureRow);
        $sheet->mergeCells('C' . $dateSignatureRow . ':D' . $dateSignatureRow);
        $sheet->mergeCells('E' . $dateSignatureRow . ':F' . $dateSignatureRow);

        return [
            1 => ['font' => ['bold' => true, 'size' => 12]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,  // Kategori
            'B' => 20,  // Tema
            'C' => 30,  // Standar
            'D' => 25,  // Foto Standar
            'E' => 25,  // Variabel
            'F' => 10,  // Score
            'G' => 40,  // Temuan
            'H' => 25,  // Foto Temuan
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getRowDimension(1)->setRowHeight(25);
                $event->sheet->getRowDimension(2)->setRowHeight(30);
                $event->sheet->getRowDimension(3)->setRowHeight(20);

                // Set row height untuk baris data dari kalkulasi
                foreach ($this->rowHeights as $row => $height) {
                    $event->sheet->getRowDimension($row)->setRowHeight($height);
                }

                // Set default height untuk baris lain
                $lastRow = $event->sheet->getHighestRow();
                for ($i = 4; $i <= $lastRow - 9; $i++) { // Adjusted for signature rows
                    if (!isset($this->rowHeights[$i])) {
                        $event->sheet->getRowDimension($i)->setRowHeight(50);
                    }
                }

                // Set larger height for signature row - UPDATED
                $signatureImageRow = $lastRow - 3;
                $event->sheet->getRowDimension($signatureImageRow)->setRowHeight(150);

                // Auto-filter untuk header
                $event->sheet->setAutoFilter('A1:H1');

                // Freeze panes - tetapkan header tetap terlihat saat scroll
                $event->sheet->freezePane('A4');
            },
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $currentRow = 4; // Mulai dari baris 4 (setelah header dan catatan)

        foreach ($this->formattedData as $detail) {
            // Tambahkan gambar standar jika ada
            if ($detail['standar_foto']) {
                $standardImagePath = storage_path('app/public/' . $detail['standar_foto']);

                if (file_exists($standardImagePath)) {
                    $drawing = new Drawing();
                    $drawing->setName('Foto Standar');
                    $drawing->setDescription('Foto Standar');
                    $drawing->setPath($standardImagePath);
                    $drawing->setHeight(120);
                    $drawing->setWidth(120);
                    $drawing->setResizeProportional(true);
                    $drawing->setCoordinates('D' . $currentRow);
                    $drawing->setOffsetX(5);
                    $drawing->setOffsetY(5);
                    $drawings[] = $drawing;
                }
            }

            // Tambahkan gambar temuan jika ada
            if (count($detail['images']) > 0) {
                $offsetY = 5;
                foreach ($detail['images'] as $index => $image) {
                    $imagePath = storage_path('app/public/' . $image['image_path']);

                    if (file_exists($imagePath)) {
                        $drawing = new Drawing();
                        $drawing->setName('Foto Temuan ' . ($index + 1));
                        $drawing->setDescription('Foto Temuan ' . ($index + 1));
                        $drawing->setPath($imagePath);
                        $drawing->setHeight(120);
                        $drawing->setWidth(120);
                        $drawing->setResizeProportional(true);
                        $drawing->setCoordinates('H' . $currentRow);
                        $drawing->setOffsetX(5);
                        $drawing->setOffsetY($offsetY);
                        $drawings[] = $drawing;

                        $offsetY += 125; // Space between images
                    }
                }
            }

            $currentRow++;
        }

        // Add signature images
        $lastRow = count($this->formattedData) + 7; // Adjust based on the data rows + header rows + grade/score rows
        $signatureImageRow = $lastRow;

        // Auditor signature - UPDATED
        if ($this->signatures && $this->signatures->auditor_signature) {
            $auditorSignPath = storage_path('app/public/' . $this->signatures->auditor_signature);
            if (file_exists($auditorSignPath)) {
                $drawing = new Drawing();
                $drawing->setName('Tanda Tangan Auditor');
                $drawing->setDescription('Tanda Tangan Auditor');
                $drawing->setPath($auditorSignPath);
                $drawing->setHeight(80);
                $drawing->setWidth(150);
                $drawing->setResizeProportional(true);
                $drawing->setCoordinates('A' . $signatureImageRow);
                $drawing->setOffsetX(10);
                $drawing->setOffsetY(35); // INCREASED from 10 to 35
                $drawings[] = $drawing;
            }
        }

        // Manager signature - UPDATED
        if ($this->signatures && $this->signatures->facilitator_signature) {
            $managerSignPath = storage_path('app/public/' . $this->signatures->facilitator_signature);
            if (file_exists($managerSignPath)) {
                $drawing = new Drawing();
                $drawing->setName('Tanda Tangan Fasilitator');
                $drawing->setDescription('Tanda Tangan Fasilitator');
                $drawing->setPath($managerSignPath);
                $drawing->setHeight(80);
                $drawing->setWidth(150);
                $drawing->setResizeProportional(true);
                $drawing->setCoordinates('C' . $signatureImageRow);
                $drawing->setOffsetX(10);
                $drawing->setOffsetY(35); // INCREASED from 10 to 35
                $drawings[] = $drawing;
            }
        }

        // Auditee signature - UPDATED
        if ($this->signatures && $this->signatures->auditee_signature) {
            $auditeeSignPath = storage_path('app/public/' . $this->signatures->auditee_signature);
            if (file_exists($auditeeSignPath)) {
                $drawing = new Drawing();
                $drawing->setName('Tanda Tangan Auditee');
                $drawing->setDescription('Tanda Tangan Auditee');
                $drawing->setPath($auditeeSignPath);
                $drawing->setHeight(80);
                $drawing->setWidth(150);
                $drawing->setResizeProportional(true);
                $drawing->setCoordinates('E' . $signatureImageRow);
                $drawing->setOffsetX(10);
                $drawing->setOffsetY(35); // INCREASED from 10 to 35
                $drawings[] = $drawing;
            }
        }

        return $drawings;
    }
}
