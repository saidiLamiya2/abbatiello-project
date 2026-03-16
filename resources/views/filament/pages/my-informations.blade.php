<x-filament-panels::page>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=Montserrat:wght@300;400;500;600&display=swap');

        .mi-wrap {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            font-family: 'Montserrat', sans-serif;
        }

        .mi-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .mi-card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .mi-card-header-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f4f6;
            flex-shrink: 0;
        }

        .mi-card-header-icon svg {
            width: 18px;
            height: 18px;
            color: #374151;
        }

        .mi-card-title {
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: #111;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .mi-card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .mi-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .mi-field {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
        }

        .mi-field label {
            font-size: 0.6rem;
            font-weight: 600;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #9ca3af;
        }

        .mi-field input {
            padding: 0.6rem 0.85rem;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.85rem;
            color: #111;
            background: #fff;
            transition: border-color 0.2s;
            outline: none;
            width: 100%;
            box-sizing: border-box;
        }

        .mi-field input:focus {
            border-color: #6b7280;
        }

        /* Placeholder disappears on focus */
        .mi-field input::placeholder {
            color: #d1d5db;
            transition: opacity 0.2s;
        }

        .mi-field input:focus::placeholder {
            opacity: 0;
        }

        /* Date picker styling */
        .mi-field input[type="date"] {
            cursor: pointer;
            color: #111;
        }

        .mi-field input[type="date"]::-webkit-calendar-picker-indicator {
            cursor: pointer;
            opacity: 0.6;
        }

        .mi-field input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
        }

        .mi-field input.mi-field-readonly {
            background: #f9fafb;
            color: #6b7280;
            cursor: default;
        }

        .mi-error {
            font-size: 0.65rem;
            color: #ef4444;
            margin-top: 0.15rem;
        }

        .mi-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.65rem 1.5rem;
            background: #111;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-family: 'Montserrat', sans-serif;
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            cursor: pointer;
            transition: opacity 0.2s;
            align-self: flex-start;
            margin-top: 0.5rem;
        }

        .mi-btn:hover { opacity: 0.8; }

        .mi-avatar {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .mi-avatar-circle {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.4rem;
            font-weight: 400;
            color: #fff;
            flex-shrink: 0;
        }

        .mi-avatar-name {
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            color: #111;
        }

        .mi-avatar-email {
            font-size: 0.72rem;
            color: #9ca3af;
            margin-top: 0.1rem;
        }

        @media (max-width: 768px) {
            .mi-wrap { grid-template-columns: 1fr; }
            .mi-row  { grid-template-columns: 1fr; }
        }
    </style>

    <div class="mi-wrap">

        {{-- ── Card 1: Personal info ── --}}
        <div class="mi-card">
            <div class="mi-card-header">
                <div class="mi-card-header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                </div>
                <div class="mi-card-title">{{ __('app.my_info.personal') }}</div>
            </div>

            <div class="mi-card-body">

                {{-- Avatar --}}
                <div class="mi-avatar">
                    <div class="mi-avatar-circle">
                        {{ strtoupper(substr($first_name, 0, 1)) }}{{ strtoupper(substr($last_name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="mi-avatar-name">{{ $first_name }} {{ $last_name }}</div>
                        <div class="mi-avatar-email">{{ $email }}</div>
                    </div>
                </div>

                <div class="mi-row">
                    <div class="mi-field">
                        <label>{{ __('app.my_info.first_name') }}</label>
                        <input type="text" wire:model="first_name" placeholder="{{ __('app.my_info.first_name') }}">
                        @error('first_name') <span class="mi-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mi-field">
                        <label>{{ __('app.my_info.last_name') }}</label>
                        <input type="text" wire:model="last_name" placeholder="{{ __('app.my_info.last_name') }}">
                        @error('last_name') <span class="mi-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mi-field">
                    <label>{{ __('app.my_info.email') }}</label>
                    <input type="email" wire:model="email" placeholder="courriel@exemple.com">
                    @error('email') <span class="mi-error">{{ $message }}</span> @enderror
                </div>

                <div class="mi-row">
                    <div class="mi-field">
                        <label>{{ __('app.my_info.phone') }}</label>
                        <input type="text" wire:model="phone" placeholder="Ex: 418-555-0100">
                        @error('phone') <span class="mi-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="mi-field">
                        <label>{{ __('app.my_info.birth_date') }}</label>
                        <input type="date" wire:model="birth_date">
                        @error('birth_date') <span class="mi-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <button class="mi-btn" wire:click="saveProfile" wire:loading.attr="disabled">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Enregistrer
                </button>

            </div>
        </div>

        {{-- ── Card 2: Change password ── --}}
        <div class="mi-card">
            <div class="mi-card-header">
                <div class="mi-card-header-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                </div>
                <div class="mi-card-title">{{ __('app.my_info.password_section') }}</div>
            </div>

            <div class="mi-card-body">

                <div class="mi-field">
                    <label>{{ __('app.my_info.current_password') }}</label>
                    <input type="password" wire:model="current_password" placeholder="••••••••">
                    @error('current_password') <span class="mi-error">{{ $message }}</span> @enderror
                </div>

                <div class="mi-field">
                    <label>{{ __('app.my_info.new_password') }}</label>
                    <input type="password" wire:model="new_password" placeholder="••••••••">
                    @error('new_password') <span class="mi-error">{{ $message }}</span> @enderror
                </div>

                <div class="mi-field">
                    <label>{{ __('app.my_info.confirm_password') }}</label>
                    <input type="password" wire:model="new_password_confirmation" placeholder="••••••••">
                    @error('new_password_confirmation') <span class="mi-error">{{ $message }}</span> @enderror
                </div>

                <button class="mi-btn" wire:click="savePassword" wire:loading.attr="disabled">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    Mettre à jour
                </button>

            </div>
        </div>

    </div>

</x-filament-panels::page>