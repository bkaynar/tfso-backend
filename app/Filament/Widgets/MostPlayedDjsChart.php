<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\AccessLog;
use App\Models\Set;
use App\Models\Track;
use App\Models\User;

class MostPlayedDjsChart extends ChartWidget
{
    protected static ?string $heading = 'En Çok Dinlenen DJ\'ler';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        // Set ve Track dinlenmelerini birleştirerek DJ'lere göre grupla
        $setCounts = AccessLog::where('content_type', 'set')
            ->selectRaw('content_id, COUNT(*) as total')
            ->groupBy('content_id')
            ->get();
        $trackCounts = AccessLog::where('content_type', 'track')
            ->selectRaw('content_id, COUNT(*) as total')
            ->groupBy('content_id')
            ->get();

        $djCounts = [];
        // Set sahipleri
        foreach ($setCounts as $row) {
            $set = Set::find($row->content_id);
            if ($set) {
                $djCounts[$set->user_id] = ($djCounts[$set->user_id] ?? 0) + $row->total;
            }
        }
        // Track sahipleri
        foreach ($trackCounts as $row) {
            $track = Track::find($row->content_id);
            if ($track) {
                $djCounts[$track->user_id] = ($djCounts[$track->user_id] ?? 0) + $row->total;
            }
        }
        // En çok dinlenen ilk 10 DJ
        arsort($djCounts);
        $djCounts = array_slice($djCounts, 0, 10, true);

        $labels = [];
        $data = [];
        foreach ($djCounts as $userId => $total) {
            $user = User::find($userId);
            $labels[] = $user ? $user->name : 'Bilinmeyen';
            $data[] = $total;
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
                    'data' => array_map(fn($value) => $value / (count($djCounts) ?: 1), $data),
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
