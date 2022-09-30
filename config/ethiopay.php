<?php

if (isset($_SERVER) && count($_SERVER)) {
    $server_url_path = $_SERVER['DOCUMENT_ROOT'];
    if (isset($s['SERVER_PROTOCOL'])) {
        $ssl = (!empty($s['HTTPS']) && $s['HTTPS'] == 'on');
        $sp = strtolower($s['SERVER_PROTOCOL']);
        $protocol = substr($sp, 0, strpos($sp, '/')) . (($ssl) ? 's' : '');
        $port = $s['SERVER_PORT'];
        $port = ((!$ssl && $port == '80') || ($ssl && $port == '443')) ? '' : ':' . $port;
        $host = ($use_forwarded_host && isset($s['HTTP_X_FORWARDED_HOST'])) ? $s['HTTP_X_FORWARDED_HOST'] : (isset($s['HTTP_HOST']) ? $s['HTTP_HOST'] : null);
        $host = isset($host) ? $host : $s['SERVER_NAME'] . $port;
        $ABSOLUTE_URL = $protocol . '://' . $host;
    } else {
        $ABSOLUTE_URL = '';
    }
    
    if ($ABSOLUTE_URL == 'http://localhost' || $ABSOLUTE_URL == env('APP_HOST')) {
        $ABSOLUTE_URL = env('APP_URL') . '/';
    } else {
        $ABSOLUTE_URL = $ABSOLUTE_URL . '/';
    }
}

if ($ABSOLUTE_URL == '/') {
    $ABSOLUTE_URL = env('APP_URL') . '/';
}

$ABSOLUTE_DOC_PATH = storage_path() . '/app/public/';

try {
    \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY', ''));
    \Stripe\Stripe::setApiVersion(env('STRIPE_API_VERSION', ''));
} catch (Exception $e) {
    $stripeResponseBody = $e->getJsonBody();
    $stripeResponseBodyErr = $stripeResponseBody['error'];
    Log::info($stripeResponseBodyErr);
}

return [
    'DOC_PATH' => [
        'USER_PROFILE_ORIGINAL_DOC_PATH' => $ABSOLUTE_DOC_PATH . 'uploads/profile/original/',
        'USER_PROFILE_SMALL_DOC_PATH' => $ABSOLUTE_DOC_PATH . 'uploads/profile/small/',
        'USER_PROFILE_MEDIUM_DOC_PATH' => $ABSOLUTE_DOC_PATH . 'uploads/profile/medium/',
        'USER_PROFILE_LARGE_DOC_PATH' => $ABSOLUTE_DOC_PATH . 'uploads/profile/large/',
    ],
    'WEB_PATH' => [
        'ABSOLUTE_URL' => $ABSOLUTE_URL,
        'USER_PROFILE_ORIGINAL_URL' => $ABSOLUTE_URL . 'uploads/profile/original/',
        'USER_PROFILE_SMALL_URL' => $ABSOLUTE_URL . 'uploads/profile/small/',
        'USER_PROFILE_MEDIUM_URL' => $ABSOLUTE_URL . 'uploads/profile/medium/',
        'USER_PROFILE_LARGE_URL' => $ABSOLUTE_URL . 'uploads/profile/large/',
        'USER_DEFAULT_PROFILE_PICTURE_URL' => $ABSOLUTE_URL . 'img/defaultProfile.png',
        'DOCUMENT_LOADING_URL' => $ABSOLUTE_URL . 'img/imageLoader.gif',
    ],
    'EMAIL' => [
        'SUBJECT_PRE_TEXT' => env('MAIL_SUBJECT_PRE_TEXT', '') . ' ',
    ],
    'STRIPE' => [
        'API_SECRET_KEY' => env('STRIPE_SECRET_KEY', ''),
        'API_VERSION' => env('STRIPE_API_VERSION', ''),
    ],
    'UPLOAD_PROFILE_SMALL_WIDTH' => 100,
    'UPLOAD_PROFILE_SMALL_HEIGHT' => 100,
    'UPLOAD_PROFILE_MEDIUM_WIDTH' => 400,
    'UPLOAD_PROFILE_MEDIUM_HEIGHT' => 400,
    'UPLOAD_PROFILE_LARGE_WIDTH' => 1000,
    'UPLOAD_PROFILE_LARGE_HEIGHT' => 1000,
    'ETHIOOIA_PHONE_CODE' => env('ETHIOOIA_PHONE_CODE', '+251'),
    'TIMEZONE_STR' => env('TIMEZONE_STR', 'Asia/Kolkata'),
    'TIMEZONE_STR_SHORT' => env('TIMEZONE_STR_SHORT', 'IST'),
    'DAYS_REMINDER_FOR_EXPIRE_CARD' => (int) env('DAYS_REMINDER_FOR_EXPIRE_CARD', 7),
    'DAYS_REMINDER_FOR_EXPIRE_BILL' => (int) env('DAYS_REMINDER_FOR_EXPIRE_BILL', 7),
    'SESSION_EXPIRED_MINUTE_LIMIT' => env('SESSION_EXPIRED_MINUTE_LIMIT', 5),
];
