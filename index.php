<?php
declare(strict_types=1);

/**
 * Nieprzypadkowy Czytelnik
 * Główny punkt wejścia strony WWW.
 *
 * Na tym etapie jest to prosta strona tymczasowa.
 * Docelowa struktura i treści zostaną przygotowane osobno.
 */
?><!doctype html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#10283f">
    <meta name="description" content="Nieprzypadkowy Czytelnik — dobre książki. Świadome wybory.">
    <title>Nieprzypadkowy Czytelnik</title>
    <style>
        :root {
            color-scheme: light;
            --navy: #10283f;
            --cream: #f7f0e3;
            --gold: #d99a2b;
            --ink: #172b40;
        }

        * { box-sizing: border-box; }

        body {
            min-height: 100vh;
            margin: 0;
            display: grid;
            place-items: center;
            padding: 32px 20px;
            background: var(--cream);
            color: var(--ink);
            font-family: Georgia, "Times New Roman", serif;
        }

        main {
            width: min(100%, 760px);
            padding: clamp(32px, 7vw, 72px);
            border: 1px solid rgba(16, 40, 63, .14);
            background: rgba(255, 252, 246, .78);
            text-align: center;
        }

        h1 {
            margin: 0;
            color: var(--navy);
            font-size: clamp(2.2rem, 7vw, 4.6rem);
            line-height: 1.02;
            font-weight: 700;
        }

        .rule {
            width: 112px;
            height: 4px;
            margin: 28px auto;
            border-radius: 99px;
            background: var(--gold);
        }

        .tagline {
            margin: 0;
            font-size: clamp(1.05rem, 2.5vw, 1.4rem);
            letter-spacing: .04em;
        }

        .status {
            margin: 42px 0 0;
            color: #536273;
            font-family: Arial, Helvetica, sans-serif;
            font-size: .95rem;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <main>
        <h1>Nieprzypadkowy<br>Czytelnik</h1>
        <div class="rule" aria-hidden="true"></div>
        <p class="tagline">Dobre książki. Świadome wybory.</p>
        <p class="status">Nasza strona jest w przygotowaniu.<br>Wkrótce zaprosimy Cię do świata ciekawych książek.</p>
    </main>
</body>
</html>
