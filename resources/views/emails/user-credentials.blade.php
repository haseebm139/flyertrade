<!DOCTYPE html>
<html>

<head>
    <title>Welcome to FlyerTrade - Your Account Credentials</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        body {
            background-color: #e5f8f2;
            margin: 0 !important;
            padding: 0 !important;
        }
    </style>
</head>

<body style="background-color: #e5f8f2; margin: 0 !important; padding: 0 !important;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <!-- LOGO -->
        <tr>
            <td bgcolor="#e5f8f2" align="center">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td align="center" valign="top" style="padding: 40px 10px 40px 10px;">
                            <img src="{{ asset('assets/logos/email_logo.png') }}" width="auto" height="auto"
                                style="display: block; border: 0px;" />
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td bgcolor="#e5f8f2" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#e5f8f2" align="center" valign="top"
                            style="padding: 40px 20px 20px 20px; border-radius: 4px 4px 0px 0px; color: #fefefe; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 48px; font-weight: 400; letter-spacing: 4px; line-height: 48px;">
                            <h1 style="font-size: 32px; font-weight: 400; margin: 0; color: #004E42;">
                                Welcome to FlyerTrade!
                            </h1>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td bgcolor="#e5f8f2" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="left"
                            style="padding: 40px 30px 40px 30px; border-radius: 4px 4px 0px 0px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0; color: #333333;">
                                Hello <strong>{{ $user->name }}</strong>,
                            </p>
                            <p style="margin: 20px 0 0 0; color: #333333;">
                                Your account has been successfully created on FlyerTrade. Below are your login credentials:
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td bgcolor="#e5f8f2" align="center" style="padding: 0px 10px 0px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="left" style="padding: 0px 30px 40px 30px;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td bgcolor="#f8f9fa" align="center"
                                        style="padding: 20px 30px; border-radius: 3px; border: 1px solid #e0e0e0;">
                                        <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                            <tr>
                                                <td align="left" style="padding: 10px 0;">
                                                    <p style="margin: 0; font-size: 14px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif;">
                                                        <strong style="color: #004E42;">Email:</strong>
                                                    </p>
                                                    <p style="margin: 5px 0 0 0; font-size: 16px; color: #333333; font-family: 'Lato', Helvetica, Arial, sans-serif;">
                                                        {{ $user->email }}
                                                    </p>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="left" style="padding: 20px 0 10px 0;">
                                                    <p style="margin: 0; font-size: 14px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif;">
                                                        <strong style="color: #004E42;">Password:</strong>
                                                    </p>
                                                    <p style="margin: 5px 0 0 0; font-size: 18px; color: #004E42; font-weight: bold; font-family: 'Lato', Helvetica, Arial, sans-serif; letter-spacing: 2px;">
                                                        {{ $password }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td bgcolor="#e5f8f2" align="center" style="padding: 0px 10px 40px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#ffffff" align="left"
                            style="padding: 0px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px;">
                            <p style="margin: 0; color: #333333;">
                                <strong>Important Security Note:</strong>
                            </p>
                            <p style="margin: 10px 0 0 0; color: #666666;">
                                For your security, please change your password after your first login. Keep your credentials safe and do not share them with anyone.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td bgcolor="#e5f8f2" align="center" style="padding: 0px 10px 40px 10px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                    <tr>
                        <td bgcolor="#e5f8f2" align="center" style="padding: 20px 30px;">
                            <p style="margin: 0; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 12px;">
                                If you have any questions, please contact our support team.
                            </p>
                            <p style="margin: 10px 0 0 0; color: #004E42; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 12px;">
                                © {{ date('Y') }} FlyerTrade. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
