<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MyInformations extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-circle';
    protected string $view = 'filament.pages.my-informations';
    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationLabel(): string
    {
        return __('app.my_info.title');
    }

    public function getTitle(): string
    {
        return __('app.my_info.title');
    }

    // Form state
    public string $first_name   = '';
    public string $last_name    = '';
    public string $email        = '';
    public ?string $phone       = null;
    public ?string $birth_date  = null;
    public string $current_password  = '';
    public string $new_password      = '';
    public string $new_password_confirmation = '';

    public function mount(): void
    {
        $user = Auth::user();
        $this->first_name  = $user->first_name;
        $user->last_name   ?? null;
        $this->last_name   = $user->last_name ?? '';
        $this->email       = $user->email;
        $this->phone       = $user->phone ?? null;
        $this->birth_date  = $user->birth_date
            ? \Carbon\Carbon::parse($user->birth_date)->format('Y-m-d')
            : null;
    }

    public function saveProfile(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name'  => ['required', 'string', 'max:100'],
            'email'      => ['required', 'email', 'unique:users,email,' . $user->id],
            'phone'      => ['nullable', 'string', 'max:30'],
            'birth_date' => ['nullable', 'date'],
        ]);

        $user->update($validated);

        Notification::make()
            ->title(__('app.my_info.profile_saved'))
            ->success()
            ->send();
    }

    public function savePassword(): void
    {
        $this->validate([
            'current_password'          => ['required', 'current_password'],
            'new_password'              => ['required', Password::defaults(), 'confirmed'],
            'new_password_confirmation' => ['required'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->current_password = '';
        $this->new_password     = '';
        $this->new_password_confirmation = '';

        Notification::make()
            ->title(__('app.my_info.password_saved'))
            ->success()
            ->send();
    }
}