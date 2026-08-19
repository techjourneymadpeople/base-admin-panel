<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Feedback extends Model
{
    use HasFactory, HasUlids, LogsActivity;

    /**
     * Activity log options for Feedback model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('feedback')
            ->setDescriptionForEvent(fn(string $eventName) => "Feedback / Masukan {$eventName}");
    }

    protected $table = 'feedbacks';

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'type',
        'message',
        'rating',
        'status',
        'admin_notes',
        'is_starred',
        'replied_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_starred' => 'boolean',
        'replied_at' => 'datetime',
    ];

    /**
     * Types Definition with Labels & Badges (Hanya Saran & Masukan dan Keluhan)
     */
    public const TYPES = [
        'saran_masukan' => ['label' => 'Saran & Masukan', 'color' => 'emerald', 'icon' => 'lightbulb'],
        'keluhan' => ['label' => 'Keluhan', 'color' => 'rose', 'icon' => 'alert-triangle'],
    ];

    /**
     * Statuses Definition with Labels & Badges
     */
    public const STATUSES = [
        'unread' => ['label' => 'Belum Dibaca', 'color' => 'rose', 'icon' => 'mail'],
        'read' => ['label' => 'Sudah Dibaca', 'color' => 'sky', 'icon' => 'mail-open'],
        'in_progress' => ['label' => 'Sedang Diproses', 'color' => 'amber', 'icon' => 'clock'],
        'resolved' => ['label' => 'Selesai (Ditutup)', 'color' => 'emerald', 'icon' => 'check-circle-2'],
        'archived' => ['label' => 'Diarsipkan', 'color' => 'stone', 'icon' => 'archive'],
    ];

    /**
     * Check if feedback is resolved / completed
     */
    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    /**
     * Scopes
     */
    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('status', 'unread');
    }

    public function scopeStarred(Builder $query): Builder
    {
        return $query->where('is_starred', true);
    }

    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Get Type Info Array
     */
    public function getTypeInfo(): array
    {
        if (isset(self::TYPES[$this->type])) {
            return self::TYPES[$this->type];
        }

        // Backward compatibility fallback
        if (in_array($this->type, ['saran', 'kritik', 'pertanyaan', 'lainnya'])) {
            return self::TYPES['saran_masukan'];
        }

        return ['label' => ucfirst($this->type), 'color' => 'stone', 'icon' => 'message-square'];
    }

    /**
     * Get Status Info Array
     */
    public function getStatusInfo(): array
    {
        return self::STATUSES[$this->status] ?? ['label' => ucfirst($this->status), 'color' => 'stone', 'icon' => 'info'];
    }
}
