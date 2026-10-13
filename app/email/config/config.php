<?php

return [
    'smtp' => [
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls',
        'username' => 'theekshanathennakoon79@gmail.com',
        'password' => 'pgqoptmaegfpuozc',
        'from_email' => 'theekshanathennakoon79@gmail.com',
        'from_name' => "Governor's Secretariat - Public Day Complaint System",
        'auth' => true,
        'debug' => false,
    ],

    'settings' => [
        'timezone' => 'Asia/Colombo',
        'baseURL' => 'https://complaint.is-great.org/',
        'fileURL' => 'https://complaint.is-great.org/',
        'contact_url' => 'https://www.zenithchambers.com/contact',
        'banner_url' => 'https://app.zenithchambers.com/email/assets/img/banner.webp',
        'company_name' => 'Governor\'s Secretariat - Public Day Complaint System',
        'admin_email' => 'theekshanathennakoon79@gmail.com',
        'logo' => '/assets/img/logo.webp',
        'icon' => '/assets/img/favicon.webp',
        'jwt_secret' => '8c9a2b7f15d34c69aefa725a4e061c29b8fc998eb74f6425acfe30c0d1fd129b',
        'currency' => 'LKR',
        'currency_symbol' => 'Rs.',
        'company_phone' => '+94774006055'
    ],
];
