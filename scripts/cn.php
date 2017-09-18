<?php

if (version_compare(PHP_VERSION, '7.0.0', '<')) {
    echo 'Please use php 7 to run.', PHP_EOL;
    exit(1);
}

$sourcePage = $argv[1] ?? false;

if (! $sourcePage) {
    echo 'Please enter http://www.mca.gov.cn/article/sj/tjbz/a/ data collection address.', PHP_EOL;
    exit(1);
}

echo PHP_EOL;
echo 'Download source for ', $sourcePage, PHP_EOL, PHP_EOL;

$source = file_get_contents($sourcePage);

echo 'Matching...', PHP_EOL, PHP_EOL;

preg_match_all('/\<tr.*?\>(.*?)\<\/tr\>/is', $source, $matches);


echo 'Analyzing:', PHP_EOL;
$data = [];
foreach ($matches[1] as $tds) {

    $isMatch = preg_match('/\<td.*?\>.*?\<\/td\>.*?\<td.*?\>(\w+)\<\/td\>.*?\<td.*?\>(.*?)\<\/td\>.*?/is', $tds, $match);

    if ($isMatch) {
        echo '.';
        $data[$match[1]] = $match[2];

        continue;
    }
}

echo sprintf('( Total: %d )', count($data)), PHP_EOL, PHP_EOL;

echo 'Writing data to data/cn.php file...', PHP_EOL;

$phpHeade = '<?php' . PHP_EOL . 'return ';
file_put_contents(dirname(__DIR__).'/data/cn-gb2260.php', $phpHeade . var_export($data, true).';'.PHP_EOL);

echo 'Data write is complete.', PHP_EOL;

exit(0);
