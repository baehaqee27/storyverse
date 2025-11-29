<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your Cloudinary settings. Cloudinary is a cloud
    | hosted media management service for all your file uploads.
    |
    */
    'cloud_url' => env('CLOUDINARY_URL'),

    /*
    |--------------------------------------------------------------------------
    | Upload Preset
    |--------------------------------------------------------------------------
    |
    | Upload Preset From Cloudinary Dashboard
    |
    */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    /*
    |--------------------------------------------------------------------------
    | Notification URL
    |--------------------------------------------------------------------------
    |
    | An HTTP or HTTPS URL to notify Cloudinary to return the response.
    |
    */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

    /*
    |--------------------------------------------------------------------------
    | Upload Route
    |--------------------------------------------------------------------------
    |
    | Upload Route
    |
    */
    'upload_route' => env('CLOUDINARY_UPLOAD_ROUTE'),

    /*
    |--------------------------------------------------------------------------
    | Upload Action
    |--------------------------------------------------------------------------
    |
    | Upload Action
    |
    */
    'upload_action' => env('CLOUDINARY_UPLOAD_ACTION'),

];
