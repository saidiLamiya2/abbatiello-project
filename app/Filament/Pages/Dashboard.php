<?php

namespace App\Filament\Pages;

use App\Models\User;
use Carbon\Carbon;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';
    protected string $view = 'filament.pages.dashboard';
    protected static ?int $navigationSort = -1;

    public static function getNavigationLabel(): string
    {
        return __('app.nav.dashboard');
    }

    public function getTitle(): string
    {
        return __('app.nav.dashboard');
    }

    public array $calendarDays = [];
    public int $currentYear;
    public int $currentMonth;
    public string $currentMonthName;
    public string $viewMode = 'month'; // month | week | day

    public function mount(): void
    {
        $this->currentYear  = now()->year;
        $this->currentMonth = now()->month;
        $this->buildCalendar();
    }

    public function previousMonth(): void
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentYear  = $date->year;
        $this->currentMonth = $date->month;
        $this->buildCalendar();
    }

    public function nextMonth(): void
    {
        $date = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentYear  = $date->year;
        $this->currentMonth = $date->month;
        $this->buildCalendar();
    }

    public function goToToday(): void
    {
        $this->currentYear  = now()->year;
        $this->currentMonth = now()->month;
        $this->buildCalendar();
    }

    private function buildCalendar(): void
    {
        $this->currentMonthName = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1)
            ->locale('fr')
            ->isoFormat('MMMM YYYY');

        // Fetch all users with a birth_date
        $users = User::whereNotNull('birth_date')
            ->get(['first_name', 'last_name', 'birth_date', 'store_id']);

        // Group birthdays by day-of-month for this month
        $birthdays = [];
        foreach ($users as $user) {
            $bday = Carbon::parse($user->birth_date);
            if ($bday->month === $this->currentMonth) {
                $birthdays[$bday->day][] = [
                    'name'  => $user->first_name . ' ' . $user->last_name,
                    'store' => $user->store_id,
                ];
            }
        }

        // Build day grid
        $firstDay   = Carbon::createFromDate($this->currentYear, $this->currentMonth, 1);
        $daysInMonth = $firstDay->daysInMonth;
        // Sunday = 0 offset
        $startOffset = $firstDay->dayOfWeek; // 0=Sun

        $days = [];
        // Empty cells before first day
        for ($i = 0; $i < $startOffset; $i++) {
            $days[] = ['day' => null, 'birthdays' => [], 'isToday' => false];
        }
        // Actual days
        for ($d = 1; $d <= $daysInMonth; $d++) {
            $isToday = ($d === now()->day && $this->currentMonth === now()->month && $this->currentYear === now()->year);
            $days[] = [
                'day'       => $d,
                'birthdays' => $birthdays[$d] ?? [],
                'isToday'   => $isToday,
            ];
        }

        $this->calendarDays = $days;
    }
}