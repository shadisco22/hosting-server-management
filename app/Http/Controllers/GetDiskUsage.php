<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GetDiskUsage extends Controller
{
    //
   public function getDiskUsage(Request $request)
{
    $user = "root";
    $token = "1LBFMWYHJY6WSUNJYN2PDF9ZV4AUR9VB";
    $query = "https://hosting.e-solutionsgroup.org:2087/json-api/get_disk_usage?api.version=1";

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $header[0] = "Authorization: whm $user:$token";
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    curl_setopt($curl, CURLOPT_URL, $query);

    $result = curl_exec($curl);

    $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    if ($http_status != 200) {
        return response()->json(['error' => 'Failed to retrieve disk usage information.'], $http_status);
    }

    $response = json_decode($result, true);
    if (!isset($response['data']) || !is_array($response['data']['accounts'])) {
        return response()->json(['error' => 'Failed to retrieve disk usage information.'], 500);
    }

    $accounts = $response['data']['accounts'];
    $users = [];
    foreach ($accounts as $account) {
        $user = [
            'user' => isset($account['user']) ? $account['user'] : 'N/A',
            'blocks_used' => isset($account['blocks_used']) ? $account['blocks_used'] : 'N/A',
            'blocks_limit' => isset($account['blocks_limit']) ? $account['blocks_limit'] : 'N/A',
            'inodes_used' => isset($account['inodes_used']) ? $account['inodes_used'] : 'N/A',
            'inodes_limit' => isset($account['inodes_limit']) ? $account['inodes_limit'] : 'N/A',
        ];
        $users[] = $user;
    }

    return response()->json(['users' => $users]);
}
}
