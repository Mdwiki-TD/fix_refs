<?php



use App\Tests\MyFunctionTest;
use function WpRefs\Bots\AttrsUtils\parseAttributes;
use function WpRefs\Bots\AttrsUtils\get_attrs;

class attrs_utilsTest extends MyFunctionTest
{

    private $data = [];

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

    /**
     * @dataProvider attributesProvider
     */
    public function testParseAttributes()
    {
        foreach ($this->data as $name => $tab) {
            $result = parseAttributes($tab[0]);
            $this->assertEquals($tab[1], $result, $name);
        }
    }

    /**
     * @dataProvider attributesProvider
     */
    public function testGetAttrs()
    {
        foreach ($this->data as $name => $tab) {
            $result = get_attrs($tab[0]);
            $this->assertEquals($tab[1], $result, $name);
        }
    }
    // اختبارات دالة get_attrs
    public function testGetAttrsAlt()
    {
        $tests = [
            // حالة: سمة واحدة مع قيمة
            [
                "text" => 'name="test"',
                "expected" => ["name" => '"test"']
            ],
            // حالة: سمة واحدة بدون قيمة
            [
                "text" => 'name',
                "expected" => ["name" => ""]
            ],
            // حالة: سمات متعددة
            [
                "text" => 'name="test" group="notes"',
                "expected" => ["name" => '"test"', "group" => '"notes"']
            ],
            // حالة: سمات مع مسافات زائدة
            [
                "text" => '  name  =  "test"  group  =  "notes"  ',
                "expected" => ["name" => '"test"', "group" => '"notes"']
            ],
            // حالة: سمة بعلامات تنصيص مفردة
            [
                "text" => "name='test'",
                "expected" => ["name" => "'test'"]
            ],
            // حالة: سمة بدون علامات تنصيص
            [
                "text" => "name=test",
                "expected" => ["name" => "test"]
            ],
            // حالة: نص فارغ
            [
                "text" => "",
                "expected" => []
            ],
            // حالة: سمة تحتوي على مسافات في القيمة
            [
                "text" => 'name="test value"',
                "expected" => ["name" => '"test value"']
            ]
        ];

        foreach ($tests as $test) {
            $result = get_attrs($test['text']);
            $this->assertEquals($test['expected'], $result);
        }
    }
}
