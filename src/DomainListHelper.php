<?php
namespace Od\Utils;

use \Curl\Curl;
use \Exception;

class DomainListHelper
{
    const API_ENDPOINT = 'https://api.domains.ccgert.com/api/getAllDomain';

    private $input_params = [];
    private $default_params = [
        'level2' => 1,
        'schema' => 0,
    ];
    private $require_params = [ 'site', 'type', 'status' ];
    private $params_key_list = [ 'status', 'site', 'type' ];

    public function __construct(array $params)
    {
        $this->validate($params);
        $this->input_params = $params;
    }

    public function getList()
    {
        $curl = new Curl;
        $payload = array_merge($this->default_params, $this->input_params);
        $response = $curl->get(self::API_ENDPOINT, $payload);
        $curl_info = $curl->getInfo();

        if ($curl->getHttpStatusCode() == 200) {
            $array_response = self::responseToArray($response);

            if (isset($array_response['data'][$payload['site']][$payload['type']])) {
                $this->domain_list = $array_response['data'][$payload['site']][$payload['type']];

                return $this->domain_list;
            } else {
                throw new Exception("empty domain list returned: [{$raw_res}]");
            }
        } else {
            throw new Exception("Get domain list failed, Unexpected api response: [{$response}]");
        }
    }

    private function responseToArray($response)
    {
        $array_res = json_decode(json_encode($response), true);

        if (is_array($array_res)) {
            return $array_res;
        } else {
            throw new Exception("unknown response:" . $response);
        }
    }

    public function getWildcardDomainList()
    {
        if (empty($this->domain_list)) {
            $this->getList();
        }

        return array_map(function ($domain) {
            return "*.{$domain}";
        }, $this->domain_list);
    }

    private function validate($params)
    {
        foreach ($this->params_key_list as $key) {
            if (!array_key_exists($key, $params)) {
                throw new Exception("missing params: [{$key}]");
            }
        }

        return true;
    }
}
