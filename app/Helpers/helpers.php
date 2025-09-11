<?php
use Illuminate\Support\Facades\Mail; 
 
if (!function_exists('sendVerificationMail')) {
    function sendVerificationMail($otp, $email)
    {

    }
}


if (!function_exists('dateFormat')) {
    function dateFormat($value)
    {
        return \Carbon\Carbon::parse($value)->format('M d, Y');
    }
}
if (!function_exists('calculateProfileCompletion')) {
    function calculateProfileCompletion($profile)
    {
        $total = 10;
        $score = 0;

        if (!empty($profile->about_me)) $score++;
        if (!empty($profile->profile_photo)) $score++;
        if (!empty($profile->country)) $score++;
        if (!empty($profile->city)) $score++;
        if (!empty($profile->office_address)) $score++;
        if (!empty($profile->latitude) && !empty($profile->longitude)) $score++;
        if ($profile->services->count() > 0) $score++;
        if ($profile->certificates->count() > 0) $score++;
        if ($profile->id_photo_status === 'approved') $score++;
        if ($profile->passport_status === 'approved') $score++;
        if ($profile->work_permit_status === 'approved') $score++;

        return round(($score / $total) * 100);
    }
}

    function calculateProfileCompletion($profile)
    {
        $requirements = [
            'about_me' => 'About Me',
            'profile_photo' => 'Profile Photo',
            'country' => 'Country',
            'city' => 'City',
            'office_address' => 'Office Address',
            'latitude_longitude' => 'Location Coordinates',
            'services' => 'At least one Service',
            'certificates' => 'At least one Certificate',
            'id_photo_status' => 'ID Photo Approved',
            'passport_status' => 'Passport Approved',
            'work_permit_status' => 'Work Permit Approved'
        ];

        $score = 0;
        $missing = [];

        foreach ($requirements as $key => $label) {
            switch ($key) {
                case 'latitude_longitude':
                    if (!empty($profile->latitude) && !empty($profile->longitude)) {
                        $score++;
                    } else {
                        $missing[] = $label;
                    }
                    break;

                case 'services':
                    if ($profile->services->count() > 0) {
                        $score++;
                    } else {
                        $missing[] = $label;
                    }
                    break;

                case 'certificates':
                    if ($profile->certificates->count() > 0) {
                        $score++;
                    } else {
                        $missing[] = $label;
                    }
                    break;

                case 'id_photo_status':
                case 'passport_status':
                case 'work_permit_status':
                    if ($profile->{$key} === 'approved') {
                        $score++;
                    } else {
                        $missing[] = $label;
                    }
                    break;

                default:
                    if (!empty($profile->{$key})) {
                        $score++;
                    } else {
                        $missing[] = $label;
                    }
            }
        }

        $percentage = round(($score / count($requirements)) * 100);

        return [
            'completion_percentage' => $percentage,
            'missing_fields' => $missing
        ];
    }
