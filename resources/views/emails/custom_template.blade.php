<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $subject ?? 'Message from Flyertrade' }}</title>
</head>

<body style="margin:0; padding:0; background: rgba(0, 0, 0, 0.05); font-family: Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 0;">
        <tr>
            <td align="center">

                <!-- Email Container -->
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#f4f7f8;">

                    <!-- Header -->
                    <tr>
                        <td style="background-color:#0b4f43; padding:20px;">
                            <table width="100%">
                                <tr>
                                    <td WIDTH="40px">
                                        <svg width="30" height="37" viewBox="0 0 30 37" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#clip0_1065_19349)">
                                                <path
                                                    d="M13.564 16.8645C13.5622 19.561 13.5622 22.2569 13.564 24.9528C13.564 32.5416 8.46223 34.8729 4.30127 36.8571L0.735246 31.0537C3.85551 29.4171 5.73778 27.5816 5.73778 25.1015C5.73778 24.5137 5.74266 24.2625 5.73778 21.258C5.73778 20.9238 5.73778 20.647 5.73778 20.4598C4.12345 19.1756 2.50973 17.8913 0.8954 16.6071C0.596405 14.6985 0.298019 12.7898 -0.000976562 10.8812C1.91235 10.8799 3.82507 10.8781 5.73839 10.8769C5.53013 10.3842 5.27863 9.65608 5.1605 8.74444C5.1605 8.74444 5.09351 8.21758 5.09351 7.68828C5.09412 2.82761 8.7594 0 13.9111 0C17.5271 0 19.4587 1.33911 21.0438 3.12458L17.2792 7.09251C16.9328 6.59675 16.1399 5.8528 15.1491 5.8528C14.5176 5.8528 13.9306 6.04976 13.4648 6.42113C12.6128 7.10044 12.5026 8.13709 12.4253 8.82859C12.3309 9.66889 12.4691 10.3817 12.6183 10.8769H13.5719C13.5683 12.8727 13.5659 14.868 13.5646 16.8645H13.564Z"
                                                    fill="white" />
                                                <path
                                                    d="M30.0002 10.8867C29.6872 12.8021 29.3748 14.7181 29.0618 16.6334C27.4317 17.9067 25.8021 19.1799 24.1719 20.4532C24.1719 20.5007 24.1719 21.2203 24.1719 21.2691C24.1719 24.6571 24.1719 24.9748 24.1719 25.087C24.1719 27.567 26.0542 29.4025 29.1745 31.0392L25.6085 36.8426C21.4475 34.8589 16.3457 32.5277 16.3457 24.9382C16.3585 18.9092 16.3719 22.7582 16.3847 16.7292C16.3798 14.7821 16.3743 12.835 16.3695 10.8879C20.9128 10.8879 25.4556 10.8873 29.999 10.8867H30.0002Z"
                                                    fill="white" />
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_1065_19349">
                                                    <rect width="30" height="36.8571" fill="white" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </td>
                                    <td style="color:#ffffff; font-weight: 500;font-size: 16px;line-height:1;">
                                         Flyertrade
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:30px; color:#717171; font-weight: 400; font-size: 14px;">

                            <h2 style="margin:0 0 15px 0; font-size:16px; font-weight: 600; color:#393939;">
                                {{ $subject ?? 'Message from Flyertrade' }}
                            </h2>

                            <p style="margin:0 0 15px 0;">
                                Dear {{ $name ?? 'Customer' }},
                            </p>

                            <div style="margin:0 0 15px 0; color:#717171;">
                                {!! $body ?? '' !!}
                            </div>

                        </td>
                    </tr>

                </table>
                <!-- End Container -->

            </td>
        </tr>
    </table>

</body>

</html>