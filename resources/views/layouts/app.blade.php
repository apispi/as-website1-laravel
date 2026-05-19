<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ApiSpi - Build & Deploy AI Agents')</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-9NX96RC3FF"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', 'G-9NX96RC3FF');
    </script>
    @stack('head')
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-wrapper">
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <svg class="logo-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 27"><defs><linearGradient id="lmg" x1=".5" y1="0" x2=".5" y2="1"><stop offset="0%" stop-color="#FCD34D"/><stop offset="100%" stop-color="#D97706"/></linearGradient></defs><path d="M12,0.5 L13.4,3.3 L16,4.5 L13.4,5.7 L12,8.5 L10.6,5.7 L8,4.5 L10.6,3.3 Z" fill="url(#lmg)"/><path d="M12,8.5 L24,26 L20,26 L15.5,18 L8.5,18 L4,26 L0,26 Z" fill="url(#lmg)"/></svg>
                        <span class="logo-text">ApiSpi</span>
                    </a>
                </div>
                <ul class="nav-menu">
                    <li><a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a></li>
                    <li><a href="{{ route('agents.index') }}" @class(['active' => request()->routeIs('agents.*')])>Agents</a></li>
                    <li><a href="{{ route('blog.index') }}" @class(['active' => request()->routeIs('blog.*')])>News</a></li>
                    <li><a href="{{ route('training') }}" @class(['active' => request()->routeIs('training')])>Training</a></li>
                    <li><a href="{{ route('about') }}" @class(['active' => request()->routeIs('about')])>About</a></li>
                    <li><a href="{{ route('contact') }}" @class(['active' => request()->routeIs('contact')])>Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-section">
                    <h4>ApiSpi</h4>
                    <p>The premier platform for deploying and managing AI agents.</p>
                </div>
                <div class="footer-section">
                    <h4>Product</h4>
                    <ul>
                        <li><a href="{{ route('agents.index') }}">Agents</a></li>
                        <li><a href="{{ route('blog.index') }}">News</a></li>
                        <li><a href="{{ route('training') }}">Training</a></li>
                        <li><a href="#">Pricing</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="{{ route('about') }}">About</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="#">Privacy</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Connect</h4>
                    <ul>
                        <li><a href="#">Twitter</a></li>
                        <li><a href="#">GitHub</a></li>
                        <li><a href="#">Discord</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                @yield('footer-extra')
                <p>&copy; 2026 ApiSpi. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="{{ asset('js/pixel-icons.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/chatbot.js') }}"></script>
    @stack('scripts')
</body>
</html>
