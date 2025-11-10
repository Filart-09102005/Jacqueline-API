<!DOCTYPE html>
<html>
<head>
    <title>Email Verified</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #5cb85c 0%, #7ec97e 30%, #f7931e 70%, #ff6b35 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .verified-container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.5);
            max-width: 500px;
            padding: 50px 40px;
        }
        
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #5cb85c, #7ec97e);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            box-shadow: 0 8px 20px rgba(92, 184, 92, 0.3);
        }
        
        .success-icon::before {
            content: "✓";
            color: white;
            font-size: 3rem;
            font-weight: bold;
        }
        
        .verified-title {
            color: #5cb85c;
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 15px;
        }
        
        .verified-text {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
            border: none;
            border-radius: 12px;
            padding: 14px 40px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
            background: linear-gradient(135deg, #f7931e 0%, #ff6b35 100%);
            color: white;
        }
        
        /* Custom SweetAlert2 styling */
        .swal2-popup {
            border-radius: 20px;
            padding: 2rem;
        }
        
        .swal2-title {
            color: #5cb85c;
            font-weight: 700;
        }
        
        .swal2-confirm {
            background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%) !important;
            border-radius: 12px;
            padding: 12px 40px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
        }
        
        .swal2-confirm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center">
    <div class="verified-container text-center">
        <div class="success-icon"></div>
        <h3 class="verified-title">Email Verified!</h3>
        <p class="verified-text">
            Congratulations! Your email has been successfully verified. 
            You can now access all features of your account.
        </p>
        <a href="{{ route('login.form') }}" class="btn-login">Go to Login</a>
    </div>

    <script>
        // Modern toast notification
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: 'linear-gradient(135deg, #5cb85c, #7ec97e)',
            color: '#fff',
            iconColor: '#fff',
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Show modern popup on load
        Swal.fire({
            title: 'Success!',
            html: '<p style="color: #666; margin: 10px 0;">Your account has been verified successfully.<br>Please login to continue.</p>',
            icon: 'success',
            confirmButtonText: 'Got it!',
            iconColor: '#5cb85c',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Show toast after main popup
                Toast.fire({
                    icon: 'success',
                    title: 'Welcome aboard! 🎉'
                });
            }
        });
    </script>
</body>
</html>