<?php
namespace Lybc\PhpGB2260;

class GB2260
{
    protected $currentCode;
    protected $data = [];

    protected $provisionText;
    protected $cityText;
    protected $districtText;

    /**
     * @expectedException InvalidCodeException
     */
    public function __construct($currentCode)
    {
        $this->data = require __DIR__ . '/data.php';
        if (! array_key_exists($currentCode, $this->data)) {
            throw new InvalidCodeException('invalid code');
        }
        $this->currentCode = $currentCode;
    }

    public static function areaCode($code)
    {
        return new self($code);
    }

    /**
     * 国标代码后4位为0的为省或直辖市
     * @return bool
     */
    public function isProvision()
    {
        return substr($this->currentCode, -4, 4) === '0000';
    }

    /**
     * 国标代码后两位为0的为市
     * @return bool
     */
    public function isCity()
    {
        return ! $this->isProvision() && substr($this->currentCode, -2, 2) === '00';
    }

    /**
     * 国标代码不属于省且不属于市的为区县代码
     */
    public function isDistrict()
    {
        return ! $this->isProvision() && ! $this->isCity();
    }

    public function getProvince()
    {
        $provinceCode = intval(substr($this->currentCode, 0, 2) . '0000');
        $this->checkInvalidCode($provinceCode);
        return $this->data[$provinceCode];
    }

    public function getCity()
    {
        if ($this->isProvision()) {
            return array_filter($this->data, function($code) {
                return $code > $this->currentCode
                    && $code < $this->currentCode + 10000
                    &&  substr($code, -2, 2) === '00';
            }, ARRAY_FILTER_USE_KEY);
        } else {
            $cityCode = intval(substr($this->currentCode, 0, 4) . '00');
            $this->checkInvalidCode($cityCode);
            return $this->data[$cityCode];
        }
    }

    /**
     * @expectedException InvalidCodeException
     */
    public function getDistrict()
    {
        if ($this->isDistrict()) {
            return $this->data[$this->currentCode];
        } else if ($this->isCity()) {
            return array_filter($this->data, function($code) {
                return $code > $this->currentCode
                    && $code < $this->currentCode + 100
                    &&  substr($code, -2, 2) !== '00';
            }, ARRAY_FILTER_USE_KEY);
        } else if ($this->isProvision()) {
            return array_filter($this->data, function($code) {
                return $code > $this->currentCode
                    && $code < $this->currentCode + 10000
                    &&  substr($code, -2, 2) !== '00';
            }, ARRAY_FILTER_USE_KEY);
        }
        throw new InvalidCodeException('invalid code: '.$this->currentCode);
    }

    /**
     * @expectedException InvalidCodeException
     */
    function checkInvalidCode($code = null)
    {
        if (is_null($code)) {
            $code = $this->currentCode;
        }
        if (! array_key_exists($code, $this->data)) {
            throw new InvalidCodeException('gb2260 code is invalid: '.$code);
        }
    }

    public function format($formatString)
    {
        if ($this->isProvision()) {
            $result = str_replace('{p}', $this->getProvince(), $formatString);
        } else if ($this->isCity()) {
            $result = str_replace(['{p}', '{c}'], [$this->getProvince(), $this->getCity()], $formatString);
        } else {
            $result = str_replace(
                ['{p}', '{c}', '{d}'],
                [$this->getProvince(), $this->getCity(), $this->getDistrict()],
                $formatString
            );
        }
        return $result;
    }

    public function __toString()
    {
        return (string) $this->data[$this->currentCode];
    }


}