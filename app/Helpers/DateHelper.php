<?php
use Illuminate\Support\Facades\Auth;

/**
 *
 * Returns local date format
 * @param string $utcDateTime <p>A UTC date/time string format (YYYY-MM-DD)</p> 
 * @param string $manual_format is optional parameter <p>In case of manual format, method ignore users profile date format.</p>
 * @return String formatted date string from user profile or manual passed date format
*/
function date_format_local($utcDateTime, $manual_format=''){
    // Will use later to make dynamic code
    /*
    Auth::user()->timezone = '';
    Auth::user()->date_format = '';
    */
    
    //User Timezone
    $userTimeZone = 'Asia/Kolkata';

    //User Date Display Format
    $userDesiredFormat = !empty($manual_format) ? $manual_format : 'Y-m-d H:i:s';

    // Create a DateTime object with the UTC date/time string
    $utc = new DateTime($utcDateTime, new DateTimeZone('UTC'));

    // Desired timezone
    $timezone = new DateTimeZone($userTimeZone);

    // Convert the UTC time to the desired timezone
    $timezoneDateTime = $utc->setTimezone($timezone);

    // Format the converted datetime string as desired
    $convertedDateTime = $timezoneDateTime->format($userDesiredFormat);

    // Output the converted datetime string
    return $convertedDateTime;
}
