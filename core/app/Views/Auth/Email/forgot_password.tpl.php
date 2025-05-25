<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* General styles */
        body, table, td, a {
            font-family: Arial, sans-serif;
            text-align: left;
            color: #333;
            line-height: 1.6;
        }
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        .email-container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #0F1011;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background-color: #0F1011;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            font-size: 14px;
        }
        /* Responsive styles */
        @media (max-width: 600px) {
            .content, .footer {
                padding: 15px;
            }
            .header {
                font-size: 20px;
            }
        }
    </style>
    <title>Reset Your Password</title>
</head>
<body>
<table class="email-container">
    <tbody>
    <tr>
        <td class="header">
            Reset Your Password
        </td>
    </tr>
    <tr>
        <td class="content">
            <h2>Hello, <?php echo $identity->first_name; ?>!</h2>
            <p>Click the button below to reset your password.</p>
            <a href="<?php echo route('auth.reset_password', $forgottenPasswordCode) ?>"
               style="display: inline-block; background-color: #00A629; color: #ffffff; padding: 12px 20px; text-align: center; text-decoration: none; border-radius: 5px; font-weight: bold;">
                Reset Password
            </a>
            <p> ... or copy-paste the following link in your browser.</p>
            <p><a href="<?php echo route('auth.reset_password', $forgottenPasswordCode) ?>" style="color: #0F1011; text-decoration: underline;">
                    <?php echo route('auth.reset_password',  $forgottenPasswordCode) ?>
                </a></p>
        </td>
    </tr>
    <tr>
        <td class="footer">
            Website: <a href="<?php echo site_url() ?>" style="color: #ffffff; text-decoration: underline;">Trading Systems</a><br/><br/>
            &copy; 2024 All rights reserved.<br>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>