<?php
namespace Lybc\PhpGB2260;

class GB2260
{
    protected $currentCode;
    protected $data = [];

    public function __construct($currentCode)
    {
        $this->data = require __DIR__ . 'data.php';
        if (! array_key_exists($currentCode, $this->data)) {
            throw new InvalidCodeException('invalid code');
        }
        $this->currentCode = $currentCode;
    }

    public static function areaCode($code)
    {
        return new self($code);
    }

    public function isProvision()
    {

    }

    public function isCity()
    {

    }

    public function isDistrict()
    {

    }

    public function format($formatString)
    {

    }

    public function __toString()
    {
        return '';
    }


}