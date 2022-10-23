<?php

    namespace App\Http\Rest;

    class API
    {
        public static function APIcall ($apiKey, $method, $url, $data) 
        {
            $curl = curl_init($url);
            $data = json_encode ($data);
            switch ($method) {
                case 'POST':
                    curl_setopt($curl, CURLOPT_POST, 1);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                case 'PUT':
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                case 'GET':
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
                    //curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
                case 'DELETE':
                    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    break;
            }
             
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                'APIKEY: '.$apiKey,
                'Content-Type: application/json',
            ));
             
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            $result = json_decode (curl_exec ($curl), true);
              
            curl_close($curl);
            return $result;
        }
    }
?>