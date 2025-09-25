<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - Document Finder</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Tailwind CSS (CDN) --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen">

<div class="w-full max-w-md space-y-4 px-4">

    <!-- Logo + Title -->
    <div class="text-center">
        <img src="{{ asset('images/AdminLTELogo.png') }}" alt="Logo" class="mx-auto w-14 h-14 mb-2">
        <h1 class="text-xl font-bold text-gray-800 leading-tight">Document Finder</h1>
        <p class="text-sm text-gray-600">University Quality Assurance Office</p>
        <p class="text-xs text-gray-500">ISO-Compliant Document Control System</p>
    </div>

    <!-- Forgot Password Card -->
    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-md font-semibold text-center text-gray-700 mb-1">Forgot Password</h2>
        <p class="text-xs text-center text-gray-500 mb-4">Enter your email to receive a reset link</p>

        @if (session('status'))
            <div class="mb-3 p-2 text-xs text-green-700 bg-green-100 rounded">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" 
                    class="w-full px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    placeholder="Enter your email" required autofocus>
                @error('email')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" class="w-full bg-blue-900 text-white py-2 rounded-md text-sm hover:bg-blue-800 transition">
                Send Reset Link
            </button>

            <!-- Back to Login -->
            <div class="text-center mt-2">
                <a href="{{ route('login') }}" class="text-sm text-blue-700 hover:underline">Back to Login</a>
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

</body>
</html>
