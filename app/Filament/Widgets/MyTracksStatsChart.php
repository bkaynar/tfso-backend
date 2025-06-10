<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\AccessLog;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;

class MyTracksStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Benim Parçalarımın Dinlenme Süreleri';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $user = Auth::user();
        if (!$user) {
            return ['datasets' => [], 'labels' => []];
        }

        $tracks = Track::where('user_id', $user->id)->get();
        $labels = [];
        $minutes = [];
        foreach ($tracks as $track) {
            $totalSeconds = AccessLog::where('content_type', 'track')
                ->where('content_id', $track->id)
                ->count() * ($track->duration ?? 0);
            $labels[] = $track->name;
            $minutes[] = round($totalSeconds / 60, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Toplam Dinlenme (dk)',
                    'data' => $minutes,
                    'backgroundColor' => '#6366f1',
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
