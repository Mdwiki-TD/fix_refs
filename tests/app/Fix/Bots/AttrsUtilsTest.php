<?php

use Tests\MyFunctionTest;
use App\Fix\Bots\AttrsUtils;

class attrsUtilsTest extends MyFunctionTest
{
    /**
     * @var array<string, array{0: string, 1: array<string, string>}>
     */
    private array $data = [];

    protected function setUp(): void
    {
        $this->data = [
            'علامات اقتباس مزدوجة' => [
                'name="reuters" group="G1"',
                ['name' => '"reuters"', 'group' => '"G1"']
            ],
            'علامات اقتباس مفردة' => [
                "name='reuters' group='G1'",
                ['name' => "'reuters'", 'group' => "'G1'"]
            ],
            'بدون علامات اقتباس' => [
                'name=reuters group=G1',
                ['name' => 'reuters', 'group' => 'G1']
            ],
            'سمات بدون قيمة' => [
                'disabled name="test"',
                ['disabled' => '', 'name' => '"test"']
            ],
            'مزيج من السمات' => [
                'name="reuters" group=\'G1\' access=public disabled',
                ['name' => '"reuters"', 'group' => "'G1'", 'access' => 'public', 'disabled' => '']
            ],
            'مسافات إضافية' => [
                '  name = "reuters"   group = G1 ',
                ['name' => '"reuters"', 'group' => 'G1']
            ],
            'حالة أحرف مختلفة لأسماء السمات' => [
                'Name="reuters" GROUP="G1"',
                ['name' => '"reuters"', 'group' => '"G1"']
            ],
            'نص فارغ' => [
                '',
                []
            ],
            'سمة مع شرطة سفلية' => [
                'access_date="2023-01-01"',
                ['access_date' => '"2023-01-01"']
            ]
        ];
    }

    public function testParseAttributes(): void {
        foreach ($this->data as $name => $tab) {
            $result = AttrsUtils::parseAttributes($tab[0]);
            $this->assertEquals($tab[1], $result, $name);
        }
    }

    public function testGetAttrs(): void {
        foreach ($this->data as $name => $tab) {
            $result = AttrsUtils::get_attrs($tab[0]);
            $this->assertEquals($tab[1], $result, $name);
        }
    }

    public function testGetAttrsAlt(): void {
        $tests = [
            [
                "text" => 'name="test"',
                "expected" => ["name" => '"test"']
            ],
            [
                "text" => 'name',
                "expected" => ["name" => ""]
            ],
            [
                "text" => 'name="test" group="notes"',
                "expected" => ["name" => '"test"', "group" => '"notes"']
            ],
            [
                "text" => '  name  =  "test"  group  =  "notes"  ',
                "expected" => ["name" => '"test"', "group" => '"notes"']
            ],
            [
                "text" => "name='test'",
                "expected" => ["name" => "'test'"]
            ],
            [
                "text" => "name=test",
                "expected" => ["name" => "test"]
            ],
            [
                "text" => "",
                "expected" => []
            ],
            [
                "text" => 'name="test value"',
                "expected" => ["name" => '"test value"']
            ]
        ];

        foreach ($tests as $test) {
            $result = AttrsUtils::get_attrs($test['text']);
            $this->assertEquals($test['expected'], $result);
        }
    }
}
