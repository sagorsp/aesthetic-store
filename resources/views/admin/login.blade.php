<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Aesthetic Store</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />

    <style>
        :root{
            --as-black:#111111;
            --as-gold:#C9A227;
            --as-grad-a:#ff5b2f;
            --as-grad-b:#ff4d61;
            --as-grad-c:#ff3f86;
            --as-gradient:linear-gradient(135deg,var(--as-grad-a) 0%,var(--as-grad-b) 52%,var(--as-grad-c) 100%);
        }

        *{box-sizing:border-box}

        body{
            margin:0;
            min-height:100vh;
            font-family:Inter,ui-sans-serif,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;
            background:
                radial-gradient(circle at 12% 18%,rgba(255,91,47,.14),transparent 28%),
                radial-gradient(circle at 88% 82%,rgba(255,63,134,.12),transparent 30%),
                #f5f4f2;
            color:#111;
        }

        .admin-login-page{
            min-height:100vh;
            display:grid;
            place-items:center;
            padding:28px 16px;
        }

        .login-shell{
            width:min(1020px,100%);
            min-height:620px;
            display:grid;
            grid-template-columns:1.02fr .98fr;
            overflow:hidden;
            border-radius:30px;
            background:#fff;
            border:1px solid rgba(17,17,17,.06);
            box-shadow:0 28px 80px rgba(17,17,17,.16);
        }

        .visual-panel{
            position:relative;
            overflow:hidden;
            padding:54px;
            color:#fff;
            background:var(--as-gradient);
            display:flex;
            flex-direction:column;
            justify-content:space-between;
        }

        .visual-panel:before,
        .visual-panel:after{
            content:"";
            position:absolute;
            border-radius:999px;
            border:1px solid rgba(255,255,255,.16);
            pointer-events:none;
        }

        .visual-panel:before{
            width:420px;height:420px;
            right:-180px;top:-120px;
        }

        .visual-panel:after{
            width:270px;height:270px;
            left:-120px;bottom:-90px;
        }

        .visual-glow{
            position:absolute;
            width:220px;height:220px;
            border-radius:999px;
            background:rgba(255,255,255,.13);
            filter:blur(10px);
            right:35px;
            bottom:55px;
        }

        .brand{
            position:relative;
            z-index:2;
            display:flex;
            align-items:center;
            gap:13px;
        }

        .brand-logo{
            width:50px;
            height:50px;
            border-radius:16px;
            background:#111;
            display:grid;
            place-items:center;
            color:var(--as-gold);
            font-size:18px;
            font-weight:900;
            box-shadow:0 14px 30px rgba(17,17,17,.18);
        }

        .brand-name{font-weight:900;font-size:18px}
        .brand-sub{font-size:11px;letter-spacing:.18em;text-transform:uppercase;opacity:.72;margin-top:2px}

        .visual-copy{
            position:relative;
            z-index:2;
            max-width:430px;
        }

        .visual-kicker{
            font-size:11px;
            text-transform:uppercase;
            letter-spacing:.22em;
            font-weight:900;
            opacity:.82;
            margin-bottom:15px;
        }

        .visual-title{
            margin:0;
            font-size:clamp(42px,5vw,64px);
            line-height:.98;
            letter-spacing:-.055em;
            font-weight:950;
        }

        .visual-text{
            max-width:370px;
            margin-top:20px;
            font-size:15px;
            line-height:1.75;
            color:rgba(255,255,255,.84);
        }

        .security-row{
            position:relative;
            z-index:2;
            display:flex;
            flex-wrap:wrap;
            gap:9px;
        }

        .security-pill{
            border:1px solid rgba(255,255,255,.22);
            background:rgba(255,255,255,.10);
            backdrop-filter:blur(10px);
            border-radius:999px;
            padding:8px 12px;
            font-size:11px;
            font-weight:800;
        }

        .form-panel{
            padding:58px 66px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#fff;
        }

        .form-inner{width:100%;max-width:390px}

        .admin-chip{
            display:inline-flex;
            align-items:center;
            gap:7px;
            padding:7px 11px;
            border-radius:999px;
            background:#fff2f5;
            color:#ff4d61;
            font-size:11px;
            text-transform:uppercase;
            letter-spacing:.12em;
            font-weight:900;
            margin-bottom:16px;
        }

        .form-title{
            font-size:38px;
            line-height:1.06;
            font-weight:950;
            letter-spacing:-.04em;
            margin:0;
        }

        .form-sub{
            font-size:14px;
            line-height:1.65;
            color:rgba(17,17,17,.48);
            margin:10px 0 26px;
        }

        .field{margin-bottom:16px}
        .field-label{
            display:block;
            font-size:12px;
            font-weight:800;
            margin-bottom:7px;
            color:rgba(17,17,17,.72);
        }

        .input-wrap{position:relative}

        .admin-input{
            width:100%;
            height:52px;
            border:1px solid #dedbd4;
            border-radius:14px;
            padding:0 15px;
            background:#faf9f7;
            outline:none;
            transition:.18s ease;
            font-size:14px;
        }

        .admin-input:focus{
            background:#fff;
            border-color:#ff4d61;
            box-shadow:0 0 0 4px rgba(255,77,97,.09);
        }

        .password-input{padding-right:54px}

        .password-toggle{
            position:absolute;
            right:8px;
            top:50%;
            transform:translateY(-50%);
            width:38px;
            height:38px;
            border:0;
            background:transparent;
            border-radius:10px;
            display:grid;
            place-items:center;
            cursor:pointer;
            transition:.18s ease;
        }

        .password-toggle:hover{background:#f0efeb}

        .form-meta{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:12px;
            margin:2px 0 20px;
            font-size:12px;
            color:rgba(17,17,17,.56);
        }

        .remember{
            display:flex;
            align-items:center;
            gap:8px;
            cursor:pointer;
        }

        .remember input{accent-color:#ff4d61}

        .login-btn{
            width:100%;
            height:52px;
            border:0;
            border-radius:14px;
            color:#fff;
            font-weight:900;
            letter-spacing:.03em;
            background:var(--as-gradient);
            cursor:pointer;
            box-shadow:0 12px 26px rgba(255,77,97,.22);
            transition:.18s ease;
        }

        .login-btn:hover{
            transform:translateY(-1px);
            filter:brightness(.98);
        }

        .back-link{
            display:flex;
            justify-content:center;
            margin-top:20px;
            font-size:12px;
            color:rgba(17,17,17,.48);
            text-decoration:none;
        }

        .back-link:hover{color:#ff4d61}

        .error-box{
            margin-bottom:18px;
            border:1px solid #fecdd3;
            background:#fff1f2;
            color:#9f1239;
            border-radius:13px;
            padding:11px 13px;
            font-size:12px;
            line-height:1.55;
        }

        .field-error{
            margin-top:6px;
            font-size:11px;
            color:#b91c1c;
        }

        @media(max-width:850px){
            .login-shell{
                grid-template-columns:1fr;
                min-height:auto;
                max-width:560px;
            }
            .visual-panel{
                min-height:290px;
                padding:34px 28px;
            }
            .visual-title{font-size:42px}
            .visual-text{margin-top:14px}
            .security-row{margin-top:24px}
            .form-panel{padding:38px 26px 42px}
        }

        @media(max-width:520px){
            .admin-login-page{padding:0}
            .login-shell{
                width:100%;
                border-radius:0;
                min-height:100vh;
            }
            .visual-panel{min-height:245px}
            .brand-logo{width:44px;height:44px}
            .visual-title{font-size:36px}
            .visual-text{font-size:13px}
            .form-panel{padding:34px 22px 42px}
            .form-title{font-size:32px}
        }
    </style>
</head>
<body>

<div class="admin-login-page">
    <div class="login-shell">

        <section class="visual-panel">
            <div class="visual-glow"></div>

            <div class="brand">
                <div class="brand-logo">AS</div>
                <div>
                    <div class="brand-name">Aesthetic Store</div>
                    <div class="brand-sub">Administration</div>
                </div>
            </div>

            <div class="visual-copy">
                <div class="visual-kicker">Secure Admin Access</div>
                <h1 class="visual-title">Manage your store beautifully.</h1>
                <p class="visual-text">
                    Products, orders, customers, reports, settings and storefront content — all from one secure dashboard.
                </p>
            </div>

            <div class="security-row">
                <span class="security-pill">Secure Login</span>
                <span class="security-pill">Role Protected</span>
                <span class="security-pill">Aesthetic Store Admin</span>
            </div>
        </section>

        <section class="form-panel">
            <div class="form-inner">
                <div class="admin-chip">● Admin Portal</div>

                <h2 class="form-title">Welcome back</h2>
                <p class="form-sub">
                    Sign in with your administrator account to continue.
                </p>

                @if($errors->any())
                    <div class="error-box">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="error-box">
                        {{ session('error') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf

                    <div class="field">
                        <label class="field-label" for="adminEmail">Email Address</label>
                        <input
                            id="adminEmail"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="admin-input"
                            placeholder="admin@example.com"
                            autocomplete="email"
                            autofocus
                            required
                        >
                        @error('email')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field">
                        <label class="field-label" for="adminPassword">Password</label>

                        <div class="input-wrap">
                            <input
                                id="adminPassword"
                                type="password"
                                name="password"
                                class="admin-input password-input"
                                placeholder="Enter your password"
                                autocomplete="current-password"
                                required
                            >

                            <button type="button"
                                    class="password-toggle"
                                    id="toggleAdminPassword"
                                    aria-label="Show password">
                                👁
                            </button>
                        </div>

                        @error('password')
                            <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-meta">
                        <label class="remember">
                            <input type="checkbox" name="remember" value="1">
                            <span>Remember me</span>
                        </label>

                        <span>Authorized access only</span>
                    </div>

                    <button type="submit" class="login-btn">
                        SIGN IN TO ADMIN
                    </button>
                </form>

                <a href="{{ route('home') }}" class="back-link">
                    ← Back to storefront
                </a>
            </div>
        </section>

    </div>
</div>

<script>
document.getElementById('toggleAdminPassword').addEventListener('click', function () {
    const input = document.getElementById('adminPassword');
    const showing = input.type === 'text';

    input.type = showing ? 'password' : 'text';
    this.textContent = showing ? '👁' : '🙈';
    this.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
});
</script>

</body>
</html>
