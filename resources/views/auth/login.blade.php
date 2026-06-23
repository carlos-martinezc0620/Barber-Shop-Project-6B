<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <style>
        body {
            margin: 0;
            font-family: 'Figtree', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body>

    <div style="display: flex; justify-content: center; align-items: center; min-height: 100vh; width: 100vw; box-sizing: border-box; background-color: #f3f4f6; padding: 20px;">
        
        <div style="width: 900px; height: 550px; max-width: 95%; background-color: #ffffff; border-radius: 12px; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1); display: flex; flex-direction: row; overflow: hidden;"> 
            
            <div style="flex: 1; padding: 50px; display: flex; flex-direction: column; justify-content: center; background-color: #ffffff; box-sizing: border-box;">
                
                <h2 style="font-size: 2rem; font-weight: 600; color: #1e3a8a; margin-top: 0; margin-bottom: 30px; text-align: left;">Bienvenido</h2>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" style="width: 100%;">
                    @csrf

                    <div style="margin-bottom: 24px;">
                        <label for="email" style="display: block; font-size: 0.875rem; color: #1f2937; margin-bottom: 8px; font-weight: 500;">Correo</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                               style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: #eff6ff; color: #1f2937; font-size: 0.95rem; box-sizing: border-box; outline: none;">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div style="margin-bottom: 24px;">
                        <label for="password" style="display: block; font-size: 0.875rem; color: #1f2937; margin-bottom: 8px; font-weight: 500;">Contraseña</label>
                        <input id="password" type="password" name="password" required autocomplete="current-password" 
                               style="width: 100%; padding: 12px 16px; border: 1px solid #d1d5db; border-radius: 0.375rem; background-color: #eff6ff; color: #1f2937; font-size: 0.95rem; box-sizing: border-box; outline: none;">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; font-size: 0.875rem;">
                        <label for="remember_me" style="display: inline-flex; align-items: center; color: #4b5563; cursor: pointer;">
                            <input id="remember_me" type="checkbox" name="remember" style="width: 16px; height: 16px; margin-right: 8px;">
                            <span>Recuérdame</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="color: #1e3a8a; text-decoration: underline; font-weight: 500;">
                                ¿Olvidaste tu contraseña?
                            </a>
                        @endif
                    </div>

                    <button type="submit" style="width: 100%; background-color: #1e293b; color: #ffffff; padding: 14px; border: none; border-radius: 0.375rem; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; cursor: pointer; box-sizing: border-box;">
                        Iniciar Sesión
                    </button>
                </form>
            </div>

            <div style="flex: 1; background-color: #717fa0; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 40px; text-align: center; color: white; box-sizing: border-box;">
                <div class="bg-black rounded-lg p-4 mb-6" style="width: 100%; ">
 <h1 style="font-size: 3rem; font-weight: bold; margin-bottom: 15px; text-shadow: 0 2px 4px rgba(0,0,0,0.15);">Barbershop</h1>
                </div>
               
                <p style="font-size: 1rem; max-width: 80%; opacity: 0.9; text-shadow: 0 1px 2px rgba(0,0,0,0.15);">Ingresa tus datos para acceder al sistema.</p>
                 
            </div>

        </div>
    </div>

</body>
</html>