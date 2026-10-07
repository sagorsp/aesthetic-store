@extends('layouts.app')
@section('title','Account')

@section('content')
<style>
  :root{
    --as-red:#B91C2C;
    --as-black:#111111;
    --as-gold:#C9A227;
    --as-soft:#f6f6f4;
    --as-grad-start:#ff5a2f;
    --as-grad-mid:#ff4a5e;
    --as-grad-end:#ff3f86;
  }

  .auth-page{
    min-height:calc(100vh - 160px);
    display:grid;
    place-items:center;
    padding:36px 16px 54px;
    background:
      radial-gradient(circle at 15% 15%, rgba(185,28,44,.06), transparent 28%),
      linear-gradient(180deg,#fafafa,#f4f3ef);
  }

  .auth-shell{
    position:relative;
    width:min(980px,100%);
    min-height:590px;
    overflow:hidden;
    border-radius:30px;
    background:#fff;
    border:1px solid #ebe8df;
    box-shadow:0 26px 70px rgba(17,17,17,.12);
  }

  .auth-form-side{
    position:absolute;
    top:0;
    width:50%;
    height:100%;
    padding:52px 54px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    transition:transform .65s cubic-bezier(.77,0,.18,1), opacity .35s ease;
    z-index:2;
    background:#fff;
  }

  .login-side{left:0;}
  .register-side{
    left:50%;
    opacity:0;
    pointer-events:none;
    transform:translateX(16%);
  }

  .auth-shell.register-mode .login-side{
    opacity:0;
    pointer-events:none;
    transform:translateX(-16%);
  }

  .auth-shell.register-mode .register-side{
    opacity:1;
    pointer-events:auto;
    transform:translateX(0);
  }

  .auth-overlay{
    position:absolute;
    top:0;
    left:50%;
    width:50%;
    height:100%;
    background:
      radial-gradient(circle at 78% 18%, rgba(255,255,255,.18), transparent 18%),
      radial-gradient(circle at 20% 82%, rgba(255,255,255,.10), transparent 20%),
      linear-gradient(135deg,var(--as-grad-start) 0%, var(--as-grad-mid) 52%, var(--as-grad-end) 100%);
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:48px;
    z-index:4;
    transition:transform .65s cubic-bezier(.77,0,.18,1);
  }

  .auth-shell.register-mode .auth-overlay{
    transform:translateX(-100%);
  }

  .overlay-inner{
    width:100%;
    max-width:340px;
    text-align:center;
  }

  .overlay-kicker{
    font-size:11px;
    text-transform:uppercase;
    letter-spacing:.22em;
    font-weight:800;
    opacity:.82;
    margin-bottom:14px;
  }

  .overlay-title{
    font-size:42px;
    line-height:1.05;
    font-weight:900;
    letter-spacing:-.03em;
  }

  .overlay-text{
    margin:18px auto 26px;
    max-width:290px;
    line-height:1.7;
    color:rgba(255,255,255,.88);
  }

  .overlay-btn{
    min-width:160px;
    height:48px;
    border:2px solid rgba(255,255,255,.95);
    border-radius:999px;
    color:#fff;
    background:transparent;
    font-weight:900;
    letter-spacing:.04em;
    transition:.2s ease;
  }

  .overlay-btn:hover{
    background:#fff;
    color:var(--as-grad-mid);
  }

  .brand-mini{
    display:flex;
    align-items:center;
    gap:10px;
    margin-bottom:26px;
  }

  .brand-mark{
    width:38px;
    height:38px;
    border-radius:12px;
    background:#111;
    color:var(--as-gold);
    display:grid;
    place-items:center;
    font-weight:900;
  }

  .auth-title{
    font-size:34px;
    line-height:1.1;
    font-weight:900;
    letter-spacing:-.025em;
    color:var(--as-black);
  }

  .auth-sub{
    color:rgba(17,17,17,.48);
    margin-top:9px;
    font-size:14px;
  }

  .auth-fields{
    margin-top:26px;
    display:grid;
    gap:15px;
  }

  .field-wrap{position:relative;}
  .field-label{
    display:block;
    font-size:12px;
    font-weight:800;
    margin-bottom:7px;
    color:rgba(17,17,17,.72);
  }

  .auth-input{
    width:100%;
    height:50px;
    border-radius:14px;
    border:1px solid #e3e1db;
    background:#f7f7f5;
    padding:0 15px;
    outline:none;
    transition:.18s ease;
  }

  .auth-input:focus{
    background:#fff;
    border-color:#ff4a5e;
    box-shadow:0 0 0 4px rgba(255,74,94,.10);
  }

  .with-toggle{padding-right:52px;}

  .pass-toggle{
    position:absolute;
    right:8px;
    bottom:6px;
    width:38px;
    height:38px;
    border-radius:10px;
    display:grid;
    place-items:center;
    border:0;
    background:transparent;
    color:#555;
  }

  .pass-toggle:hover{background:#ecebe7;}

  .auth-primary{
    width:100%;
    height:50px;
    border-radius:999px;
    background:linear-gradient(135deg,var(--as-grad-start),var(--as-grad-mid) 55%,var(--as-grad-end));
    color:#fff;
    border:0;
    font-weight:900;
    letter-spacing:.03em;
    box-shadow:0 10px 22px rgba(255,74,94,.22);
    transition:.18s ease;
  }

  .auth-primary:hover{transform:translateY(-1px);filter:brightness(.98);}

  .auth-meta{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-top:3px;
    font-size:13px;
    color:rgba(17,17,17,.58);
  }

  .mobile-switch{
    display:none;
    text-align:center;
    margin-top:18px;
    font-size:13px;
    color:rgba(17,17,17,.56);
  }

  .mobile-switch button{
    color:#ff4a5e;
    font-weight:800;
  }

  .error-text{
    font-size:12px;
    color:#b91c1c;
    margin-top:5px;
  }

  @media(max-width:820px){
    .auth-shell{
      min-height:auto;
      overflow:visible;
      border-radius:22px;
    }
    .auth-overlay{display:none;}
    .auth-form-side{
      position:relative;
      width:100%;
      left:auto;
      height:auto;
      padding:34px 22px;
      min-height:0;
      display:none;
      transform:none!important;
      opacity:1!important;
    }
    .auth-form-side.is-mobile-active{display:flex;}
    .register-side{left:auto;}
    .mobile-switch{display:block;}
  }
</style>

<div class="auth-page">
  <div class="auth-shell {{ session('auth_mode') === 'register' || $errors->has('name') ? 'register-mode' : '' }}" id="authShell">

    <section class="auth-form-side login-side is-mobile-active" id="loginPanel">
      <div class="brand-mini">
        <div class="brand-mark">AS</div>
        <div class="font-bold">Aesthetic Store</div>
      </div>

      <h1 class="auth-title">Sign In</h1>
      <p class="auth-sub">Welcome back. Access your orders, wishlist and account.</p>

      @if(session('success'))
        <div class="alert alert-success mt-5">{{ session('success') }}</div>
      @endif

      <form method="POST" action="{{ route('login.submit') }}" class="auth-fields">
        @csrf

        <div>
          <label class="field-label">Email</label>
          <input class="auth-input" type="email" name="email" value="{{ old('email') }}" placeholder="Enter e-mail" autocomplete="email" required>
          @error('email')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field-wrap">
          <label class="field-label">Password</label>
          <input class="auth-input with-toggle" id="loginPassword" type="password" name="password" placeholder="Enter password" autocomplete="current-password" required>
          <button type="button" class="pass-toggle" data-toggle-password="loginPassword" aria-label="Show password">👁</button>
          @error('password')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="auth-meta">
          <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="remember" value="1" class="checkbox checkbox-sm">
            <span>Remember me</span>
          </label>
        </div>

        <button class="auth-primary">SIGN IN</button>
      </form>

      <div class="mobile-switch">
        Don't have an account?
        <button type="button" data-switch="register">Sign Up</button>
      </div>
    </section>

    <section class="auth-form-side register-side" id="registerPanel">
      <div class="brand-mini">
        <div class="brand-mark">AS</div>
        <div class="font-bold">Aesthetic Store</div>
      </div>

      <h2 class="auth-title">Create Account</h2>
      <p class="auth-sub">Join Aesthetic Store and enjoy a faster shopping experience.</p>

      <form method="POST" action="{{ route('register.submit') }}" class="auth-fields">
        @csrf

        <div>
          <label class="field-label">Full Name</label>
          <input class="auth-input" name="name" value="{{ old('name') }}" placeholder="Your full name" autocomplete="name" required>
          @error('name')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div>
          <label class="field-label">Email</label>
          <input class="auth-input" type="email" name="email" value="{{ old('email') }}" placeholder="Enter e-mail" autocomplete="email" required>
        </div>

        <div class="field-wrap">
          <label class="field-label">Password</label>
          <input class="auth-input with-toggle" id="registerPassword" type="password" name="password" placeholder="Minimum 8 characters" autocomplete="new-password" required>
          <button type="button" class="pass-toggle" data-toggle-password="registerPassword" aria-label="Show password">👁</button>
          @error('password')<div class="error-text">{{ $message }}</div>@enderror
        </div>

        <div class="field-wrap">
          <label class="field-label">Confirm Password</label>
          <input class="auth-input with-toggle" id="registerPasswordConfirmation" type="password" name="password_confirmation" placeholder="Confirm password" autocomplete="new-password" required>
          <button type="button" class="pass-toggle" data-toggle-password="registerPasswordConfirmation" aria-label="Show password">👁</button>
        </div>

        <button class="auth-primary">SIGN UP</button>
      </form>

      <div class="mobile-switch">
        Already have an account?
        <button type="button" data-switch="login">Sign In</button>
      </div>
    </section>

    <aside class="auth-overlay">
      <div class="overlay-inner" id="overlayLoginCopy">
        <div class="overlay-kicker">Aesthetic Store</div>
        <div class="overlay-title">Hello, Friend!</div>
        <p class="overlay-text">Enter your personal details and start your journey with us.</p>
        <button type="button" class="overlay-btn" data-switch="register">SIGN UP</button>
      </div>

      <div class="overlay-inner hidden" id="overlayRegisterCopy">
        <div class="overlay-kicker">Welcome Back</div>
        <div class="overlay-title">Already with us?</div>
        <p class="overlay-text">Sign in to continue shopping and manage your orders and wishlist.</p>
        <button type="button" class="overlay-btn" data-switch="login">SIGN IN</button>
      </div>
    </aside>

  </div>
</div>

<script>
(() => {
  const shell = document.getElementById('authShell');
  const loginPanel = document.getElementById('loginPanel');
  const registerPanel = document.getElementById('registerPanel');
  const loginCopy = document.getElementById('overlayLoginCopy');
  const registerCopy = document.getElementById('overlayRegisterCopy');

  function setMode(mode){
    const isRegister = mode === 'register';
    shell.classList.toggle('register-mode', isRegister);

    if(window.innerWidth <= 820){
      loginPanel.classList.toggle('is-mobile-active', !isRegister);
      registerPanel.classList.toggle('is-mobile-active', isRegister);
    }

    loginCopy.classList.toggle('hidden', isRegister);
    registerCopy.classList.toggle('hidden', !isRegister);
  }

  document.querySelectorAll('[data-switch]').forEach(btn => {
    btn.addEventListener('click', () => setMode(btn.dataset.switch));
  });

  document.querySelectorAll('[data-toggle-password]').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = document.getElementById(btn.dataset.togglePassword);
      const showing = input.type === 'text';
      input.type = showing ? 'password' : 'text';
      btn.textContent = showing ? '👁' : '🙈';
      btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
    });
  });

  setMode(shell.classList.contains('register-mode') ? 'register' : 'login');
  window.addEventListener('resize', () => {
    setMode(shell.classList.contains('register-mode') ? 'register' : 'login');
  });
})();
</script>
@endsection
