<?php
	return [
	   'customer'   => 'Customer Name',           // Customer name eg. Into The Source
       'domain'     => 'test.dev',                // FQDN eg intothesource.com
       'slack'      => 'AAA/BBB/123'              // Slack key
       'accept_env' => [						  // Add the environment when the system needs to log
       		'production',
       		'local'
       ],

       /**
        * Fire is found here.  
        * Just enter any word/domain/directory you want
        *
        * Filters on request url and referer url!
        */
       'filters' => [							  // Filter these url's and/or referers (Using: builded PCRE)
       		// 'www.google.com',
       		// 'internal.site.org',
       		// 'old/images/ico.png',
       		// 'non-exist-dir'
       ]
	];
