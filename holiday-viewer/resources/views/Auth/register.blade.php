<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);
            max-width: 420px;
            padding: 40px;
        }
        .register-title { 
            color: #ff6b35; 
            font-weight: 700; 
            font-size: 2rem; 
            margin-bottom: 10px; 
        }
        .register-subtitle { 
            color: #5cb85c; 
            font-size: 0.95rem; 
            margin-bottom: 30px; 
        }
        .form-label { 
            color: #333; 
            font-weight: 600; 
            font-size: 0.9rem; 
            margin-bottom: 8px; 
        }
        .form-control {
            border: 2px solid #e0e0e0; 
            border-radius: 12px; 
            padding: 12px 16px;
            transition: all 0.3s ease; 
            font-size: 0.95rem;
        }
        .form-control:focus {
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
            background: #fff;
        }
        .btn-register {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            border: none; 
            border-radius: 12px; 
            padding: 14px;
            font-weight: 600; 
            font-size: 1rem; 
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
            background: linear-gradient(135deg, #f7931e 0%, #ff6b35 100%);
        }
        .login-link { 
            color: #5cb85c; 
            text-decoration: none; 
            font-weight: 600; 
            transition: all 0.3s ease; 
        }
        .login-link:hover { 
            color: #4a9d4a; 
            text-decoration: underline; 
        }
        .divider { 
            color: #999; 
            margin: 20px 0; 
        }
        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #999;
        }
        .password-toggle:hover {
            color: #ff6b35;
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="register-container mx-auto">
            <h3 class="register-title text-center">Create Account</h3>
            <p class="register-subtitle text-center">Join us and start your journey today</p>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input name="name" type="text" class="form-control" placeholder="John Doe" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input name="email" type="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}" required>
                </div>
                <div class="mb-3 password-wrapper">
                    <label class="form-label">Password</label>
                    <input name="password" id="password" type="password" class="form-control" placeholder="Create a strong password" required>
                    <i class="ri-eye-line password-toggle" id="togglePassword"></i>
                </div>
                <div class="mb-4 password-wrapper">
                    <label class="form-label">Confirm Password</label>
                    <input name="password_confirmation" id="confirmPassword" type="password" class="form-control" placeholder="Confirm your password" required>
                    <i class="ri-eye-line password-toggle" id="toggleConfirmPassword"></i>
                </div>
                <button class="btn btn-register w-100">Create Account</button>
            </form>

            <div class="divider text-center">
                <small>Already have an account?</small>
            </div>

            <div class="text-center">
                <a href="{{ route('login.form') }}" class="login-link">Login here</a>
            </div>
        </div>
    </div>

    <script>
        // Toast configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Show success message
        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}',
                background: 'linear-gradient(135deg, #5cb85c, #7ec97e)',
                color: '#fff',
                iconColor: '#fff'
            });
        @endif

        // Show error message
        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}',
                background: 'linear-gradient(135deg, #ff6b35, #ff8c5a)',
                color: '#fff',
                iconColor: '#fff'
            });
        @endif

        // Password toggle functionality
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPassword = document.getElementById('confirmPassword');

        togglePassword.addEventListener('click', () => {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            togglePassword.classList.toggle('ri-eye-line');
            togglePassword.classList.toggle('ri-eye-off-line');
        });

        toggleConfirmPassword.addEventListener('click', () => {
            const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPassword.setAttribute('type', type);
            toggleConfirmPassword.classList.toggle('ri-eye-line');
            toggleConfirmPassword.classList.toggle('ri-eye-off-line');
        });
    </script>
</body>
</html>