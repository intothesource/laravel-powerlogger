<?php

namespace IntoTheSource\Powerlogger;

use Config;

class Handle
{

    public static function init(\Exception $e)
    {
        $request = request();

        if (in_array(env('APP_ENV', 'production'), config('powerlogger.accept_env'))) {

            if ($e instanceof NotFoundHttpException) {
                $status = $e->getStatusCode();
            } else {
                $status = 404;
            }

            $message = [
                'text'   => 'Logmelding van ' . config('powerlogger.customer') . ' [ ' . config('powerlogger.domain') . ' ]',
                'name'   => 'IntoTheLogBot',
                'fields' => [
                    [
                        'title' => 'Foutcode',
                        'value' => $status,
                        'short' => true,
                    ],
                    [
                        'title' => 'URL',
                        'value' => $request->url(),
                        'short' => true,
                    ],
                    [
                        'title' => 'Method',
                        'value' => $request->method(),
                        'short' => true,
                    ],
                ],
            ];

            if (!empty($e->getMessage())) {
                $message['fields'][] = [
                    'title' => 'Error bericht',
                    'value' => $e->getMessage(),
                    'short' => true,
                ];
            }

            if (!empty($request->server('HTTP_REFERER'))) {
                $message['fields'][] = [
                    'title' => 'Referrer',
                    'value' => $request->server('HTTP_REFERER'),
                    'short' => true,
                ];
            }

            if ( ! empty($request->server('REMOTE_ADDR')))
            {
                $url = 'http://ip-api.com/json/'.$request->server('REMOTE_ADDR').'?fields=country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,reverse,mobile,proxy,query,status,message';
                $json = json_decode(file_get_contents($url), true);

                $message['fields'][] = [
                    'title' => 'IP',
                    'value' => '<http://ip-api.com/#' . $request->server('REMOTE_ADDR') . '|' . $request->server('REMOTE_ADDR') . '>',
                    'short' => true
                ];

                $message['fields'][] = [
                    'title' => 'IP - info',
                    'value' => $json['org'] . ' | ' . $json['regionName'] . ' | ' . $json['reverse'],
                    'short' => true
                ];

                $message['fields'][] = [
                    'title' => 'IP Acties',
                    'value' => '<https://http-tarpit.org/api.php?add='.$request->server('REMOTE_ADDR').'&reason=Hackbot&via='.config('powerlogger.domain').'|Blokkeer> of <https://http-tarpit.org/api.php?remove='.$request->server('REMOTE_ADDR').'|Deblokeer>',
                    'short' => true
                ];
            }

            if ($request->route() !== null) {
                $message['fields'][] = [
                    'title' => 'Route',
                    'value' => $request->route()->getName(),
                    'short' => true,
                ];
            }

            if (in_array($request->method(), ['POST', 'PUT', 'PATCH'])) {

                $inputs = '';
                foreach ($request->except(['old_password', 'password_confirmation', 'password', 'confirm_password', '_token', '_method']) as $name => $value) {
                    $inputs .= "[" . $name . "] => " . $value . "\n";
                }

                $message['fields'][] = [
                    'title' => 'Input',
                    'value' => $inputs,
                    'short' => true,
                ];
            }

            switch ($status):
        default:
        case 404:
            $message['color'] = 'warning';
            break;
        case 403:
        case 500:
            $message['color'] = 'danger';
            break;
            endswitch;

            $touchedFilter = $logIPAddress = false;
            foreach (config('powerlogger.filters') as $filter) {
                    $filter = str_replace(['.', '/'], ['\.', '\/'], $filter);

                    if (in_array($filter, config('powerlogger.showIpWhenRouteIs'))) {
                        $logIPAddress = true;
                    }

                    if (stripos($request->url(), $filter)) {
                        $touchedFilter = true;
                    }

                    if (!empty($request->server('HTTP_REFERER'))) {
                        if (stripos($request->server('HTTP_REFERER'), $filter)) {
                            $touchedFilter = true;
                        }
                    }
            }

            if ($touchedFilter) {
                return false; //Stopping the logger here, because we don't want to log this.
            }

            if(isset($request->url()))
            {   
                if ( ! empty($request->server('REMOTE_ADDR')))
                {
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, 'https://http-tarpit.org/api.php?add=' . $request->server('REMOTE_ADDR') . '&auto=true&via='.config('powerlogger.domain').'&url='.$request->url());
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_exec($curl);
                }
                // Stopping the logger here, because we dont want to log 
                // wordpress bots, just block them and get on with our lifes.
                return false;
            }

            $attachment = json_encode($message);
            $curl       = curl_init();
            curl_setopt($curl, CURLOPT_URL, 'https://hooks.slack.com/services/' . config('powerlogger.slack'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $attachment);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_exec($curl);
        }
    }
}
