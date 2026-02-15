@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', 'Inicia sesión para comenzar')

@section('auth_body')
    <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="input-group mb-3">
            <input type="email" name="email" class="form-control" placeholder="Email" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
        </div>

        <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col-7">
                <div class="icheck-primary">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember" class="text-muted small">Recordarme</label>
                </div>
            </div>
            <div class="col-5">
                <button type="submit" class="btn btn-gold btn-block shadow">
                    <i class="fas fa-sign-in-alt mr-1"></i> Acceder
                </button>
            </div>
        </div>
    </form>
@stop

@section('auth_footer')
    <p class="my-2 text-center">
        <a href="{{ route('password.request') }}" class="text-gold-link small font-weight-bold">Olvidé mi contraseña</a>
    </p>
    <p class="mb-0 text-center">
        <a href="{{ route('register') }}" class="text-gold-link small font-weight-bold">Crear una nueva cuenta</a>
    </p>
@stop

@section('css')
<style>
    /* 1. Fondo de Lobby de Lujo */
    body.login-page {
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.3)), 
                    url('https://images.unsplash.com/photo-1629909613654-28e377c37b09?q=80&w=2070&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
    }

    /* 2. Títulos en Dorado Metálico */
    .login-logo a {
        color: #d4af37 !important; /* Dorado base */
        background: linear-gradient(to bottom, #cf9d31 22%, #ffecb3 45%, #d4af37 50%, #aa822d 75%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800 !important;
        text-transform: uppercase;
        filter: drop-shadow(2px 2px 2px rgba(0,0,0,0.5));
        letter-spacing: 2px;
    }

    /* 3. Tarjeta Elegante */
    .login-box .card {
        border: none !important;
        border-radius: 20px !important;
        background: rgba(255, 255, 255, 0.95) !important;
        box-shadow: 0 20px 40px rgba(0,0,0,0.4) !important;
        border-top: 5px solid #d4af37 !important; /* Línea superior dorada */
    }

    /* Detalle lateral esmeralda (opcional, para mantener el toque médico) */
    .login-box .card::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 6px;
        background: #20c997;
        border-radius: 20px 0 0 20px;
    }

    /* 4. Botón con Estilo Dorado */
    .btn-gold {
        background: linear-gradient(135deg, #d4af37 0%, #aa822d 100%) !important;
        border: none !important;
        color: white !important;
        border-radius: 10px !important;
        font-weight: bold;
        text-transform: uppercase;
        transition: 0.3s;
    }

    .btn-gold:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4) !important;
        filter: brightness(1.1);
    }

    /* 5. Inputs y Enlaces */
    .form-control {
        border-radius: 10px !important;
        border: 1px solid #e0e0e0;
    }

    .text-gold-link {
        color: #aa822d !important;
    }

    .text-gold-link:hover {
        color: #d4af37 !important;
        text-decoration: underline !important;
    }

    .login-box .card-header {
        color: #444 !important;
        font-weight: bold;
        font-size: 1.2rem;
    }
</style>
@stop