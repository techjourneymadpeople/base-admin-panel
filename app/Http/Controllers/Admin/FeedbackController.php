<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class FeedbackController extends Controller
{
    /**
     * Display a listing of Feedbacks.
     */
    public function index(Request $request): View|JsonResponse
    {
        if ($request->ajax()) {
            $feedbacks = Feedback::query()->select('feedbacks.*');

            if ($request->filled('type')) {
                $feedbacks->where('type', $request->input('type'));
            }

            if ($request->filled('status')) {
                $feedbacks->where('status', $request->input('status'));
            }

            if ($request->has('is_starred') && $request->input('is_starred') !== '' && $request->input('is_starred') !== null) {
                $feedbacks->where('is_starred', $request->input('is_starred') == '1' ? 1 : 0);
            }

            return DataTables::of($feedbacks)
                ->addIndexColumn()
                ->addColumn('star', function (Feedback $feedback) {
                    $starred = $feedback->is_starred ? 'text-amber-400 fill-amber-400' : 'text-stone-300 hover:text-amber-400';
                    $toggleUrl = route('admin.feedbacks.toggle-star', $feedback->id);

                    return '
                        <button type="button" onclick="toggleFeedbackStar(\'' . $toggleUrl . '\', this)" class="focus:outline-none transition-colors p-1 cursor-pointer" title="Tandai Bintang Prioritas">
                            <i data-lucide="star" class="w-4 h-4 ' . $starred . '"></i>
                        </button>
                    ';
                })
                ->addColumn('sender', function (Feedback $feedback) {
                    $phoneHtml = $feedback->phone
                        ? '<span class="text-[11px] text-stone-500 font-mono flex items-center gap-1"><i data-lucide="phone" class="w-3 h-3"></i>' . e($feedback->phone) . '</span>'
                        : '';

                    $unreadDot = $feedback->status === 'unread'
                        ? '<span class="w-2 h-2 rounded-full bg-rose-500 shrink-0" title="Belum Dibaca"></span>'
                        : '';

                    return '
                        <div class="space-y-0.5">
                            <div class="flex items-center gap-1.5">
                                ' . $unreadDot . '
                                <h4 class="font-extrabold text-xs text-[#1d3e35]">' . e($feedback->name) . '</h4>
                            </div>
                            <p class="text-[11px] text-stone-500 font-medium">' . e($feedback->email) . '</p>
                            ' . $phoneHtml . '
                        </div>
                    ';
                })
                ->addColumn('type_badge', function (Feedback $feedback) {
                    $info = $feedback->getTypeInfo();
                    $colorMap = [
                        'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        'amber' => 'bg-amber-50 text-amber-700 border-amber-200',
                        'sky' => 'bg-sky-50 text-sky-700 border-sky-200',
                        'rose' => 'bg-rose-50 text-rose-700 border-rose-200',
                        'stone' => 'bg-stone-100 text-stone-700 border-stone-200',
                    ];
                    $badgeClass = $colorMap[$info['color']] ?? 'bg-stone-100 text-stone-700 border-stone-200';

                    return '<span class="inline-flex items-center gap-1 text-[11px] font-bold px-2.5 py-0.5 rounded-full border ' . $badgeClass . '">' . e($info['label']) . '</span>';
                })
                ->addColumn('subject_message', function (Feedback $feedback) {
                    $subjectHtml = $feedback->subject
                        ? '<h5 class="font-bold text-xs text-stone-800 line-clamp-1 mb-0.5">' . e($feedback->subject) . '</h5>'
                        : '';
                    $snippet = mb_strimwidth(strip_tags($feedback->message), 0, 80, '...');

                    return '
                        <div class="max-w-xs space-y-0.5">
                            ' . $subjectHtml . '
                            <p class="text-[11px] text-stone-600 line-clamp-2 italic leading-relaxed">"' . e($snippet) . '"</p>
                        </div>
                    ';
                })
                ->addColumn('rating_display', function (Feedback $feedback) {
                    if (!$feedback->rating) {
                        return '<span class="text-[11px] text-stone-400 italic">-</span>';
                    }

                    $stars = '';
                    for ($i = 1; $i <= 5; $i++) {
                        $stars .= $i <= $feedback->rating
                            ? '<span class="text-amber-400 text-xs">★</span>'
                            : '<span class="text-stone-300 text-xs">★</span>';
                    }
                    return '<div class="flex items-center gap-0.5" title="' . $feedback->rating . ' dari 5 Bintang">' . $stars . '</div>';
                })
                ->addColumn('status_badge', function (Feedback $feedback) {
                    $statusUrl = route('admin.feedbacks.update-status', $feedback->id);
                    $options = '';
                    foreach (Feedback::STATUSES as $key => $meta) {
                        $selected = $feedback->status === $key ? 'selected' : '';
                        $options .= '<option value="' . $key . '" ' . $selected . '>' . $meta['label'] . '</option>';
                    }

                    $colorMap = [
                        'rose' => 'text-rose-700 bg-rose-50 border-rose-200',
                        'sky' => 'text-sky-700 bg-sky-50 border-sky-200',
                        'amber' => 'text-amber-700 bg-amber-50 border-amber-200',
                        'emerald' => 'text-emerald-700 bg-emerald-50 border-emerald-200',
                        'stone' => 'text-stone-700 bg-stone-100 border-stone-200',
                    ];
                    $currentColor = $colorMap[$feedback->getStatusInfo()['color']] ?? 'text-stone-700 bg-stone-100 border-stone-200';

                    return '
                        <select onchange="updateFeedbackStatus(\'' . $statusUrl . '\', this.value, this)" class="text-[11px] font-bold rounded-xl px-2 py-1 border outline-none cursor-pointer ' . $currentColor . '">
                            ' . $options . '
                        </select>
                    ';
                })
                ->addColumn('created_date', function (Feedback $feedback) {
                    return '<span class="text-[11px] text-stone-500 font-mono" title="' . $feedback->created_at->format('Y-m-d H:i:s') . '">' . $feedback->created_at->translatedFormat('d M Y, H:i') . '</span>';
                })
                ->addColumn('action', function (Feedback $feedback) {
                    $showUrl = route('admin.feedbacks.show', $feedback->id);
                    $editUrl = route('admin.feedbacks.edit', $feedback->id);
                    $deleteUrl = route('admin.feedbacks.destroy', $feedback->id);

                    return '
                        <div class="flex items-center justify-end gap-1">
                            <a href="' . $showUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#31725e] hover:bg-[#e2f0ea]/80 transition-colors" title="Lihat Detail Pesan">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="' . $editUrl . '" class="p-1.5 rounded-xl text-stone-500 hover:text-[#31725e] hover:bg-[#e2f0ea]/80 transition-colors" title="Edit Masukan">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                            </a>
                            <button type="button" onclick="confirmDeleteFeedback(\'' . $deleteUrl . '\', \'' . addslashes($feedback->name) . '\')" class="p-1.5 rounded-xl text-stone-500 hover:text-red-600 hover:bg-red-50 transition-colors cursor-pointer" title="Hapus Masukan">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['star', 'sender', 'type_badge', 'subject_message', 'rating_display', 'status_badge', 'created_date', 'action'])
                ->make(true);
        }

        $stats = [
            'total' => Feedback::count(),
            'unread' => Feedback::where('status', 'unread')->count(),
            'in_progress' => Feedback::where('status', 'in_progress')->count(),
            'resolved' => Feedback::where('status', 'resolved')->count(),
        ];

        $types = Feedback::TYPES;
        $statuses = Feedback::STATUSES;

        return view('admin.feedbacks.index', compact('stats', 'types', 'statuses'));
    }

    /**
     * Display the specified Feedback.
     */
    public function show(Feedback $feedback): View
    {
        // Auto mark as read if it was unread
        if ($feedback->status === 'unread') {
            $feedback->status = 'read';
            $feedback->save();
        }

        $types = Feedback::TYPES;
        $statuses = Feedback::STATUSES;

        return view('admin.feedbacks.show', compact('feedback', 'types', 'statuses'));
    }

    /**
     * Show the form for creating a new Feedback manually.
     */
    public function create(): View
    {
        $types = Feedback::TYPES;
        $statuses = Feedback::STATUSES;

        return view('admin.feedbacks.create', compact('types', 'statuses'));
    }

    /**
     * Store a newly created Feedback in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_keys(Feedback::TYPES)),
            'message' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'status' => 'required|string|in:' . implode(',', array_keys(Feedback::STATUSES)),
            'admin_notes' => 'nullable|string',
            'is_starred' => 'nullable|boolean',
        ]);

        $validated['is_starred'] = $request->boolean('is_starred');

        if ($validated['status'] === 'resolved' && empty($validated['replied_at'])) {
            $validated['replied_at'] = now();
        }

        Feedback::create($validated);

        return redirect()
            ->route('admin.feedbacks.index')
            ->with('success', 'Saran / Masukan dari "' . $validated['name'] . '" berhasil dicatat!');
    }

    /**
     * Show the form for editing the specified Feedback.
     */
    public function edit(Feedback $feedback): View
    {
        $types = Feedback::TYPES;
        $statuses = Feedback::STATUSES;

        return view('admin.feedbacks.edit', compact('feedback', 'types', 'statuses'));
    }

    /**
     * Update the specified Feedback in storage.
     */
    public function update(Request $request, Feedback $feedback): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'subject' => 'nullable|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_keys(Feedback::TYPES)),
            'message' => 'required|string',
            'rating' => 'nullable|integer|min:1|max:5',
            'status' => 'required|string|in:' . implode(',', array_keys(Feedback::STATUSES)),
            'admin_notes' => 'nullable|string',
            'is_starred' => 'nullable|boolean',
        ]);

        $validated['is_starred'] = $request->boolean('is_starred');

        if ($validated['status'] === 'resolved' && !$feedback->replied_at) {
            $validated['replied_at'] = now();
        }

        $feedback->update($validated);

        return redirect()
            ->route('admin.feedbacks.index')
            ->with('success', 'Data masukan dari "' . $feedback->name . '" berhasil diperbarui!');
    }

    /**
     * Remove the specified Feedback from storage.
     */
    public function destroy(Feedback $feedback): RedirectResponse|JsonResponse
    {
        $name = $feedback->name;
        $feedback->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Masukan dari "' . $name . '" berhasil dihapus!',
            ]);
        }

        return redirect()
            ->route('admin.feedbacks.index')
            ->with('success', 'Masukan dari "' . $name . '" berhasil dihapus!');
    }

    /**
     * Toggle Star priority via AJAX.
     */
    public function toggleStar(Feedback $feedback): JsonResponse
    {
        $feedback->is_starred = !$feedback->is_starred;
        $feedback->save();

        return response()->json([
            'success' => true,
            'is_starred' => $feedback->is_starred,
            'message' => $feedback->is_starred ? 'Ditandai sebagai prioritas.' : 'Tanda prioritas dihapus.',
        ]);
    }

    /**
     * Update status via AJAX.
     */
    public function updateStatus(Request $request, Feedback $feedback): JsonResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|in:' . implode(',', array_keys(Feedback::STATUSES)),
        ]);

        $feedback->status = $validated['status'];
        if ($validated['status'] === 'resolved' && !$feedback->replied_at) {
            $feedback->replied_at = now();
        }
        $feedback->save();

        return response()->json([
            'success' => true,
            'status' => $feedback->status,
            'message' => 'Status masukan berhasil diubah menjadi ' . $feedback->getStatusInfo()['label'] . '.',
        ]);
    }
}
