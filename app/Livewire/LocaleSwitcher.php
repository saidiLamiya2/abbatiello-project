<?php

namespace App\Livewire;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LocaleSwitcher extends Component
{
    public string $locale;

    public function mount(): void
    {
        $this->locale = Auth::user()->locale ?? config('app.locale', 'fr');
    }

    public function switchLocale(string $locale): void
    {
        if (!in_array($locale, ['fr', 'en'])) {
            return;
        }

        Auth::user()->update(['locale' => $locale]);
        $this->locale = $locale;
        App::setLocale($locale);

        $this->redirect(request()->header('Referer') ?? '/admin');
    }

    public function render()
    {
        return view('livewire.locale-switcher');
    }
}