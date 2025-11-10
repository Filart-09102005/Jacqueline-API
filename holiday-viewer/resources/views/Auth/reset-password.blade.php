<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #5cb85c 0%, #7ec97e 50%, #ff6b35 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .reset-container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);
            max-width: 480px;
            padding: 40px;
        }
        
        .reset-title {
            color: #5cb85c;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .reset-subtitle {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 30px;
            line-height: 1.6;
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
            border-color: #5cb85c;
            box-shadow: 0 0 0 0.2rem rgba(92, 184, 92, 0.15);
            background: #fff;
        }
        
        .btn-reset {
            background: linear-gradient(135deg, #5cb85c 0%, #7ec97e 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(92, 184, 92, 0.3);
        }
        
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(92, 184, 92, 0.4);
            background: linear-gradient(135deg, #7ec97e 0%, #5cb85c 100%);
        }
        
        .password-wrapper {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 38px;
            cursor: pointer;
            color: #999;
            z-index: 10;
        }
        
        .password-toggle:hover {
            color: #5cb85c;
        }
        
        .password-strength {
            height: 5px;
            border-radius: 3px;
            margin-top: 8px;
            background: #e0e0e0;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .password-strength-bar {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
        }
        
        .strength-weak { background: #ff6b35; width: 33%; }
        .strength-medium { background: #f7931e; width: 66%; }
        .strength-strong { background: #5cb85c; width: 100%; }
        
        .password-requirements {
            font-size: 0.8rem;
            color: #666;
            margin-top: 8px;
        }
        
        .requirement {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 4px;
        }
        
        .requirement.met {
            color: #5cb85c;
        }
        
        .requirement.met i {
            color: #5cb85c;
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="reset-container mx-auto">
            <h3 class="reset-title text-center">Reset Password</h3>
            <p class="reset-subtitle text-center">
                Create a new strong password for your account.
            </p>

            <form method="POST" action="{{ route('password.update') }}" id="resetForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" value="{{ $email }}" readonly style="background: #f8f9fa;">
                </div>

                <div class="mb-3 password-wrapper">
                    <label class="form-label">New Password</label>
                    <input name="password" id="password" type="password" class="form-control" placeholder="Enter new password" required>
                    <i class="ri-eye-line password-toggle" id="togglePassword"></i>
                    
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strengthBar"></div>
                    </div>
                    
                    <div class="password-requirements">
                        <div class="requirement" id="req-length">
                            <i class="ri-close-circle-line"></i>
                            <span>At least 8 characters</span>
                        </div>
                    </div>
                </div>

                <div class="mb-4 password-wrapper">
                    <label class="form-label">Confirm Password</label>
                    <input name="password_confirmation" id="password_confirmation" type="password" class="form-control" placeholder="Confirm new password" required>
                    <i class="ri-eye-line password-toggle" id="togglePasswordConfirm"></i>
                </div>

                <button type="submit" class="btn btn-reset w-100">Reset Password</button>
            </form>
        </div>
    </div>

<script>
    // Toast configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Display Laravel validation errors
    @if($errors->any())
        Toast.fire({
            icon: 'error',
            title: '{{ $errors->first() }}',
            background: 'linear-gradient(135deg, #ff6b35, #ff8c5a)',
            color: '#fff',
            iconColor: '#fff'
        });
    @endif

    // Display success message and redirect to login
    @if(session('status'))
        Toast.fire({
            icon: 'success',
            title: '{{ session('status') }}',
            background: 'linear-gradient(135deg, #5cb85c, #7ec97e)',
            color: '#fff',
            iconColor: '#fff'
        }).then(() => {
            window.location.href = "{{ route('login.form') }}";
        });
    @endif

    // Password visibility toggles
    const togglePassword = document.getElementById('togglePassword');
    const password = document.getElementById('password');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const passwordConfirm = document.getElementById('password_confirmation');

    togglePassword.addEventListener('click', () => {
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        togglePassword.classList.toggle('ri-eye-line');
        togglePassword.classList.toggle('ri-eye-off-line');
    });

    togglePasswordConfirm.addEventListener('click', () => {
        const type = passwordConfirm.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirm.setAttribute('type', type);
        togglePasswordConfirm.classList.toggle('ri-eye-line');
        togglePasswordConfirm.classList.toggle('ri-eye-off-line');
    });

    // Password strength checker
    const strengthBar = document.getElementById('strengthBar');
    const reqLength = document.getElementById('req-length');

    password.addEventListener('input', function() {
        const length = this.value.length;

        // Check length requirement
        if (length >= 8) {
            reqLength.classList.add('met');
            reqLength.querySelector('i').className = 'ri-check-circle-fill';
        } else {
            reqLength.classList.remove('met');
            reqLength.querySelector('i').className = 'ri-close-circle-line';
        }

        // Update strength bar
        strengthBar.className = 'password-strength-bar';
        if (length === 0) {
            strengthBar.style.width = '0%';
        } else if (length < 8) {
            strengthBar.classList.add('strength-weak');
        } else if (length < 12) {
            strengthBar.classList.add('strength-medium');
        } else {
            strengthBar.classList.add('strength-strong');
        }
    });

    // Form validation before submit
    document.getElementById('resetForm').addEventListener('submit', function(e) {
        const pass = password.value;
        const passConfirm = passwordConfirm.value;

        if (pass !== passConfirm) {
            e.preventDefault();
            Toast.fire({
                icon: 'error',
                title: 'Passwords do not match!',
                background: 'linear-gradient(135deg, #ff6b35, #ff8c5a)',
                color: '#fff',
                iconColor: '#fff'
            });
            return;
        }

        if (pass.length < 8) {
            e.preventDefault();
            Toast.fire({
                icon: 'error',
                title: 'Password must be at least 8 characters!',
                background: 'linear-gradient(135deg, #ff6b35, #ff8c5a)',
                color: '#fff',
                iconColor: '#fff'
            });
            return;
        }

        Swal.fire({
            title: 'Resetting Password...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    });
</script>


</body>
</html>