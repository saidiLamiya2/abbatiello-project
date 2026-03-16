<x-filament-panels::page.simple>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@300;400;500&family=Montserrat:wght@300;400;500;600&display=swap');

        html, body, .fi-body, .fi-simple-layout {
            background: #000 !important;
            overflow: hidden !important;
            height: 100% !important;
        }

        .fi-simple-page {
            background: transparent !important;
            box-shadow: none !important;
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            border-radius: 0 !important;
        }

        .fi-simple-main {
            background: transparent !important;
            box-shadow: none !important;
            width: 100vw !important;
            max-width: 100vw !important;
            padding: 0 !important;
            border-radius: 0 !important;
        }

        /* ── Full screen wrapper ── */
        .abbatiello-login {
            display: flex;
            height: 100vh;
            width: 100vw;
            background: #000;
            font-family: 'Montserrat', sans-serif;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            box-sizing: border-box;
            overflow: hidden;
        }

        /* ── Centered card ── */
        .abbatiello-inner {
            display: flex;
            width: 100%;
            max-width: 1080px;
            min-height: 600px;
            border: 1px solid rgba(255,255,255,0.09);
            border-radius: 4px;
            overflow: hidden;
        }

        /* ── Left panel ── */
        .abl-left {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 4rem 3rem;
            position: relative;
            overflow: hidden;
        }

        .abl-left::after {
            content: '';
            position: absolute;
            right: 0;
            top: 10%;
            bottom: 10%;
            width: 1px;
            background: linear-gradient(to bottom, transparent, rgba(255,255,255,0.15), transparent);
        }

        .abl-brand {
            text-align: center;
            margin-bottom: 4rem;
        }

        .abl-brand-groupe {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            justify-content: center;
            margin-bottom: 0.25rem;
        }

        .abl-brand-groupe::before,
        .abl-brand-groupe::after {
            content: '';
            width: 60px;
            height: 1px;
            background: rgba(255,255,255,0.35);
        }

        .abl-brand-groupe span {
            font-size: 0.6rem;
            letter-spacing: 0.4em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.5);
            font-weight: 400;
        }

        .abl-brand-name {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2.8rem, 4vw, 4.5rem);
            font-weight: 300;
            color: #fff;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            line-height: 1;
            margin: 0;
        }

        .abl-brand-line {
            height: 1px;
            background: rgba(255,255,255,0.35);
            margin-top: 0.6rem;
        }

        /* ── Sub-brand logos grid ── */
        .abl-logos {
            display: grid;
            grid-template-columns: repeat(4, 110px);
            grid-template-rows: repeat(2, 75px);
            gap: 1.5rem 2rem;
        }

        .abl-logo {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0.45;
            transition: opacity 0.35s ease;
        }

        .abl-logo:hover { opacity: 0.85; }

        .abl-logo-name {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #fff;
            text-align: center;
            line-height: 1.2;
        }

        .abl-logo-sub {
            font-size: 0.45rem;
            font-weight: 300;
            letter-spacing: 0.12em;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            margin-top: 0.2rem;
            text-align: center;
        }

        .abl-logo-serif {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.1rem;
            font-weight: 400;
            font-style: italic;
            color: #fff;
            text-transform: none;
            letter-spacing: 0.04em;
        }

        /* ── Right panel ── */
        .abl-right {
            width: 380px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            justify-content: center;
            padding: 3.5rem 2.75rem;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(24px);
            position: relative;
        }

        .abl-right::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        }

        .abl-form-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem;
            font-weight: 400;
            color: #fff;
            letter-spacing: 0.06em;
            text-align: center;
            margin-bottom: 0.3rem;
        }

        .abl-form-sub {
            font-size: 0.6rem;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            text-align: center;
            margin-bottom: 2.5rem;
        }

        /* ── Override Filament form inputs ── */
        .abl-right .fi-fo-field-wrp > label,
        .abl-right label {
            font-family: 'Montserrat', sans-serif !important;
            font-size: 0.6rem !important;
            font-weight: 500 !important;
            letter-spacing: 0.22em !important;
            text-transform: uppercase !important;
            color: rgba(255,255,255,0.45) !important;
        }

        .abl-right input[type="email"],
        .abl-right input[type="password"],
        .abl-right input[type="text"] {
            background: rgba(255,255,255,0.05) !important;
            border: 1px solid rgba(255,255,255,0.1) !important;
            border-radius: 3px !important;
            color: #fff !important;
            font-family: 'Montserrat', sans-serif !important;
            font-size: 0.85rem !important;
            transition: border-color 0.2s !important;
        }

        .abl-right input:focus {
            border-color: rgba(255,255,255,0.35) !important;
            box-shadow: none !important;
            outline: none !important;
            background: rgba(255,255,255,0.07) !important;
        }

        .abl-right input::placeholder {
            color: rgba(255,255,255,0.18) !important;
        }

        .abl-right button[type="submit"],
        .abl-right .fi-btn-color-primary {
            background: #fff !important;
            color: #000 !important;
            font-family: 'Montserrat', sans-serif !important;
            font-size: 0.65rem !important;
            font-weight: 600 !important;
            letter-spacing: 0.28em !important;
            text-transform: uppercase !important;
            border-radius: 3px !important;
            border: none !important;
            width: 100% !important;
            padding: 0.9rem !important;
            cursor: pointer !important;
            transition: opacity 0.2s !important;
            margin-top: 0.5rem !important;
        }

        .abl-right button[type="submit"]:hover {
            opacity: 0.88 !important;
        }

        .abl-right a {
            color: rgba(255,255,255,0.35) !important;
            font-size: 0.65rem !important;
            letter-spacing: 0.08em !important;
            transition: color 0.2s !important;
        }

        .abl-right a:hover {
            color: rgba(255,255,255,0.75) !important;
        }

        .abl-footer {
            margin-top: 2.5rem;
            font-size: 0.55rem;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.18);
            text-align: center;
        }

        @media (max-width: 768px) {
            .abbatiello-login { padding: 0; }
            .abbatiello-inner { border-radius: 0; border: none; min-height: 100vh; }
            .abl-left { display: none; }
            .abl-right { width: 100vw; }
        }

        /* ── Hide Filament default logo and heading ── */
        .fi-simple-header,
        .fi-logo,
        .fi-simple-page-heading,
        [class*="fi-simple-header"],
        [class*="fi-logo"] {
            display: none !important;
        }
    </style>

    <div class="abbatiello-login">
        <div class="abbatiello-inner">

            {{-- ── Left: Groupe Abbatiello identity ── --}}
            <div class="abl-left">
                <div class="abl-brand">
                    <div class="abl-brand-groupe">
                        <span>Groupe</span>
                    </div>
                    <h1 class="abl-brand-name">Abbatiello</h1>
                    <div class="abl-brand-line"></div>
                </div>

                <div class="abl-logos">
                    <div class="abl-logo">
                        <div class="abl-logo-name" style="font-size:0.9rem;">TOPLA!</div>
                        <div class="abl-logo-sub">La boîte à pâtes</div>
                    </div>
                    <div class="abl-logo">
                        <div class="abl-logo-name abl-logo-serif">L'œufrier</div>
                        <div class="abl-logo-sub">déjeuner & dîner</div>
                    </div>
                    <div class="abl-logo">
                        <div class="abl-logo-name" style="line-height:1.1;">CHEZ<br>MAMIE</div>
                    </div>
                    <div class="abl-logo">
                        <div class="abl-logo-name abl-logo-serif" style="font-size:1.3rem;">Salvatoré</div>
                    </div>
                    <div class="abl-logo">
                        <div class="abl-logo-name" style="font-size:0.65rem;">Jack le coq</div>
                        <div class="abl-logo-sub">tendres de poulet</div>
                    </div>
                    <div class="abl-logo">
                        <div class="abl-logo-name" style="font-size:1rem;">BLITZ</div>
                        <div class="abl-logo-sub">franchise</div>
                    </div>
                    <div class="abl-logo">
                        <div class="abl-logo-name abl-logo-serif" style="font-size:1.1rem;">Ubee</div>
                    </div>
                    <div class="abl-logo">
                        <div class="abl-logo-name" style="font-size:0.7rem;">P. ABBAT</div>
                    </div>
                </div>
            </div>

            {{-- ── Right: Login form ── --}}
            <div class="abl-right">
                <h2 class="abl-form-title">Connexion</h2>
                <p class="abl-form-sub">Espace de gestion franchisé</p>

                {{ $this->content }}

                <div class="abl-footer">
                    © {{ date('Y') }} Groupe Abbatiello — Tous droits réservés
                </div>
            </div>

        </div>
    </div>
</x-filament-panels::page.simple>