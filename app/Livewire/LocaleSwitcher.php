<?php

namespace App\Livewire;

use App\Services\LocaleService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LocaleSwitcher extends Component
{
    public string $locale;

    public function mount(LocaleService $localeService): void
    {
        $this->locale = $localeService->resolveFor(Auth::user());
    }

    public function switchLocale(string $locale, LocaleService $localeService): void
    {
        $localeService->switchFor(Auth::user(), $locale);

        $this->locale = $locale;

        $this->redirect(request()->header('Referer') ?? '/admin');
    }

    public function render()
    {
        return view('livewire.locale-switcher');
    }
}