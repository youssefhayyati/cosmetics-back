<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderChart extends ChartWidget
{
    protected static ?string $heading = 'Orders';
    protected static string $color = 'info';
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 2;

    public ?string $filter = 'year';

    protected function getData(): array
    {
        $orderCounts = [];
        $labels = [];

        $query = Order::query();

        switch ($this->filter) {
            case 'today':
                $query->whereDate('created_at', Carbon::today());
                $labels = $this->getHourlyLabels();
                $orderCounts = $this->getHourlyOrderCounts($query);
                break;

            case 'month':
                $startOfMonth = Carbon::now()->startOfMonth();
                $query->whereMonth('created_at', $startOfMonth->month)
                    ->whereYear('created_at', $startOfMonth->year);
                $labels = $this->getDailyLabels($startOfMonth->daysInMonth);
                $orderCounts = $this->getDailyOrderCounts($query);
                break;

            case 'year':
                $query->whereYear('created_at', Carbon::now()->year);
                $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                $orderCounts = $this->getMonthlyOrderCounts($query);
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $orderCounts,
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'tension' => 0.1
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'month' => 'This month',
            'year' => 'This year',
        ];
    }

    private function getHourlyLabels(): array
    {
        return array_map(function ($hour) {
            return sprintf('%02d:00', $hour);
        }, range(0, 23));
    }

    private function getDailyLabels(int $days): array
    {
        $labels = [];
        $currentDate = Carbon::now()->startOfMonth();

        for ($i = 1; $i <= $days; $i++) {
            $labels[] = $i; // Just show the day number
            $currentDate->addDay();
        }

        return $labels;
    }

    private function getHourlyOrderCounts($query): array
    {
        $hourlyCounts = array_fill(0, 24, 0);

        $orders = $query->select(DB::raw('EXTRACT(HOUR FROM created_at) as hour'), DB::raw('COUNT(*) as count'))
            ->groupBy('hour')
            ->get();

        foreach ($orders as $order) {
            $hourlyCounts[(int)$order->hour] = $order->count;
        }

        return $hourlyCounts;
    }

    private function getDailyOrderCounts($query): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $daysInMonth = $startOfMonth->daysInMonth;
        $dailyCounts = array_fill(0, $daysInMonth, 0);

        $orders = $query
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->get();

        foreach ($orders as $order) {
            $day = Carbon::parse($order->date)->day;
            $dailyCounts[$day - 1] = $order->count;
        }

        return $dailyCounts;
    }

    private function getMonthlyOrderCounts($query): array
    {
        $monthlyCounts = array_fill(0, 12, 0);

        $orders = $query
            ->select(
                DB::raw('EXTRACT(MONTH FROM created_at) as month'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy(DB::raw('EXTRACT(MONTH FROM created_at)'))
            ->orderBy('month')
            ->get();

        foreach ($orders as $order) {
            $monthlyCounts[(int)$order->month - 1] = $order->count;
        }

        return $monthlyCounts;
    }
}
