<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\AccessLog;
use App\Models\Set;
use Illuminate\Support\Facades\Auth;

class MySetsStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Benim Setlerimin Dinlenme Süreleri';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $user = Auth::user();
        if (!$user) {
            return ['datasets' => [], 'labels' => []];
        }

        $sets = Set::where('user_id', $user->id)->get();
        $labels = [];
        $minutes = [];
        foreach ($sets as $set) {
            // Set modelinde duration yoksa 0 olarak al
            $totalSeconds = AccessLog::where('content_type', 'set')
                ->where('content_id', $set->id)
                ->count() * ($set->duration ?? 0);
            $labels[] = $set->name;
            $minutes[] = round($totalSeconds / 60, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Toplam Dinlenme (dk)',
                    'data' => $minutes,
                    'backgroundColor' => '#4ade80',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
