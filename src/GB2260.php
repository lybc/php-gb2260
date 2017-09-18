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
        $this->data = require __DIR__ . '/../cn-gb2260.php';
        if (! array_key_exists($currentCode, $this->data) || is_string($currentCode)) {
            throw new InvalidCodeException('invalid code or code is not number');
        }
        $this->currentCode = $currentCode;
    }

    public static function areaCode($code)
    {
        return new self($code);
    }

    /**
     * 获取当前代码的名称
     * @return mixed
     */
    public function getCurrent()
    {
        return $this->data[$this->currentCode];
    }

    /**
     * 国标代码后4位为0的为省或直辖市
     * @return bool
     */
    public function isProvince()
    {
        return substr($this->currentCode, -4, 4) === '0000';
    }

    /**
     * 国标代码后两位为0的为市
     * @return bool
     */
    public function isCity()
    {
        return ! $this->isProvince() && substr($this->currentCode, -2, 2) === '00';
    }

    /**
     * 国标代码不属于省且不属于市的为区县代码
     */
    public function isDistrict()
    {
        return ! $this->isProvince() && ! $this->isCity();
    }

    /**
     * 根据当前的国标代码获取所在省的名称
     * @return mixed
     */
    public function getProvince()
    {
        $provinceCode = intval(substr($this->currentCode, 0, 2) . '0000');
        $this->checkInvalidCode($provinceCode);
        return $this->data[$provinceCode];
    }

    /**
     * 根据当前的国标代码获取所在市的名称，如代码为省代码，则得到该省所有市的数组
     * @return mixed
     */
    public function getCity()
    {
        if ($this->isProvince()) {
            return array_filter($this->data, function ($code) {
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
     * 根据当前的国标代码获取所在市的名称，如代码为省或市代码，则得到所有区的数组
     * @return array|mixed
     * @throws InvalidCodeException
     */
    public function getDistrict()
    {
        if ($this->isDistrict()) {
            return $this->data[$this->currentCode];
        } elseif ($this->isCity()) {
            return array_filter($this->data, function ($code) {
                return $code > $this->currentCode
                    && $code < $this->currentCode + 100
                    &&  substr($code, -2, 2) !== '00';
            }, ARRAY_FILTER_USE_KEY);
        } elseif ($this->isProvince()) {
            return array_filter($this->data, function ($code) {
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
    public function checkInvalidCode($code = null)
    {
        if (is_null($code)) {
            $code = $this->currentCode;
        }
        if (! array_key_exists($code, $this->data)) {
            throw new InvalidCodeException('gb2260 code is invalid: '.$code);
        }
    }

    /**
     * 根据传入的字符串格式输出
     * @param $formatString
     * @return mixed
     */
    public function format($formatString)
    {
        if ($this->isProvince()) {
            $result = str_replace('{p}', $this->getProvince(), $formatString);
        } elseif ($this->isCity()) {
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
