<?php
$error = $error ?? ($_SESSION['error'] ?? '');
$success = $success ?? ($_SESSION['success'] ?? '');
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CV Online</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            color: #1f2937;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .auth-card {
            width: 100%;
            max-width: 450px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
            overflow: hidden;
        }

        .auth-header {
            padding: 20px 24px;
            background: #16a34a;
            color: #ffffff;
        }

        .auth-header h2 {
            margin: 0;
            font-size: 24px;
        }

        .auth-body {
            padding: 24px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
        }

        input:focus {
            outline: none;
            border-color: #16a34a;
            box-shadow: 0 0 0 3px rgba(22, 163, 74, 0.16);
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: #16a34a;
            color: #ffffff;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn:hover {
            background: #15803d;
        }

        .message {
            padding: 10px 12px;
            margin-bottom: 16px;
            border-radius: 8px;
            font-size: 14px;
        }

        .message.error {
            background: #fee2e2;
            color: #991b1b;
        }

        .message.success {
            background: #dcfce7;
            color: #166534;
        }

        .auth-link {
            margin-top: 18px;
            text-align: center;
        }

        .auth-link a {
            color: #16a34a;
            font-weight: 700;
            text-decoration: none;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <div class="auth-header">
            <h2>Register</h2>
        </div>

        <div class="auth-body">
            <?php if (!empty($error)): ?>
                <div class="message error"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="message success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <p style="margin-bottom: 16px; color:#475569;">
                Please choose your role: Employer or Job Seeker, then fill in your account details.
            </p>

            <form method="POST" action="index.php?route=auth/register">
                <div class="form-group">
                    <label for="role">Chọn vai trò</label>
                    <select id="role" name="role" class="form-control" required>
                        <option value="job_seeker">Job Seeker</option>
                        <option value="employer">Employer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn">Register</button>
            </form>

            <div class="auth-link">
                Already have account?
                <a href="index.php?route=auth/login">Login here</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>