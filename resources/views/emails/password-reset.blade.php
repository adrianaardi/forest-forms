<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <p>Hi {{ $user->name }},</p>
    <p>We received a request to reset your password for Forest Forms.</p>
    <p>Please click the button below to set a new password:</p>
    <p>
        <a href="{{ $resetUrl }}" style="display: inline-block; padding: 10px 20px; background-color: #2563eb; color: #fff; text-decoration: none; border-radius: 4px;">
            Reset Password
        </a>
    </p>
    <p>If you did not request this, you can ignore this email.</p>
    <p>Thank you,<br>Forest Forms</p>
</body>
</html>
