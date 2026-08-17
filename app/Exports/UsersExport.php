<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class UsersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected int $rowNumber = 0;

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
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
     * @param  User  $user
     * @return array
     */
    public function map($user): array
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
            $user->id,
            $user->name,
            $user->email,
            $user->roles->pluck('name')->implode(', ') ?: 'User',
            $statusLabels[$user->status] ?? ucfirst($user->status),
            $user->email_verified_at ? 'Terverifikasi (' . $user->email_verified_at->format('d/m/Y H:i') . ')' : 'Belum Diverifikasi',
            $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-',
        ];
    }

    /**
     * Apply styles to worksheet.
     *
     * @param  Worksheet  $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
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
