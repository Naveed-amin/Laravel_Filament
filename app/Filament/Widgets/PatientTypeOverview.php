<?php

namespace App\Filament\Widgets;

use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PatientTypeOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Cats', Patient::query()->where('type', 'cat')->count()),
            Stat::make('Dogs', Patient::query()->where('type', 'dog')->count()),
            Stat::make('Rabbits', Patient::query()->where('type', 'rabbit')->count()),
            Stat::make('Parrots', Patient::query()->where('type', 'parrot')->count()),
            Stat::make('Hamsters', Patient::query()->where('type', 'hamster')->count()),
            Stat::make('Birds', Patient::query()->where('type', 'bird')->count()),
        ];
    }
}
