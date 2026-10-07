<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Welcome to Aesthetic Store</title>
</head>
<body style="margin:0;padding:0;background:#f5f4f2;font-family:Arial,Helvetica,sans-serif;color:#111;">
<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f5f4f2;padding:30px 14px;">
<tr>
<td align="center">

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="max-width:620px;background:#ffffff;border-radius:22px;overflow:hidden;box-shadow:0 16px 40px rgba(17,17,17,.08);">

<tr>
<td style="padding:34px 34px 22px;background:linear-gradient(135deg,#ff5b2f 0%,#ff4d61 52%,#ff3f86 100%);color:#fff;text-align:center;">
<div style="font-size:13px;letter-spacing:2px;text-transform:uppercase;font-weight:700;opacity:.9;">Aesthetic Store</div>
<h1 style="margin:12px 0 6px;font-size:34px;line-height:1.15;">Welcome, {{ $user->name }}!</h1>
<p style="margin:0;opacity:.9;font-size:15px;line-height:1.6;">
Your account has been created successfully.
</p>
</td>
</tr>

<tr>
<td style="padding:30px 34px;">
<p style="margin:0 0 18px;font-size:16px;line-height:1.7;">
Thanks for joining <strong>Aesthetic Store</strong>. You can now save wishlist items, place orders faster and manage your account from one place.
</p>

<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin:20px 0;">
<tr>
<td style="background:#faf9f6;border:1px solid #ece9e2;border-radius:16px;padding:18px;">
<div style="font-size:13px;color:#777;margin-bottom:6px;">Your account email</div>
<div style="font-size:16px;font-weight:700;">{{ $user->email }}</div>
</td>
</tr>
</table>

<table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin:26px auto;">
<tr>
<td style="border-radius:999px;background:linear-gradient(135deg,#ff5b2f,#ff4d61 52%,#ff3f86);">
<a href="{{ url('/account') }}" style="display:inline-block;padding:14px 28px;color:#fff;text-decoration:none;font-weight:800;font-size:14px;">
Go to My Account
</a>
</td>
</tr>
</table>

<p style="margin:22px 0 0;font-size:14px;line-height:1.7;color:#666;">
If you did not create this account, you can ignore this email or contact our support team.
</p>
</td>
</tr>

<tr>
<td style="padding:22px 34px;background:#111;color:#fff;text-align:center;">
<div style="font-size:14px;font-weight:700;">Aesthetic Store</div>
<div style="font-size:12px;color:#aaa;margin-top:6px;">
support@aestheticstore.bd
</div>
</td>
</tr>

</table>

</td>
</tr>
</table>
</body>
</html>
