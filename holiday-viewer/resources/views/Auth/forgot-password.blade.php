<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 50%, #5cb85c 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .forgot-container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);
            max-width: 450px;
            padding: 40px;
        }
        
        .forgot-title {
            color: #ff6b35;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .forgot-subtitle {
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
            border-color: #ff6b35;
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.15);
            background: #fff;
        }
        
        .btn-reset {
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
        
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
            background: linear-gradient(135deg, #f7931e 0%, #ff6b35 100%);
        }
        
        .back-link {
            color: #5cb85c;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        
        .back-link:hover {
            color: #4a9d4a;
            text-decoration: underline;
        }
        
        .divider {
            color: #999;
            margin: 20px 0;
        }
        
        .info-box {
            background: linear-gradient(135deg, rgba(92, 184, 92, 0.1), rgba(126, 201, 126, 0.1));
            border-left: 4px solid #5cb85c;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 0.85rem;
            color: #555;
        }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="forgot-container mx-auto">
            <h3 class="forgot-title text-center">Forgot Password?</h3>
            <p class="forgot-subtitle text-center">
                No worries! Enter your email address and we'll send you a link to reset your password.
            </p>
            
            <div class="info-box">
                <i class="ri-information-line"></i>
                Please check your spam folder if you don't receive the email within a few minutes.
            </div>

            <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
                @csrf
                <div class="mb-4">
                    <label class="form-label">Email Address</label>
                    <input name="email" type="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}" required autofocus>
                </div>
                
                <button type="submit" class="btn btn-reset w-100">Send Reset Link</button>
            </form>

            <div class="divider text-center">
                <small>Remember your password?</small>
            </div>

            <div class="text-center">
                <a href="{{ route('login.form') }}" class="back-link">
                    <i class="ri-arrow-left-line"></i> Back to Login
                </a>
            </div>
        </div>
    </div>
<script>
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

    const form = document.getElementById('forgotForm');

    form.addEventListener('submit', async function(e) {
        e.preventDefault(); // prevent default form submission

        Swal.fire({
            title: 'Sending...',
            text: 'Please wait while we send the reset link',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();
            Swal.close();

            if (response.ok) {
                Toast.fire({
                    icon: 'success',
                    title: data.message || 'Password reset link sent!',
                    background: 'linear-gradient(135deg, #5cb85c, #7ec97e)',
                    color: '#fff',
                    iconColor: '#fff'
                });
                form.reset(); // optional: clear email input
            } else {
                Toast.fire({
                    icon: 'error',
                    title: data.message || 'Failed to send email.',
                    background: 'linear-gradient(135deg, #ff6b35, #ff8c5a)',
                    color: '#fff',
                    iconColor: '#fff'
                });
            }
        } catch (err) {
            Swal.close();
            Toast.fire({
                icon: 'error',
                title: 'Something went wrong. Please try again.',
                background: 'linear-gradient(135deg, #ff6b35, #ff8c5a)',
                color: '#fff',
                iconColor: '#fff'
            });
            console.error(err);
        }
    });
</script>

</body>
</html>