<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Spatie\Permission\Models\Role;

class UsersImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    use Importable, SkipsErrors, SkipsFailures;

    protected int $importedCount = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|array|null
     */
    public function model(array $row): \Illuminate\Database\Eloquent\Model|array|null
    {
        $rawPassword = !empty($row['password']) ? (string) $row['password'] : 'password';

        $user = User::create([
            'name' => $row['nama_lengkap'] ?? $row['name'] ?? $row['nama'],
            'email' => strtolower(trim($row['alamat_email'] ?? $row['email'])),
            'password' => Hash::make($rawPassword),
            'email_verified_at' => now(),
        ]);

        // Assign role if specified and valid, otherwise default to 'User'
        $roleName = trim($row['hak_akses'] ?? $row['role'] ?? 'User');
        if (!empty($roleName) && Role::where('name', $roleName)->exists()) {
            $user->assignRole($roleName);
        } else {
            $user->assignRole('User');
        }

        $this->importedCount++;

        return $user;
    }

    /**
     * Validation rules for each row.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            '*.nama_lengkap' => ['sometimes', 'required', 'string', 'max:255'],
            '*.name' => ['sometimes', 'required', 'string', 'max:255'],
            '*.alamat_email' => ['sometimes', 'required', 'email', 'max:255', 'unique:users,email'],
            '*.email' => ['sometimes', 'required', 'email', 'max:255', 'unique:users,email'],
        ];
    }

    /**
     * Custom validation attribute names.
     */
    public function customValidationAttributes()
    {
        return [
            'alamat_email' => 'Alamat Email',
            'email' => 'Email',
            'nama_lengkap' => 'Nama Lengkap',
            'name' => 'Nama',
        ];
    }

    /**
     * Get count of successfully imported users.
     */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }
}
