<?php
use Lybc\PhpGB2260\GB2260;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

class TestCase extends PHPUnitTestCase
{
    function testJudgement()
    {
        $provinceAreaCode = 360000; // 山西省
        $cityAreaCode = 360300;     // 萍乡市
        $districtAreaCode = 360313; // 湘东区
        $this->assertTrue(GB2260::areaCode($provinceAreaCode)->isProvision());
        $this->assertFalse(GB2260::areaCode($provinceAreaCode)->isCity());
        $this->assertFalse(GB2260::areaCode($provinceAreaCode)->isDistrict());

        $this->assertTrue(GB2260::areaCode($cityAreaCode)->isCity());
        $this->assertFalse(GB2260::areaCode($cityAreaCode)->isProvision());
        $this->assertFalse(GB2260::areaCode($cityAreaCode)->isDistrict());

        $this->assertTrue(GB2260::areaCode($districtAreaCode)->isDistrict());
        $this->assertFalse(GB2260::areaCode($districtAreaCode)->isProvision());
        $this->assertFalse(GB2260::areaCode($districtAreaCode)->isCity());
    }

    function testGetProvince()
    {
        // 萍乡市
        $this->assertEquals(GB2260::areaCode(360300)->getProvince(), '江西省');
        // 湘东区
        $this->assertEquals(GB2260::areaCode(360313)->getProvince(), '江西省');
    }

    function testGetCity()
    {
        $this->assertEquals(GB2260::areaCode(360313)->getCity(), '萍乡市');
        $this->assertEquals(GB2260::areaCode(360300)->getCity(), '萍乡市');
        $this->assertArraySubset(GB2260::areaCode(360000)->getCity(), [
            360100 => '南昌市',
            360200 => '景德镇市',
            360300 => '萍乡市',
            360400 => '九江市',
            360500 => '新余市',
            360600 => '鹰潭市',
            360700 => '赣州市',
            360800 => '吉安市',
            360900 => '宜春市',
            361000 => '抚州市',
            361100 => '上饶市'
        ]);
    }

    function testGetDistrict()
    {
        $this->assertEquals(GB2260::areaCode(360313)->getDistrict(), '湘东区');
        $this->assertArraySubset(GB2260::areaCode(360300)->getDistrict(), [
            360301 => "市辖区",
            360302 => "安源区",
            360313 => "湘东区",
            360321 => "莲花县",
            360322 => "上栗县",
            360323 => "芦溪县",
        ]);
        $this->assertArraySubset(GB2260::areaCode(360000)->getDistrict(), [
            360101 => "市辖区",
            360102 => "东湖区",
            360103 => "西湖区",
            360104 => "青云谱区",
            360105 => "湾里区",
            360111 => "青山湖区",
            360112 => "新建区",
            360121 => "南昌县",
            360123 => "安义县",
            360124 => "进贤县",
            360201 => "市辖区",
            360202 => "昌江区",
            360203 => "珠山区",
            360222 => "浮梁县",
            360281 => "乐平市",
            360301 => "市辖区",
            360302 => "安源区",
            360313 => "湘东区",
            360321 => "莲花县",
            360322 => "上栗县",
            360323 => "芦溪县",
            360401 => "市辖区",
            360402 => "濂溪区",
            360403 => "浔阳区",
            360421 => "九江县",
            360423 => "武宁县",
            360424 => "修水县",
            360425 => "永修县",
            360426 => "德安县",
            360428 => "都昌县",
            360429 => "湖口县",
            360430 => "彭泽县",
            360481 => "瑞昌市",
            360482 => "共青城市",
            360483 => "庐山市",
            360501 => "市辖区",
            360502 => "渝水区",
            360521 => "分宜县",
            360601 => "市辖区",
            360602 => "月湖区",
            360622 => "余江县",
            360681 => "贵溪市",
            360701 => "市辖区",
            360702 => "章贡区",
            360703 => "南康区",
            360721 => "赣县",
            360722 => "信丰县",
            360723 => "大余县",
            360724 => "上犹县",
            360725 => "崇义县",
            360726 => "安远县",
            360727 => "龙南县",
            360728 => "定南县",
            360729 => "全南县",
            360730 => "宁都县",
            360731 => "于都县",
            360732 => "兴国县",
            360733 => "会昌县",
            360734 => "寻乌县",
            360735 => "石城县",
            360781 => "瑞金市",
            360801 => "市辖区",
            360802 => "吉州区",
            360803 => "青原区",
            360821 => "吉安县",
            360822 => "吉水县",
            360823 => "峡江县",
            360824 => "新干县",
            360825 => "永丰县",
            360826 => "泰和县",
            360827 => "遂川县",
            360828 => "万安县",
            360829 => "安福县",
            360830 => "永新县",
            360881 => "井冈山市",
            360901 => "市辖区",
            360902 => "袁州区",
            360921 => "奉新县",
            360922 => "万载县",
            360923 => "上高县",
            360924 => "宜丰县",
            360925 => "靖安县",
            360926 => "铜鼓县",
            360981 => "丰城市",
            360982 => "樟树市",
            360983 => "高安市",
            361001 => "市辖区",
            361002 => "临川区",
            361021 => "南城县",
            361022 => "黎川县",
            361023 => "南丰县",
            361024 => "崇仁县",
            361025 => "乐安县",
            361026 => "宜黄县",
            361027 => "金溪县",
            361028 => "资溪县",
            361029 => "东乡县",
            361030 => "广昌县",
            361101 => "市辖区",
            361102 => "信州区",
            361103 => "广丰区",
            361121 => "上饶县",
            361123 => "玉山县",
            361124 => "铅山县",
            361125 => "横峰县",
            361126 => "弋阳县",
            361127 => "余干县",
            361128 => "鄱阳县",
            361129 => "万年县",
            361130 => "婺源县",
            361181 => "德兴市",

        ]);
    }

    function testOutput()
    {
        $this->assertEquals(
            GB2260::areaCode(360313)->format('{p}, {c}, {d}'),
            '江西省, 萍乡市, 湘东区'
        );

        $this->assertEquals(
            GB2260::areaCode(360300)->format('{p}, {c}, {d}'),
            '江西省, 萍乡市, {d}'
        );
        $this->assertEquals(
            GB2260::areaCode(360000)->format('{p}, {c}, {d}'),
            '江西省, {c}, {d}'
        );

        $area = new GB2260(360313);
        $this->assertEquals($area, '湘东区');
    }

    function testException()
    {
        $this->expectException(\Lybc\PhpGB2260\InvalidCodeException::class);
        $area = new GB2260('abcdefg');
        GB2260::areaCode('abcdefg')->getProvince();
        GB2260::areaCode('abcdefg')->getCity();
        GB2260::areaCode('abcdefg')->getDistrict();
    }
}