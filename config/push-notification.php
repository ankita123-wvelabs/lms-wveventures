<?php

return array(

    'appNameIOS'     => array(
        'environment' =>'development',
        'certificate' => public_path('application_file/lms_dev_apns.pem'),
        'passPhrase'  =>'password',
        'service'     =>'apns'
    ),
    'appNameAndroid' => array(
        'environment' =>'production',
        'apiKey'      =>'AIzaSyA9gKnReC2HacaF6OiXxGoRTZq6Q2Yj4kM',
        'service'     =>'gcm'
    )

);