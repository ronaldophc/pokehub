<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — PokeHub</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: ui-sans-serif, system-ui, sans-serif;
            background: #18181b;
            color: #f4f4f5;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 2rem;
            padding: 2rem;
        }
        .sprite {
            image-rendering: pixelated;
            width: 96px;
            height: 96px;
            object-fit: contain;
            opacity: 0.6;
        }
        .code {
            font-size: 5rem;
            font-weight: 800;
            line-height: 1;
            color: #7c3aed;
            letter-spacing: -0.05em;
        }
        .title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #f4f4f5;
        }
        .message {
            font-size: 0.875rem;
            color: #a1a1aa;
            text-align: center;
            max-width: 360px;
            line-height: 1.6;
        }
        .btn {
            display: inline-block;
            background: #7c3aed;
            color: #fff;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.5rem 1.25rem;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background 0.15s;
        }
        .btn:hover { background: #6d28d9; }
        .logo {
            position: fixed;
            top: 1.5rem;
            left: 1.5rem;
            font-size: 1.125rem;
            font-weight: 700;
            color: #f4f4f5;
            text-decoration: none;
        }
        .logo span { color: #7c3aed; }
    </style>
</head>
<body>
    <a href="/" class="logo">Poke<span>Hub</span></a>

    <img src="@yield('sprite')" class="sprite" alt="" onerror="this.style.display='none'">

    <div style="text-align:center; display:flex; flex-direction:column; align-items:center; gap:0.75rem;">
        <p class="code">@yield('code')</p>
        <p class="title">@yield('title')</p>
        <p class="message">@yield('message')</p>
    </div>

    <a href="@yield('href', '/')" class="btn">@yield('action', 'Voltar ao início')</a>
</body>
</html>
