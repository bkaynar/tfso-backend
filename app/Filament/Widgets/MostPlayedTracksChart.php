<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\AccessLog;
use App\Models\Track;

class MostPlayedTracksChart extends ChartWidget
{
    protected static ?string $heading = 'En Çok Dinlenen Parçalar';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $topTracks = AccessLog::select('content_id')
            ->where('content_type', 'track')
            ->groupBy('content_id')
            ->selectRaw('content_id, COUNT(*) as total')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $labels = [];
        $data = [];

        foreach ($topTracks as $row) {
            $track = Track::find($row->content_id);
            $labels[] = $track ? $track->name : 'Bilinmeyen';
            $data[] = $row->total;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Dinlenme',
                    'data' => $data,
                    'backgroundColor' => '#6366f1',
                ],
                [
                    'label' => 'Toplam Dinlenme',
                    'data' => $data,
                    'backgroundColor' => '#4ade80',
                ],
                [
                    'label' => 'Ortalama Dinlenme',
                    'data' => array_map(fn($value) => $value / count($topTracks), $data),
                    'backgroundColor' => '#f59e0b',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}
