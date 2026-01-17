<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Terms and Conditions - Flyertrade</title>

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
        </style>
    </head>
    <body>
        <div class="container">
            <a href="/" class="back-link">← Back to Home</a>
            <h1>Terms and Conditions</h1>
            <p>Last updated: {{ date('F d, Y') }}</p>

            <h2>1. Agreement to Terms</h2>
            <p>By accessing or using Flyertrade, you agree to be bound by these terms and conditions. If you disagree with any part of these terms, you may not access the service.</p>

            <h2>2. Use of Service</h2>
            <p>You agree to use our service only for lawful purposes and in accordance with these terms. You are responsible for maintaining the confidentiality of your account and password.</p>

            <h2>3. Intellectual Property</h2>
            <p>The service and its original content, features, and functionality are and will remain the exclusive property of Flyertrade and its licensors.</p>

            <h2>4. Termination</h2>
            <p>We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the terms.</p>

            <h2>5. Limitation of Liability</h2>
            <p>In no event shall Flyertrade, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential or punitive damages.</p>

            <h2>6. Governing Law</h2>
            <p>These terms shall be governed and construed in accordance with the laws of our operating jurisdiction, without regard to its conflict of law provisions.</p>

            <h2>7. Changes to Terms</h2>
            <p>We reserve the right, at our sole discretion, to modify or replace these terms at any time. We will provide at least 30 days' notice prior to any new terms taking effect.</p>

            <h2>8. Contact Us</h2>
            <p>If you have any questions about these terms, please contact us at: support@flyertrade.com</p>
        </div>
    </body>
</html>
