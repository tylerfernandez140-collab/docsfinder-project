<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Document Finder</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Spin animation */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .spin-slow {
            animation: spin 5s linear infinite;
        }

        /* Pulse animation for text */
        @keyframes pulse-text {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .pulse-text {
            animation: pulse-text 1.5s ease-in-out infinite;
        }

        /* Loading overlay */
        #loadingOverlay {
            position: fixed;
            inset: 0;
            background-color: rgba(255,255,255,0.9);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 50;
            display: none; /* hidden by default */
        }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

<!-- Loading Overlay -->
<div id="loadingOverlay">
    <img src="{{ asset('images/psu_logo.png') }}" alt="PSU Logo" class="w-24 h-24 spin-slow">
 
</div>

<div class="w-full max-w-md space-y-4 px-4">

    <!-- Logo + Title -->
    <div class="text-center">
        <img src="{{ asset('images/AdminLTELogo.png') }}" alt="Logo" class="mx-auto w-14 h-14 mb-2">
        <h1 class="text-xl font-bold text-gray-800 leading-tight">Document Finder</h1>
        <p class="text-sm text-gray-600">University Quality Assurance Office</p>
        <p class="text-xs text-gray-500">ISO-Compliant Document Control System</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white rounded-lg shadow p-6">

        <h2 class="text-md font-semibold text-center text-gray-700 mb-1">Sign In</h2>
        <p class="text-xs text-center text-gray-500 mb-4">Enter your Email and password to access the system</p>

        {{-- Display Errors --}}
        @if ($errors->any())
            <div class="mb-3 p-2 text-xs text-red-700 bg-red-100 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="text" name="email" value="{{ old('email') }}"
                    class="w-full px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Enter your Email" required>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" name="password"
                    class="w-full px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Enter your password" required>
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-blue-900 text-white py-2 rounded-md text-sm hover:bg-blue-800 transition">
                Sign In
            </button>

            <!-- Forgot Password -->
            <div class="text-center mt-2">
                <a href="{{ route('password.request') }}" class="text-sm text-blue-700 hover:underline">Forgot Password?</a>
            </div>
        </form>
    </div>

    <!-- Notice -->
    <div class="bg-white rounded-lg shadow p-3 text-xs text-gray-600">
        <p class="flex items-center">
            <span class="mr-1 text-red-600">⚠️</span>
            Access is restricted to authorized personnel only. All activities are monitored and logged for security purposes.
        </p>
    </div>

    <!-- Footer -->
    <div class="text-center text-[10px] text-gray-400">
        ISO 9001:2015 Compliant Document Management System <br>
        © Pangasinan State University - Quality Assurance Office
    </div>
</div>

<script>
    // Show loading overlay when the form is submitted
    document.getElementById('loginForm').addEventListener('submit', function() {
        document.getElementById('loadingOverlay').style.display = 'flex';
    });
</script>

</body>
</html>
