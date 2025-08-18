<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.6;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            margin-top: 20px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <h2>Hello, {{ $name }}!</h2>

    <p>Welcome aboard 🎉 We're thrilled to have you join our community.</p>

    <p>You're now part of something exciting — a platform designed to support, inspire, and grow with you.</p>

    <p>To get started, click the button below and dive right in:</p>

    <a href="{{ $appURL }}" class="button">Visit Our Website</a>

    <p>If you have any questions, feel free to reach out. We're here to help!</p>

    <p>Thanks again,<br>The Team</p>
</body>
</html>
