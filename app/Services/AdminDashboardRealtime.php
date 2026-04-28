<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class AdminDashboardRealtime
{
    private const CACHE_KEY = 'admin_dashboard_events';

    public function __construct(private AdminDashboardMetrics $metrics)
    {
    }

    public function publish(string $type, string $message, array $context = [], string $level = 'success'): array
    {
        $events = Cache::get(self::CACHE_KEY, []);
        $sequence = (int) data_get(last($events) ?: [], 'sequence', 0) + 1;

        $event = [
            'id' => (string) $sequence,
            'sequence' => $sequence,
            'type' => $type,
            'message' => $message,
            'level' => $level,
            'context' => $context,
            'snapshot' => $this->metrics->snapshot(),
            'occurred_at' => now()->toIso8601String(),
        ];

        $events[] = $event;
        Cache::forever(self::CACHE_KEY, array_slice($events, -20));

        return $event;
    }

    public function eventsAfter(int $sequence = 0): array
    {
        return collect(Cache::get(self::CACHE_KEY, []))
            ->filter(fn (array $event) => (int) ($event['sequence'] ?? 0) > $sequence)
            ->values()
            ->all();
    }
}
