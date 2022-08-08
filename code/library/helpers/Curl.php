<?php
/**
 * Helper: Curl
 *
 * An assist to just quickly and easily do cURL get/post requests
 *
 * @author Johnathan Tiong <johnathan.tiong@gmail.com>
 * @copyright 2022 Johnathan Tiong
 *
 */
namespace spark\Helpers;

/**
 * curl
 * helps send curl requests (get and post if needed)
 */
class curl
{
    /**
     * get request to a given $url
     *
     * @param string $url
     * @param string $token
     * @return void
     */
    public function get($url, $token)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}