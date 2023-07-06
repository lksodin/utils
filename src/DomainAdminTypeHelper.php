<?php
namespace Od\Utils;

use \Exception;

class DomainAdminTypeHelper
{
    private static $map = [
        'ai' => 1,
        'share' => 2,
        'jt' => 4,
        'wi' => 8,
        'm' => 10,
        'lk' => 11,
        'ct' => 12,
        'ck' => 13,
        'dl' => 15,
        'list' => 99,
        'lk_api' => 100,
        'wap_api' => 101,
        'app_api' => 102,
        'app_vapi' => 103,
        'cs' => 105
    ];

    public static function toNumeric(string $types)
    {
        $type_array = explode(',', $types);
        $result = [];

        foreach ($type_array as $type) {
            if (is_numeric($type)) {
                if (in_array($type, self::$map, true)) {
                    $result[] = $type;
                } else {
                    throw new Exception("Unknown int type: [{$type}]");
                }
            } else {
                if (array_key_exists($type, self::$map)) {
                    $result[] = self::$map[$type];
                } else {
                    throw new Exception("Unknown int type: [{$type}]");
                }
            }
        }

        return implode(',', $result);
    }
}
