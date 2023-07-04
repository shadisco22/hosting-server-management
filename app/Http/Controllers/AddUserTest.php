<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AddUserTest extends Controller
{
    //
public function addAccount(Request $request)
{
    $user = "root";
    $token = "1LBFMWYHJY6WSUNJYN2PDF9ZV4AUR9VB";
    $query = "https://hosting.e-solutionsgroup.org:2087/json-api/createacct";

    $username = $request->input('username');
    $domain = $request->input('domain');
    $password = $request->input('password');
    $plan = $request->input('plan');

    $account_params = array(
        'api.version' => 1,
        'username' => $username,
        'domain' => $domain,
        'plan' => $plan,
        'password' => $password,
        'contactemail' => 'tareksati@hotmail.com',
        'featurelist' => 'default',
        'owner' => 'root'
    );

    $account_json = json_encode($account_params);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $header[0] = "Authorization: whm $user:$token";
    $header[1] = "Content-Type: application/json";
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $account_json);
    curl_setopt($curl, CURLOPT_URL, $query);

    $result = curl_exec($curl);

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    $response = [];
    if ($http_status != 200) {
        $response['error'] = "Error: " . $http_status . " returned";
    } else {
        $json = json_decode($result);
        $response['account_created'] = "Account was created successfully";
    }

    return response()->json($response); // Return the $response array as a JSON response
}
}
