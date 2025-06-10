<?php

namespace App\Filament\Resources\StatsResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

use App\Models\User;
use App\Models\Set;
use App\Models\Track;
class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
       return [
            Stat::make('Kullanıcı Sayısı', User::count()),
            Stat::make('Set Sayısı', Set::count()),
            Stat::make('Parça Sayısı', Track::count()),
        ];
    }
}
