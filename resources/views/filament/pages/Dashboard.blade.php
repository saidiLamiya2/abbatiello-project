<x-filament-panels::page>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=Montserrat:wght@300;400;500;600&display=swap');

        /* ── Action cards ── */
        .dash-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .dash-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 1rem;
            padding: 2.5rem 1.5rem;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.2s ease, opacity 0.2s ease;
            min-height: 160px;
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            font-weight: 500;
            letter-spacing: 0.03em;
            text-align: center;
            border: none;
            background: none;
            width: 100%;
        }

        .dash-card:hover {
            transform: translateY(-3px);
            opacity: 0.92;
        }

        .dash-card-icon {
            width: 52px;
            height: 52px;
            opacity: 0.95;
        }

        .dash-card--info       { background: #F97316; }
        .dash-card--harassment { background: #111; }
        .dash-card--holidays   { background: #22C55E; }

        /* ── Calendar ── */
        .dash-cal {
            background: #fff;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
            font-family: 'Montserrat', sans-serif;
        }

        .dash-cal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .dash-cal-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .dash-cal-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #fff;
            cursor: pointer;
            font-size: 0.8rem;
            color: #374151;
            transition: background 0.15s;
        }

        .dash-cal-btn:hover { background: #f3f4f6; }

        .dash-cal-today-btn {
            padding: 0 0.85rem;
            height: 32px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background: #fff;
            cursor: pointer;
            font-size: 0.75rem;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            color: #374151;
            transition: background 0.15s;
        }

        .dash-cal-today-btn:hover { background: #f3f4f6; }

        .dash-cal-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.6rem;
            font-weight: 400;
            color: #111;
            letter-spacing: 0.03em;
        }

        .dash-cal-view-btns {
            display: flex;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            overflow: hidden;
        }

        .dash-cal-view-btn {
            padding: 0.3rem 0.85rem;
            font-size: 0.72rem;
            font-family: 'Montserrat', sans-serif;
            font-weight: 500;
            background: #fff;
            border: none;
            cursor: pointer;
            color: #6b7280;
            border-right: 1px solid #d1d5db;
            transition: background 0.15s, color 0.15s;
        }

        .dash-cal-view-btn:last-child { border-right: none; }
        .dash-cal-view-btn.active {
            background: #f3f4f6;
            color: #111;
        }

        /* Grid */
        .dash-cal-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
        }

        .dash-cal-dow {
            padding: 0.6rem 0.5rem;
            text-align: center;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            color: #6b7280;
            border-bottom: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .dash-cal-cell {
            min-height: 110px;
            border-right: 1px solid #e5e7eb;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.4rem;
            vertical-align: top;
        }

        .dash-cal-cell:nth-child(7n) { border-right: none; }

        .dash-cal-cell--empty {
            background: #fafafa;
        }

        .dash-cal-cell--today .dash-cal-day-num {
            background: #111;
            color: #fff;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dash-cal-day-num {
            font-size: 0.78rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.3rem;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dash-bday {
            font-size: 0.65rem;
            font-weight: 500;
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
            margin-bottom: 0.2rem;
            line-height: 1.3;
            cursor: default;
        }

        .dash-bday--0 { background: #93c5fd; color: #1e3a5f; }
        .dash-bday--1 { background: #fbbf24; color: #78350f; }
        .dash-bday--2 { background: #6ee7b7; color: #064e3b; }
        .dash-bday--3 { background: #f9a8d4; color: #831843; }
        .dash-bday--4 { background: #a5b4fc; color: #312e81; }

        @media (max-width: 768px) {
            .dash-cards { grid-template-columns: 1fr; }
            .dash-cal-title { font-size: 1.1rem; }
            .dash-cal-cell { min-height: 70px; }
        }
    </style>

    {{-- ── 3 Action Cards ── --}}
    <div class="dash-cards">

        {{-- {{ __('app.dashboard.my_informations') }} --}}
        <a href="{{ route('filament.admin.pages.my-informations') }}" class="dash-card dash-card--info">
            <svg class="dash-card-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="12" cy="8" r="4" fill="white"/>
                <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7" stroke="white" stroke-width="2" stroke-linecap="round"/>
                <circle cx="18" cy="18" r="4" fill="white" opacity="0.9"/>
                <path d="M18 16v2l1 1" stroke="#F97316" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            {{ __('app.dashboard.my_informations') }}
        </a>

        {{-- {{ __('app.dashboard.harassment') }} — triggers PDF download --}}
        <a href="{{ asset(app()->getLocale() === 'en' ? 'documents/harassment-policy.pdf' : 'documents/harassment-policy-fr.pdf') }}" download class="dash-card dash-card--harassment">
            <svg class="dash-card-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="4" y="3" width="16" height="18" rx="2" stroke="white" stroke-width="1.8"/>
                <line x1="8" y1="8" x2="16" y2="8" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                <line x1="8" y1="12" x2="16" y2="12" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                <line x1="8" y1="16" x2="13" y2="16" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
            </svg>
            {{ __('app.dashboard.harassment') }}
        </a>

        {{-- {{ __('app.dashboard.holidays') }} — external link --}}
        <a href="https://forms.monday.com/forms/" target="_blank" rel="noopener" class="dash-card dash-card--holidays">
            <svg class="dash-card-icon" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="3" y="4" width="18" height="18" rx="2" stroke="white" stroke-width="1.8"/>
                <line x1="3" y1="9" x2="21" y2="9" stroke="white" stroke-width="1.8"/>
                <line x1="8" y1="2" x2="8" y2="6" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                <line x1="16" y1="2" x2="16" y2="6" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                <rect x="7" y="13" width="3" height="3" rx="0.5" fill="white"/>
                <rect x="14" y="13" width="3" height="3" rx="0.5" fill="white"/>
            </svg>
            {{ __('app.dashboard.holidays') }}
        </a>

    </div>

    {{-- ── Birthday Calendar ── --}}
    <div class="dash-cal">

        {{-- Header --}}
        <div class="dash-cal-header">
            <div class="dash-cal-nav">
                <button class="dash-cal-btn" wire:click="previousMonth">&#8249;</button>
                <button class="dash-cal-btn" wire:click="nextMonth">&#8250;</button>
                <button class="dash-cal-today-btn" wire:click="goToToday">{{ __('app.dashboard.today') }}</button>
            </div>

            <div class="dash-cal-title">{{ ucfirst($currentMonthName) }}</div>

            <div class="dash-cal-view-btns">
                <button class="dash-cal-view-btn active">{{ __('app.dashboard.month') }}</button>
            </div>
        </div>

        {{-- Day-of-week headers --}}
        <div class="dash-cal-grid">
            @foreach(__('app.dashboard.days') as $dow)
                <div class="dash-cal-dow">{{ $dow }}</div>
            @endforeach

            {{-- Day cells --}}
            @foreach($calendarDays as $cell)
                <div class="dash-cal-cell {{ is_null($cell['day']) ? 'dash-cal-cell--empty' : '' }} {{ $cell['isToday'] ? 'dash-cal-cell--today' : '' }}">
                    @if($cell['day'])
                        <div class="dash-cal-day-num">{{ $cell['day'] }}</div>
                        @foreach($cell['birthdays'] as $i => $bday)
                            <div class="dash-bday dash-bday--{{ $i % 5 }}">
                                🎂 {{ $bday['name'] }}
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach
        </div>

    </div>

</x-filament-panels::page>