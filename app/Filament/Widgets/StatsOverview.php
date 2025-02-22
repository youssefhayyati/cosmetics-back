<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    // protected static ?string $heading = 'Dashboard';

    protected static ?string $pollingInterval = null;

    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 2;

    protected function getStats(): array
    {
        return [
            Stat::make('', function () {
                return Order::count();
            })
                ->description('Total orders')
                ->descriptionIcon('heroicon-o-tag', IconPosition::Before)
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('', function () {
                return Order::where('status', 'pending')->count();
            })
                ->description('Pending orders')
                ->descriptionIcon('heroicon-o-clock', IconPosition::Before)
                ->color('warning')
                ->chart([9, 3, 11, 5, 19, 7, 21]),

            Stat::make('', function () {
                return Order::whereDate('created_at', today())->count();
            })
                ->description('New orders')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before)
                ->color('')
                ->chart([10, 4, 12, 6, 20, 8, 22]),

            Stat::make('', function () {
                return Order::where('status', 'completed')->count();
            })
                ->description('Completed orders')
                ->descriptionIcon('heroicon-o-check-circle', IconPosition::Before)
                ->color('success')
                ->chart([11, 5, 13, 7, 21, 9, 23]),

            Stat::make('', function () {
                return Order::where('status', 'cancelled')->count();
            })
                ->description('Cancelled orders')
                ->descriptionIcon('heroicon-o-x-circle', IconPosition::Before)
                ->color('danger')
                ->chart([12, 6, 14, 8, 22, 10, 24]),
        ];
    }
}
