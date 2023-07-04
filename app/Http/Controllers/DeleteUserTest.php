<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeleteUserTest extends Controller
{
    //
    public function deleteAccount(Request $request)
    {
        $user = "root";
        $token = "1LBFMWYHJY6WSUNJYN2PDF9ZV4AUR9VB";

        $query = "https://hosting.e-solutionsgroup.org:2087/json-api/removeacct?user=username";

        $account_params = array(
            'api.version' => 1,
            'username' => 'example',
            'keepdns' => '0', // Set this to 0 to remove the account's DNS zone file
            
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
        $response = []; // Initialize the $response variable here
        if ($http_status != 200) {
            $response['error'] = "Error: " . $http_status . " returned";
        } else {
            $json = json_decode($result);
            $response['account_deleted'] = "Account was deleted successfully";
        }

        curl_close($curl);

        return view('cpanel.delete-acc-test', ['response' => $response]);
    }
}
