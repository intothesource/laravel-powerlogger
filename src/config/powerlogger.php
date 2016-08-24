<?php
return [
    'customer'          => '', // Customer name eg. Into The Source
    'domain'            => '', // FQDN eg intothesource.com
    'slack'             => '', // Slack key
    'accept_env'        => [ // Add the environment when the system needs to log
        'production',
        'local',
    ],

    /**
     * Fire is found here.
     * Just enter any word/domain/directory you want
     *
     * Filters on request url and referer url!
     */
    'filters'           => [ // Filter these url's and/or referers (Using: builded PCRE)
       
    ],

    'showIpWhenRouteIs' => [

    ],
];
