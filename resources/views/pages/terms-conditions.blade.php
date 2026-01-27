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

            .page-logo {
                display: flex;
                justify-content: left;
                margin-bottom: 20px;
                background: #004E42;
            }
            .page-logo img {
                max-width: 50px;
                width: 100%;
                height: auto;
                padding: 0.5vw;
                margin-left: 0.5vw
            }
            .page-logo span {
                
                font-weight: 500;
                font-size: 1vw;
                line-height: 1.25vw;                 
                color: #ffffff;
                align-self: center;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="page-logo">
                <img src="{{ asset('assets/images/icons/small_logo.svg') }}" alt="Flyertrade Logo">
                <span>FlyerTrader</span>
            </div>
            {{-- <a href="/" class="back-link">← Back to Home</a> --}}
            <h1>Terms and Conditions</h1>
            {{-- <p>Last updated: {{ date('F d, Y') }}</p> --}}

            @php
                $terms = [
                    [
                        'title' => 'Agreement to Terms',
                        'body' => \App\Models\Setting::get('onboarding_terms_agreement', 'By accessing or using Flyertrade, you agree to be bound by these terms and conditions. If you disagree with any part of these terms, you may not access the service.'),
                    ],
                    [
                        'title' => 'Use of Service',
                        'body' => \App\Models\Setting::get('onboarding_terms_use', 'You agree to use our service only for lawful purposes and in accordance with these terms. You are responsible for maintaining the confidentiality of your account and password.'),
                    ],
                    [
                        'title' => 'Intellectual Property',
                        'body' => \App\Models\Setting::get('onboarding_terms_ip', 'The service and its original content, features, and functionality are and will remain the exclusive property of Flyertrade and its licensors.'),
                    ],
                    [
                        'title' => 'Termination',
                        'body' => \App\Models\Setting::get('onboarding_terms_termination', 'We may terminate or suspend your account immediately, without prior notice or liability, for any reason whatsoever, including without limitation if you breach the terms.'),
                    ],
                    [
                        'title' => 'Limitation of Liability',
                        'body' => \App\Models\Setting::get('onboarding_terms_liability', 'In no event shall Flyertrade, nor its directors, employees, partners, agents, suppliers, or affiliates, be liable for any indirect, incidental, special, consequential or punitive damages.'),
                    ],
                    [
                        'title' => 'Governing Law',
                        'body' => \App\Models\Setting::get('onboarding_terms_law', 'These terms shall be governed and construed in accordance with the laws of our operating jurisdiction, without regard to its conflict of law provisions.'),
                    ],
                    [
                        'title' => 'Changes to Terms',
                        'body' => \App\Models\Setting::get('onboarding_terms_changes', "We reserve the right, at our sole discretion, to modify or replace these terms at any time. We will provide at least 30 days' notice prior to any new terms taking effect."),
                    ],
                    [
                        'title' => 'Contact Us',
                        'body' => \App\Models\Setting::get('onboarding_terms_contact', 'If you have any questions about these terms, please contact us at: support@flyertrade.com'),
                    ],
                ];
            @endphp

            @foreach ($terms as $index => $term)
                <h2>{{ $index + 1 }}. {{ $term['title'] }}</h2>
                <p>{!! nl2br(e($term['body'])) !!}</p>
            @endforeach
        </div>
    </body>
</html>
