<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Santri | Login</title>

    <link rel="icon" type="image/png" href="{{ asset('media/img/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .glassmorphism {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .form-input::placeholder { color: #d1d5db; }
        @keyframes fadeInSlideUp { from { opacity:0; transform:translateY(20px);} to { opacity:1; transform:translateY(0);} }
        .animate-fadeInSlideUp { animation: fadeInSlideUp 0.7s ease-out forwards; }
        @keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .animate-spin-slow { animation: spin-slow 25s linear infinite; }
    </style>
</head>
<body class="bg-gray-200">

    <div class="relative min-h-screen flex items-center justify-center p-4 bg-gradient-to-br from-sky-500 to-indigo-600 overflow-hidden">

        <div class="absolute -top-10 -left-10 w-72 h-72 bg-indigo-400 rounded-full opacity-20 filter blur-xl animate-spin-slow"></div>
        <div class="absolute -bottom-10 -right-10 w-72 h-72 bg-sky-400 rounded-full opacity-20 filter blur-xl animate-spin-slow" style="animation-delay: 5s;"></div>
        <div class="absolute top-1/2 left-1/2 w-48 h-48 bg-purple-400 rounded-xl opacity-10 filter blur-2xl animate-spin-slow" style="animation-delay: 2s;"></div>

        <div class="absolute top-4 right-4 z-20">
            <a href="{{ route('login') }}" class="text-white text-sm font-medium py-2 px-4 rounded-lg bg-white bg-opacity-20 hover:bg-opacity-30 transition duration-300">
                <i class="fas fa-tachometer-alt mr-2"></i> {{ $setting['school'] ?? config('app.name') }}
            </a>
        </div>

        <div class="w-full max-w-sm z-10">
            <div class="glassmorphism rounded-2xl shadow-xl p-8 animate-fadeInSlideUp">

                <div class="flex justify-center mb-6">
                    @if(!empty($setting['logo']))
                        <img src="{{ asset('uploads/school/'.$setting['logo']) }}" alt="Logo" class="h-20">
                    @else
                        <img src="{{ asset('media/img/logo.png') }}" alt="Logo" class="h-20">
                    @endif
                </div>

                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-white">Portal Santri</h1>
                    <p class="text-gray-200">Silakan masuk dengan akun Anda.</p>
                </div>

                @if(session('failed'))
                    <div class="mb-4 text-center text-sm text-red-100 bg-red-500 bg-opacity-40 rounded-lg py-2 px-3">
                        {{ session('failed') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="mb-4 text-center text-sm text-red-100 bg-red-500 bg-opacity-40 rounded-lg py-2 px-3">
                        @foreach($errors->all() as $e) {{ $e }} @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('portal.doLogin') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" name="redirect" value="{{ request('redirect') }}">

                    <div>
                        <label for="nis" class="sr-only">Nomor Induk Siswa</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-user text-gray-300"></i>
                            </div>
                            <input
                                type="text"
                                id="nis"
                                name="nis"
                                value="{{ old('nis') }}"
                                placeholder="Nomor Induk Siswa (NIS)"
                                required
                                autofocus
                                class="form-input w-full pl-10 pr-4 py-2 bg-white bg-opacity-20 border border-transparent rounded-lg text-white placeholder-gray-300 focus:outline-none focus:bg-opacity-30 focus:ring-2 focus:ring-sky-400 transition duration-300">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="sr-only">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-300"></i>
                            </div>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="Password"
                                required
                                class="form-input w-full pl-10 pr-4 py-2 bg-white bg-opacity-20 border border-transparent rounded-lg text-white placeholder-gray-300 focus:outline-none focus:bg-opacity-30 focus:ring-2 focus:ring-sky-400 transition duration-300">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-sky-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500 focus:ring-opacity-75 transition duration-300 transform hover:scale-105">
                        LOGIN
                    </button>
                </form>
            </div>

            <div class="text-center text-sm text-white mt-8 opacity-75 z-10">
                <p>{{ $setting['school'] ?? config('app.name') }}</p>
            </div>
        </div>

    </div>
</body>
</html>
