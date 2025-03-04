<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bukidnon State University - Student Portal</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

            <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-image: url('{{ asset("img/Bukidnon State University.jpg") }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            margin: 0;
            position: relative;
            
        }

        body::before {
            content: '';
            position: absolute;
            top: 1;
            right: 0;
            bottom: 0;
            left: 0;
            background: rgba(255, 255, 255, 0.85); /* Changed to white overlay */
            z-index: 1;
        }

        .container {
            position: relative;
            z-index: 2;
        }

        .welcome-container {
            padding: 1.5rem;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 2rem auto;
        }

        .logo {
            max-width: 100px;
            margin-bottom: 1rem;
        }

        .btn-custom {
            background-color: #006838; /* BukSU Green */
            color: white;
            padding: 8px 20px;
            border-radius: 25px;
            transition: all 0.3s ease;
            border: 2px solid #006838;
            margin: 5px;
            font-size: 0.9rem;
        }

        .btn-custom:hover {
            background-color: white;
            color: #006838;
            transform: translateY(-2px);
        }

        .header-text {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #006838; /* BukSU Green */
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .sub-text {
            font-size: 1rem;
            margin-bottom: 1rem;
            color: #333;
        }

        .features-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 0.8rem;
            margin-top: 1rem;
        }

        .feature-card {
            background-color: white;
            padding: 0.8rem;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0, 104, 56, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .feature-card h3 {
            color: #006838;
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }

        .feature-card p {
            color: #666;
            font-size: 0.8rem;
            margin-bottom: 0;
            line-height: 1.2;
        }

        .feature-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #006838;
        }

        @media (max-width: 768px) {
            .welcome-container {
                margin: 1rem auto;
                padding: 1rem;
                max-width: 95%;
            }

            .features-container {
                grid-template-columns: repeat(3, 1fr);
            }

            .header-text {
                font-size: 1.5rem;
            }

            .feature-card {
                padding: 0.6rem;
            }
        }

        @media (max-width: 480px) {
            .features-container {
                grid-template-columns: repeat(1, 1fr);
            }
        }
            </style>
    </head>
<body>
    <div class="container">
        <div class="welcome-container text-center">
            <img src="{{ asset('img/buksu-logo.png') }}" alt="BukSU Logo" class="logo">
            <h1 class="header-text">Welcome to BukSU Student Portal</h1>
            <p class="sub-text">Empowering Education Through Technology</p>

            @if (Route::has('login'))
                <div class="buttons-container">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-custom">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-custom">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-custom">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="features-container">
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h3>Online Enrollment</h3>
                    <p>Easily manage your course enrollments</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📊</div>
                    <h3>Grade Monitoring</h3>
                    <p>Track your academic progress</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📅</div>
                    <h3>Class Schedule</h3>
                    <p>View your daily class schedule</p>
                </div>
            </div>
        </div>
        </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
