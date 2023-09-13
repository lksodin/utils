<?php
namespace Od\Utils;

use \Curl\Curl;
use \Exception;
use Od\Utils\JsonValidator;

class DomainAdmin
{
    const API_ENDPOINT = 'https://api.domains.ccgert.com/api/';

    private $site_list = ['a', 'd', 'e', 'p', 'n', 'q', 'devp'];

    private $api_list = [
        'get_domain_list' => 'getAllDomain',
        'disable_domain' => 'disableDomain'
    ];

    private function getApiEndpoint(string $api_name)
    {
        if (array_key_exists($api_name, $this->api_list)) {
            return self::API_ENDPOINT . $this->api_list[$api_name];
        } else {
            throw new Exception("Api: [{$api_name}] not found");
        }
    }

    private function getRawDomainList(string $site, $type, $status)
    {
        $payload = [
            'level2' => 1,
            'schema' => 0,
            'site' => $site,
            'type' => $type,
            'status' => $status
        ];
        $curl = new Curl;
        $curl->get($this->getApiEndpoint('get_domain_list'), $payload);

        if ($curl->isError()) {
            throw new Exception("Get domain list failed, curl error: [{$curl->getErrorMessage()}]");
        } else {
            $raw_res = $curl->getRawResponse();

            if (JsonValidator::isValid($raw_res)) {
                $decoded_res = json_decode($raw_res, true);

                if (isset($decoded_res['data'])) {
                    return $decoded_res['data'];
                } else {
                    throw new Exception("empty domain list returned: [{$raw_res}]");
                }
            } else {
                throw new Exception("Get domain list failed, Unexpected api response: [{$raw_res}]");
            }
        }
    }

    public function getDomainList(string $site, $type, $status)
    {
        $require_params = [ 'site', 'type', 'status' ];
        $raw_list = $this->getRawDomainList($site, $type, $status);
        $domain_list = [];

        foreach ($raw_list as $site_domains) {
            foreach ($site_domains as $type_domains) {
                $domain_list = array_merge($domain_list, $type_domains);
            }
        }

        if (empty($domain_list)) {
            throw new Exception("empty domain list returned: [{$raw_list}]");
        } else {
            return $domain_list;
        }
    }

    public function getWildcardDomainList(string $site, $type, $status)
    {
        return array_map(function ($domain) {
            return "*.{$domain}";
        }, $this->getDomainList($site, $type, $status));
    }

    public function disableDomain(string $site, $type, $domain, $desc, $match_mode = 0)
    {
        $payload = [
            'site' => $site,
            'type' => $type,
            'domain' => $domain,
            'desc' => $desc,
            'match_mode' => $match_mode
        ];
        $curl = new Curl;
        $curl->post($this->getApiEndpoint('disable_domain'), $payload);

        if ($curl->isError()) {
            throw new Exception("Api response error: {$curl->getErrorMessage()}");
        } else {
            $result = $curl->getResponse();
            return $result;
        }
    }
}
