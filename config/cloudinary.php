<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary URL
    |--------------------------------------------------------------------------
    |
    | The cloudinary()/UploadApi() helpers read configuration from this key
    | directly, separate from the 'cloudinary' disk defined in
    | config/filesystems.php (which is only used by Laravel's generic
    | Storage facade). Both should point at the same credentials.
    |
    */

    'cloud_url' => env('CLOUDINARY_URL'),

    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),

    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
];