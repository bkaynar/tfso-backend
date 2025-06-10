<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\AccessLog;
use App\Models\Track;
use Illuminate\Support\Facades\Auth;

class MyTracksStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Benim Parçalarımın Dinlenme Sayıları';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $user = Auth::user();
        if (!$user) {
            return ['datasets' => [], 'labels' => []];
        }

        $tracks = Track::where('user_id', $user->id)->get();
        $labels = [];
        $listenCounts = [];
        foreach ($tracks as $track) {
            $count = AccessLog::where('content_type', 'track')
                ->where('content_id', $track->id)
                ->count();
            $labels[] = $track->name;
            $listenCounts[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Dinlenme Sayısı',
                    'data' => $listenCounts,
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
