# php-gb2260

中华人民共和国国家标准 GB/T 2260 行政区划代码 php 封装


## Installing

`composer require lybc/php-gb2260`

## Usage
```php
use Lybc\PhpGB2260\GB2260;

$provinceAreaCode = 360000; // 江西省
$cityAreaCode = 360300;     // 萍乡市
$districtAreaCode = 360313; // 湘东区

// 判断 code 是否省市区
GB2260::areaCode($provinceAreaCode)->isProvision();
GB2260::areaCode($provinceAreaCode)->isCity();
GB2260::areaCode($provinceAreaCode)->isDistrict();

// 根据 code 获取省市区名称
GB2260::areaCode(360300)->getProvince(); // 根据市号获取所在省的名称
GB2260::areaCode(360313)->getCity(); // 根据区号获取所在市的名称
GB2260::areaCode(360313)->getDistrict(); // 根据区号获取名称

GB2260::areaCode(360300)->getDistrict(); // 根据市号获取所有区的名称，为 code => name 的数组

// 按照自定义格式输出
GB2260::areaCode(360300)->format('{p}, {c}, {d}'); // 输出江西省, 萍乡市, 湘东区, 只支持{p}/{c}/{d}


```

## License

MIT

## Thanks

[package-builder @ overtrue](https://github.com/overtrue/package-builder)

[gb2260](https://github.com/cn/GB2260)

[gb2260.php](https://github.com/cn/GB2260.php)
