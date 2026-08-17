<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Enumerable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected int $rowNumber = 0;

    /**
     * @return Enumerable
     */
    public function collection(): Enumerable
    {
        $currentUser = auth()->user();
        $query = User::with('roles')->orderBy('name', 'asc');

        if (!$currentUser || !$currentUser->hasRole('Super Admin')) {
            $query->whereDoesntHave('roles', function ($q) {
                $q->where('name', 'Super Admin');
            });
        }

        return $query->get();
    }

    /**
     * Headings for the export sheet.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'No',
            'ID Pengguna (ULID)',
            'Nama Lengkap',
            'Alamat Email',
            'Hak Akses / Role',
            'Status Akun',
            'Status Verifikasi Email',
            'Tanggal Bergabung',
        ];
    }

    /**
     * Map each user row.
     *
     * @param  mixed  $row
     * @return array
     */
    public function map(mixed $row): array
    {
        $this->rowNumber++;

        $statusLabels = [
            'active' => 'Aktif',
            'nonactive' => 'Non-Aktif',
            'suspended' => 'Suspended',
            'banned' => 'Banned',
        ];

        return [
            $this->rowNumber,
            $row->id,
            $row->name,
            $row->email,
            $row->roles->pluck('name')->implode(', ') ?: 'User',
            $statusLabels[$row->status] ?? ucfirst($row->status ?? 'active'),
            $row->email_verified_at ? 'Terverifikasi (' . $row->email_verified_at->format('d/m/Y H:i') . ')' : 'Belum Diverifikasi',
            $row->created_at ? $row->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    /**
     * Apply styles to worksheet.
     *
     * @param  Worksheet  $sheet
     * @return array|null
     */
    public function styles(Worksheet $sheet): ?array
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF1D3E35'], // Mountain Green
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }
}
