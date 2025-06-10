<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\AccessLog;
use App\Models\Set;
use Illuminate\Support\Facades\Auth;

class MySetsStatsChart extends ChartWidget
{
    protected static ?string $heading = 'Benim Setlerimin Dinlenme Sayıları';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $user = Auth::user();
        if (!$user) {
            return ['datasets' => [], 'labels' => []];
        }

        $sets = Set::where('user_id', $user->id)->get();
        $labels = [];
        $listenCounts = [];
        foreach ($sets as $set) {
            $count = AccessLog::where('content_type', 'set')
                ->where('content_id', $set->id)
                ->count();
            $labels[] = $set->name;
            $listenCounts[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Dinlenme Sayısı',
                    'data' => $listenCounts,
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
