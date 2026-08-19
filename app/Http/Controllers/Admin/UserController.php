<?php

namespace App\Http\Controllers\Admin;

use App\Exports\UsersExport;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use App\Models\User;
use App\Models\WebConfiguration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource with Yajra DataTables Server-Side support.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $currentUser = auth()->user();
            $users = User::with('roles')->select('users.*');

            // Non-Super Admin cannot view Super Admin users
            if (!$currentUser || !$currentUser->hasRole('Super Admin')) {
                $users->whereDoesntHave('roles', function ($q) {
                    $q->where('name', 'Super Admin');
                });
            }

            $users->orderBy('name', 'asc');

            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('user_info', function (User $user) {
                    $initial = strtoupper(substr($user->name, 0, 1));
                    $name = e($user->name);
                    $email = e($user->email);

                    return '
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-2xl bg-gradient-to-tr from-[#1d3e35] to-[#31725e] text-white flex items-center justify-center font-bold text-xs shadow-xs shrink-0">
                                ' . $initial . '
                            </div>
                            <div class="min-w-0">
                                <div class="font-extrabold text-xs text-[#1d3e35] truncate">' . $name . '</div>
                                <div class="text-[11px] text-stone-400 font-mono truncate">' . $email . '</div>
                            </div>
                        </div>
                    ';
                })
                ->addColumn('role_badge', function (User $user) {
                    $roleName = $user->getRoleNames()->first() ?? 'User';
                    $roleColors = [
                        'Super Admin' => 'bg-red-50 text-red-700 border-red-200',
                        'Administrator' => 'bg-emerald-50 text-[#1d3e35] border-[#99cab7]/50',
                        'Manager' => 'bg-amber-50 text-amber-800 border-amber-200',
                        'Editor' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'Support' => 'bg-purple-50 text-purple-700 border-purple-200',
                    ];
                    $colorClass = $roleColors[$roleName] ?? 'bg-stone-50 text-stone-600 border-stone-200';

                    return '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border ' . $colorClass . '">' . e($roleName) . '</span>';
                })
                ->addColumn('status_badge', function (User $user) {
                    $status = $user->status ?? 'active';
                    $statusConfig = [
                        'active' => ['bg' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'dot' => 'bg-emerald-500', 'label' => 'Aktif'],
                        'nonactive' => ['bg' => 'bg-stone-100 text-stone-600 border-stone-200', 'dot' => 'bg-stone-400', 'label' => 'Nonaktif'],
                        'suspended' => ['bg' => 'bg-amber-50 text-amber-700 border-amber-200', 'dot' => 'bg-amber-500', 'label' => 'Ditangguhkan'],
                        'banned' => ['bg' => 'bg-red-50 text-red-700 border-red-200', 'dot' => 'bg-red-500', 'label' => 'Diblokir'],
                    ];
                    $cfg = $statusConfig[$status] ?? $statusConfig['active'];

                    return '
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold border ' . $cfg['bg'] . '">
                            <span class="w-1.5 h-1.5 rounded-full ' . $cfg['dot'] . '"></span>
                            ' . $cfg['label'] . '
                        </span>
                    ';
                })
                ->addColumn('email_status', function (User $user) {
                    if ($user->email_verified_at) {
                        return '<span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600"><i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Terverifikasi</span>';
                    }
                    return '<span class="inline-flex items-center gap-1 text-[11px] font-bold text-stone-400"><i data-lucide="clock" class="w-3.5 h-3.5"></i> Belum</span>';
                })
                ->addColumn('action', function (User $user) {
                    $showUrl = route('admin.users.show', $user->id);
                    $editUrl = route('admin.users.edit', $user->id);
                    $roleUrl = route('admin.users.roles.edit', $user->id);

                    return '
                        <div class="flex items-center justify-end gap-1">
                            <a href="' . $showUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#1d3e35] hover:bg-[#e2f0ea]/80 transition-colors" title="Lihat Detail">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="' . $editUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#31725e] hover:bg-[#e2f0ea]/80 transition-colors" title="Edit Profil & Status">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <a href="' . $roleUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#b17042] hover:bg-amber-50 transition-colors" title="Kelola Role & Hak Akses">
                                <i data-lucide="shield-alert" class="w-4 h-4"></i>
                            </a>
                        </div>
                    ';
                })
                ->rawColumns(['user_info', 'role_badge', 'status_badge', 'email_status', 'action'])
                ->toJson();
        }

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $config = WebConfiguration::current();
        if ($config && $config->limit_users_count > 0 && User::count() >= $config->limit_users_count) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Jumlah pengguna telah mencapai batas kuota maksimal ({$config->limit_users_count} akun). Silakan perluas kuota di Web Konfigurasi.");
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'status' => ['required', 'in:active,nonactive,suspended,banned'],
            'email_verified' => ['sometimes', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'] ?? 'active',
            'email_verified_at' => !empty($validated['email_verified']) ? now() : null,
        ]);

        // Automatically assign default 'User' role (Role assignment is separated in dedicated module)
        $user->assignRole('User');

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna baru '{$user->name}' berhasil ditambahkan dengan role User dan status " . ucfirst($user->status) . "!");
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $this->authorizeSuperAdminAccess($user);

        $user->load('roles.permissions', 'permissions');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        $this->authorizeSuperAdminAccess($user);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $this->authorizeSuperAdminAccess($user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'status' => ['required', 'in:active,nonactive,suspended,banned'],
            'email_verified' => ['sometimes', 'boolean'],
        ]);

        // Prevent setting the last Super Admin to nonactive/suspended/banned
        if ($user->hasRole('Super Admin') && $validated['status'] !== 'active') {
            $activeSuperAdminCount = User::role('Super Admin')->where('status', 'active')->count();
            if ($activeSuperAdminCount <= 1) {
                return back()->with('error', 'Tidak dapat menonaktifkan atau memblokir satu-satunya Super Admin aktif pada sistem.');
            }
        }

        $user->name = $validated['name'];
        $user->email = strtolower(trim($validated['email']));
        $user->status = $validated['status'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if (isset($validated['email_verified'])) {
            $user->email_verified_at = $validated['email_verified'] ? ($user->email_verified_at ?? now()) : null;
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna '{$user->name}' berhasil diperbarui!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): RedirectResponse|JsonResponse
    {
        $this->authorizeSuperAdminAccess($user);

        // 1. Cannot delete self
        if (auth()->id() === $user->id) {
            if (request()->ajax()) {
                return response()->json(['error' => 'Anda tidak dapat menghapus akun Anda sendiri.'], 403);
            }
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // 2. Prevent deleting last Super Admin
        if ($user->hasRole('Super Admin')) {
            $superAdminCount = User::role('Super Admin')->count();
            if ($superAdminCount <= 1) {
                if (request()->ajax()) {
                    return response()->json(['error' => 'Tidak dapat menghapus Super Admin terakhir pada sistem.'], 403);
                }
                return back()->with('error', 'Tidak dapat menghapus Super Admin terakhir pada sistem.');
            }
        }

        $name = $user->name;
        $user->delete();

        if (request()->ajax()) {
            return response()->json(['success' => "Pengguna '{$name}' berhasil dihapus."]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna '{$name}' berhasil dihapus dari sistem.");
    }

    /**
     * Export all users to Excel using Maatwebsite Excel.
     */
    public function export(): BinaryFileResponse
    {
        $fileName = 'data-pengguna-' . date('Y-m-d_His') . '.xlsx';
        return Excel::download(new UsersExport, $fileName);
    }

    /**
     * Import users from uploaded Excel file using Maatwebsite Excel.
     */
    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'], // Max 5MB
        ]);

        $config = WebConfiguration::current();
        if ($config && $config->limit_users_count > 0 && User::count() >= $config->limit_users_count) {
            return back()->with('error', "Jumlah pengguna telah mencapai batas kuota maksimal ({$config->limit_users_count} akun). Silakan perluas kuota di Web Konfigurasi.");
        }

        try {
            $import = new UsersImport;
            $import->import($request->file('file'));

            $count = $import->getImportedCount();
            $errors = $import->failures();

            $message = "Berhasil mengimpor {$count} pengguna baru.";
            if ($errors->isNotEmpty()) {
                $message .= " Namun terdapat " . $errors->count() . " baris data yang dilewati karena duplikasi/format tidak valid.";
            }

            return redirect()->route('admin.users.index')->with('success', $message);
        } catch (\Throwable $e) {
            return back()->with('error', 'Gagal mengimpor berkas Excel: ' . $e->getMessage());
        }
    }

    /**
     * Download sample Excel import template.
     */
    public function downloadTemplate(): BinaryFileResponse
    {
        $export = new class implements \Maatwebsite\Excel\Concerns\FromArray, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\ShouldAutoSize {
            public function array(): array
            {
                return [
                    [
                        'nama_lengkap' => 'Budi Santoso',
                        'alamat_email' => 'budi@example.com',
                        'password' => 'password123',
                        'hak_akses' => 'Editor',
                    ],
                    [
                        'nama_lengkap' => 'Siti Rahma',
                        'alamat_email' => 'siti@example.com',
                        'password' => 'password123',
                        'hak_akses' => 'Support',
                    ],
                ];
            }

            public function headings(): array
            {
                return ['nama_lengkap', 'alamat_email', 'password', 'hak_akses'];
            }
        };

        return Excel::download($export, 'template-import-pengguna.xlsx');
    }

    /**
     * Check if non-Super Admin is attempting to access a Super Admin user.
     */
    private function authorizeSuperAdminAccess(User $user): void
    {
        $currentUser = auth()->user();
        if ($currentUser && !$currentUser->hasRole('Super Admin') && $user->hasRole('Super Admin')) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat atau mengelola akun Super Admin.');
        }
    }
}
