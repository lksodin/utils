<?php
namespace Od\Utils;

use \Exception;
use \InvalidArgumentException;

class DomainName
{
    const DEFAULT_SUB_LENGTH = 8;

    private $level2;
    private $sub;

    public function __construct($domainName)
    {
        if ($domainName = filter_var($domainName, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME)) {
            $domainNameParts = explode(".", $domainName);
            $this->level2 = implode('.', array_slice($domainNameParts, -2, 2));
            $this->sub = implode('.', array_slice($domainNameParts, 0, -2));
        } else {
            throw new InvalidArgumentException("Invalid domain name");
        }
    }

    public function full()
    {
        return sprintf("%s.%s", $this->sub, $this->level2);
    }

    public function setLevel2($domainName)
    {
        if (explode('.', $domainName) != 2) {
            throw new InvalidArgumentException("invalid format for level2 domain");
        }

        $this->level2 = $domainName;
    }

    public function level2()
    {
        return $this->level2;
    }

    public function setSub($sub)
    {
        $this->sub = $sub;
    }

    public function sub()
    {
        return $this->sub;
    }

    public function __toString()
    {
        return $this->full();
    }

    public function randomSub($length = self::DEFAULT_SUB_LENGTH, $random_pattern_mode = 0)
    {
        if (empty($this->sub)) {
            throw new Exception("original domain type not specified");
        }

        if (empty($length)) {
            $length = self::DEFAULT_SUB_LENGTH;
        }

        $length -= strlen($this->sub);
        $prefix_length = floor($length / 2);
        $random_str = md5(microtime(true));

        //Todo: fix it
        if ($random_pattern_mode !== 0) {
            return sprintf(
                "%s%s%s",
                $this->sub[0],
                substr($random_str, 0, $length),
                $this->sub[1]
            );
        } else {
            return sprintf(
                "%s%s%s",
                substr($random_str, 0, $prefix_length),
                $this->sub,
                substr($random_str, $prefix_length, $length - $prefix_length)
            );
        }
    }
}
