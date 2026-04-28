<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminDashboardRealtime;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DashboardStreamController extends Controller
{
    public function __invoke(Request $request, AdminDashboardRealtime $realtime): StreamedResponse
    {
        $lastSequence = (int) ($request->header('Last-Event-ID') ?? $request->query('last_event_id', 0));
        $once = $request->boolean('once');

        return response()->stream(function () use ($realtime, $lastSequence, $once): void {
            $currentSequence = $lastSequence;
            $startedAt = time();

            while (time() - $startedAt < 20) {
                $events = $realtime->eventsAfter($currentSequence);

                foreach ($events as $event) {
                    $currentSequence = (int) $event['sequence'];

                    echo 'id: ' . $event['id'] . "\n";
                    echo "event: dashboard-update\n";
                    echo 'data: ' . json_encode($event, JSON_UNESCAPED_SLASHES) . "\n\n";

                    if (ob_get_level() > 0) {
                        ob_flush();
                    }

                    flush();

                    if ($once) {
                        return;
                    }
                }

                echo ": ping\n\n";

                if (ob_get_level() > 0) {
                    ob_flush();
                }

                flush();
                sleep(1);
            }
        }, 200, [
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'Content-Type' => 'text/event-stream',
            'X-Accel-Buffering' => 'no',
        ]);
    }
}
