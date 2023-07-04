<?php

namespace App\Http\Controllers;

/*use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;*/


class ListAccTest extends Controller
{
    //
    public function listAccounts()
    {
        $user = "root";
        $token = "1LBFMWYHJY6WSUNJYN2PDF9ZV4AUR9VB";

        $query = "https://hosting.e-solutionsgroup.org:2087/json-api/listaccts?api.version=1";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER,0);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);

        $header[0] = "Authorization: whm $user:$token";
        curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
        curl_setopt($curl, CURLOPT_URL, $query);

        $result = curl_exec($curl);

        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $users = []; // Initialize the $users variable here
        if ($http_status != 200) {
            echo "[!] Error: " . $http_status . " returned\n";
        } else {
            $json = json_decode($result);

            foreach ($json->{'data'}->{'acct'} as $userdetails) {
                $user = [
                    'user' => $userdetails->{'user'},
                    'start_date' => $userdetails->{'startdate'},
                    'suspended' => $userdetails->{'suspended'},
                    'suspend_reason' => $userdetails->{'suspendreason'},
                    'suspend_time' => $userdetails->{'suspendtime'},
                ];
                $users[] = $user;
            }
        }

        curl_close($curl);

        return response()->json($user);
    }
}
