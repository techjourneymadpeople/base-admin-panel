<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory, HasUlids;

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
     * Types Definition with Labels & Badges
     */
    public const TYPES = [
        'saran' => ['label' => 'Saran / Masukan', 'color' => 'emerald', 'icon' => 'lightbulb'],
        'kritik' => ['label' => 'Kritik Konstruktif', 'color' => 'amber', 'icon' => 'alert-triangle'],
        'pertanyaan' => ['label' => 'Pertanyaan', 'color' => 'sky', 'icon' => 'help-circle'],
        'keluhan' => ['label' => 'Keluhan Layanan', 'color' => 'rose', 'icon' => 'alert-octagon'],
        'lainnya' => ['label' => 'Lainnya', 'color' => 'stone', 'icon' => 'message-square'],
    ];

    /**
     * Statuses Definition with Labels & Badges
     */
    public const STATUSES = [
        'unread' => ['label' => 'Belum Dibaca', 'color' => 'rose', 'icon' => 'mail'],
        'read' => ['label' => 'Sudah Dibaca', 'color' => 'sky', 'icon' => 'mail-open'],
        'in_progress' => ['label' => 'Sedang Diproses', 'color' => 'amber', 'icon' => 'clock'],
        'resolved' => ['label' => 'Selesai / Ditindaklanjuti', 'color' => 'emerald', 'icon' => 'check-circle-2'],
        'archived' => ['label' => 'Diarsipkan', 'color' => 'stone', 'icon' => 'archive'],
    ];

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
        return self::TYPES[$this->type] ?? ['label' => ucfirst($this->type), 'color' => 'stone', 'icon' => 'message-square'];
    }

    /**
     * Get Status Info Array
     */
    public function getStatusInfo(): array
    {
        return self::STATUSES[$this->status] ?? ['label' => ucfirst($this->status), 'color' => 'stone', 'icon' => 'info'];
    }
}
