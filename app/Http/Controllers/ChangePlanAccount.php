<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChangePlanAccount extends Controller
{
    //
    public function changePackage(Request $request)
    {
        $user = "root";
        $token = "1LBFMWYHJY6WSUNJYN2PDF9ZV4AUR9VB";
        $server = "hosting.e-solutionsgroup.org";

        $account = $request->input('account');
        $package = $request->input('package');


        $params = [
            'api.version' => 1,
            'user' => $account,
            'pkg' => $package,
        ];

        $query = http_build_query($params);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $header[0] = "Authorization: whm $user:$token";
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_URL, "https://$server:2087/json-api/changepackage?" . $query);

        $result = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($http_status != 200) {
            $message = "Failed to change package for account $account";
            return response()->json($message);
        } else {
            $message = "success";
            return response()->json($message);
        }
    }
}
