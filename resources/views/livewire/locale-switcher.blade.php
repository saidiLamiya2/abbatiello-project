<div style="display: inline-flex; align-items: center; gap: 0.25rem; margin-right: 0.5rem;">
    <button
        wire:click="switchLocale('fr')"
        style="
            padding: 0.35rem 0.65rem;
            border-radius: 5px;
            border: 1px solid {{ $locale === 'fr' ? 'color-mix(in srgb, currentColor 60%, transparent)' : 'color-mix(in srgb, currentColor 20%, transparent)' }};
            background: {{ $locale === 'fr' ? 'color-mix(in srgb, currentColor 15%, transparent)' : 'transparent' }};
            color: inherit;
            opacity: {{ $locale === 'fr' ? '1' : '0.45' }};
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            cursor: pointer;
            font-family: Montserrat, sans-serif;
            transition: all 0.2s;
        "
    >FR</button>

    <button
        wire:click="switchLocale('en')"
        style="
            padding: 0.35rem 0.65rem;
            border-radius: 5px;
            border: 1px solid {{ $locale === 'en' ? 'color-mix(in srgb, currentColor 60%, transparent)' : 'color-mix(in srgb, currentColor 20%, transparent)' }};
            background: {{ $locale === 'en' ? 'color-mix(in srgb, currentColor 15%, transparent)' : 'transparent' }};
            color: inherit;
            opacity: {{ $locale === 'en' ? '1' : '0.45' }};
            font-size: 0.65rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            cursor: pointer;
            font-family: Montserrat, sans-serif;
            transition: all 0.2s;
        "
    >EN</button>
</div>