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
            margin: 0;
            padding: 0;
        }

        :root {
            --primary: #4f46e5;
            --primary-dark: #4338ca;
            --primary-light: #eef2ff;
            --bg: #f8fafc;
            --bg-gradient: linear-gradient(135deg, #eef2ff, #f8fafc);
            --card: #ffffff;
            --text-main: #0f172a;
            --text-body: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius-sm: 12px;
            --radius-md: 16px;
            --radius-lg: 20px;
            --shadow: 0 10px 30px rgba(15, 23, 42, 0.08);
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: var(--bg-gradient);
            color: var(--text-body);
            min-height: 100vh;
        }

        .auth-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 460px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .auth-header {
            padding: 28px 28px 16px;
            border-bottom: 1px solid var(--border);
        }

        .auth-header h2 {
            font-size: 30px;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .auth-header p {
            color: var(--text-muted);
            font-size: 14px;
        }

        .auth-body {
            padding: 28px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 700;
            margin-bottom: 8px;
            color: var(--text-body);
        }

        input,
        select {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #cbd5e1;
            border-radius: var(--radius-sm);
            font-size: 15px;
            background: #ffffff;
            color: var(--text-body);
            transition: 0.2s ease;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.12);
        }

        .btn {
            width: 100%;
            border: none;
            border-radius: var(--radius-sm);
            padding: 13px 18px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s ease;
            background: var(--primary);
            color: #ffffff;
        }

        .btn:hover {
            background: var(--primary-dark);
        }

        .message {
            padding: 12px 14px;
            margin-bottom: 18px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            line-height: 1.5;
        }

        .message.error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }

        .message.success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }

        .auth-link {
            margin-top: 22px;
            text-align: center;
            color: var(--text-muted);
            font-size: 14px;
        }

        .auth-link a {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
        }

        .auth-link a:hover {
            text-decoration: underline;
        }

        .auth-note {
            text-align: center;
            margin-top: 14px;
            color: var(--text-muted);
            font-size: 14px;
            line-height: 1.6;
        }

        @media (max-width: 500px) {
            .auth-card {
                border-radius: var(--radius-md);
            }

            .auth-header,
            .auth-body {
                padding: 22px;
            }

            .auth-header h2 {
                font-size: 26px;
            }
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