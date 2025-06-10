<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\AccessLog;
use App\Models\Set;

class MostPlayedSetsChart extends ChartWidget
{
    protected static ?string $heading = 'En Çok Dinlenen Setler';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $topSets = AccessLog::select('content_id')
            ->where('content_type', 'set')
            ->groupBy('content_id')
            ->selectRaw('content_id, COUNT(*) as total')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $labels = [];
        $data = [];

        foreach ($topSets as $row) {
            $set = Set::find($row->content_id);
            $labels[] = $set ? $set->name : 'Bilinmeyen';
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
                    'data' => array_map(fn($value) => $value / count($topSets), $data),
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
        $user = \Illuminate\Support\Facades\Auth::user();
        return $user && method_exists($user, 'hasRole') && $user->hasRole('admin');
    }
}
