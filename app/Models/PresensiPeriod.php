<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiPeriod extends Model
{
    use HasFactory;

    public const TYPE_OPTIONS = [
        'semester_ganjil' => 'Semester Ganjil',
        'semester_genap' => 'Semester Genap',
        'tahun_ajaran' => '1 Tahun Ajaran',
        'custom' => 'Periode Kustom',
    ];

    public const DAY_OPTIONS = [
        'monday' => 'Senin',
        'tuesday' => 'Selasa',
        'wednesday' => 'Rabu',
        'thursday' => 'Kamis',
        'friday' => 'Jumat',
        'saturday' => 'Sabtu',
        'sunday' => 'Minggu',
    ];

    protected $fillable = [
        'name',
        'period_type',
        'start_date',
        'end_date',
        'active_days',
        'is_active',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'active_days' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function includesDate(Carbon $date): bool
    {
        $startDate = Carbon::parse($this->start_date)->startOfDay();
        $endDate = Carbon::parse($this->end_date)->endOfDay();

        return $date->betweenIncluded($startDate, $endDate);
    }

    public function isOperationalOn(Carbon $date): bool
    {
        if (! $this->includesDate($date)) {
            return false;
        }

        $dayKey = strtolower($date->englishDayOfWeek);

        return in_array($dayKey, $this->active_days ?? [], true);
    }

    public static function defaultActiveDays(): array
    {
        return ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
    }

    public static function activeForDate(Carbon $date): ?self
    {
        return static::query()
            ->whereDate('start_date', '<=', $date->toDateString())
            ->whereDate('end_date', '>=', $date->toDateString())
            ->orderByDesc('is_active')
            ->orderByDesc('start_date')
            ->first();
    }

    public function activeDayLabels(): array
    {
        return collect($this->active_days ?? [])
            ->map(fn (string $day) => self::DAY_OPTIONS[$day] ?? ucfirst($day))
            ->values()
            ->all();
    }
}