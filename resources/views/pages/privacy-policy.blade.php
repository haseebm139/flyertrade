<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Privacy Policy - Flyertrade</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
        
        <style>
            body {
                font-family: 'Instrument Sans', sans-serif;
                line-height: 1.6;
                color: #333;
                background-color: #fdfdfc;
            }
            .container {
                max-width: 800px;
                margin: 50px auto;
                padding: 20px;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            }
            h1 {
                font-size: 2.5rem;
                font-weight: 600;
                margin-bottom: 20px;
                color: #1b1b18;
            }
            h2 {
                font-size: 1.5rem;
                font-weight: 600;
                margin-top: 30px;
                margin-bottom: 15px;
                color: #1b1b18;
            }
            p {
                margin-bottom: 15px;
            }
            .back-link {
                display: inline-block;
                margin-bottom: 20px;
                color: #706f6c;
                text-decoration: none;
                font-size: 0.9rem;
            }
            .back-link:hover {
                text-decoration: underline;
            }
            .page-logo {
                display: flex;
                justify-content: center;
                margin-bottom: 20px;
            }
            .page-logo img {
                max-width: 180px;
                width: 100%;
                height: auto;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="page-logo">
                <img src="{{ asset('assets/images/icons/logo.svg') }}" alt="Flyertrade Logo">
            </div>
            {{-- <a href="/" class="back-link">← Back to Home</a> --}}
            <h1>Privacy Policy</h1>
            {{-- <p>Last updated: {{ date('F d, Y') }}</p> --}}

            <h2>1. Introduction</h2>
            <p>Welcome to Flyertrade. We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you about how we look after your personal data when you visit our website or use our application.</p>

            <h2>2. The Data We Collect About You</h2>
            <p>We may collect, use, store and transfer different kinds of personal data about you which we have grouped together as follows:</p>
            <ul>
                <li>Identity Data (name, username, etc.)</li>
                <li>Contact Data (email address, telephone numbers)</li>
                <li>Technical Data (IP address, login data, browser type)</li>
                <li>Usage Data (information about how you use our website/app)</li>
            </ul>

            <h2>3. How We Use Your Personal Data</h2>
            <p>We will only use your personal data when the law allows us to. Most commonly, we will use your personal data in the following circumstances:</p>
            <ul>
                <li>Where we need to perform the contract we are about to enter into or have entered into with you.</li>
                <li>Where it is necessary for our legitimate interests.</li>
                <li>Where we need to comply with a legal obligation.</li>
            </ul>

            <h2>4. Data Security</h2>
            <p>We have put in place appropriate security measures to prevent your personal data from being accidentally lost, used or accessed in an unauthorized way, altered or disclosed.</p>

            <h2>5. Your Legal Rights</h2>
            <p>Under certain circumstances, you have rights under data protection laws in relation to your personal data, including the right to request access, correction, erasure, restriction, transfer, or to object to processing.</p>

            <h2>6. Contact Us</h2>
            <p>If you have any questions about this privacy policy or our privacy practices, please contact us at: support@flyertrade.com</p>
        </div>
    </body>
</html>
