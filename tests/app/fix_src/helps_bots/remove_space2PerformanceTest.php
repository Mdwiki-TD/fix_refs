<?php


use FixRefs\Tests\MyFunctionTest;
use function WpRefs\RemoveSpace\remove_spaces_between_ref_and_punctuation;

class remove_space2PerformanceTest extends MyFunctionTest
{
    public function testStressWithOneMillionRefs()
    {
        $dots = ['.', '։', '。', '।', ':'];
        $parts = [];

        // توليد مليون ref
        for ($i = 0; $i < 1000000; $i++) {
            $dot = $dots[$i % count($dots)];
            if ($i % 2 === 0) {
                $parts[] = "<ref name=\"T$i\" /> $dot";
            } else {
                $parts[] = "</ref> $dot";
            }
        }

        $input = implode(' ', $parts);

        $start = microtime(true);
        $output = remove_spaces_between_ref_and_punctuation($input, 'multi');
        $duration = microtime(true) - $start;

        echo "Stress test duration: {$duration} seconds\n";

        // نتأكد أن الزمن معقول (مثلاً أقل من 10 ثوانٍ لمليون حالة)
        $this->assertLessThan(20.0, $duration, "Function too slow on 1M refs");

        // تحقق من أنه لا توجد مسافات زائدة
        foreach ($dots as $dot) {
            $this->assertStringNotContainsString(" /> $dot", $output);
            $this->assertStringNotContainsString("</ref> $dot", $output);
        }
    }
}
