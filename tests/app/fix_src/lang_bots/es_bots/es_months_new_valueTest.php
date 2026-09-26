<?php



use FixRefs\Tests\MyFunctionTest;
use function WpRefs\Bots\MonthNewValue\make_date_new_val_es;

class es_months_new_valueTest extends MyFunctionTest
{
    public function test_date_with_full_date()
    {
        $this->assertEquals("25 de julio de 1975", make_date_new_val_es("July 25, 1975"));
    }

    public function test_date_with_month_and_year()
    {
        $this->assertEquals("noviembre de 2022", make_date_new_val_es("November 2022"));
    }

    public function test_date_with_comma_year()
    {
        $this->assertEquals("enero de 2024", make_date_new_val_es("January, 2024"));
    }

    public function test_date_with_day_month_year()
    {
        $this->assertEquals("10 de marzo de 2023", make_date_new_val_es("10 March, 2023"));
    }
    public function test_date_with_day_month_year_lower()
    {
        $this->assertEquals("10 de marzo de 2023", make_date_new_val_es("10 march, 2023"));
    }
    public function test_date_with_day_month_year_no_comma()
    {
        $this->assertEquals("5 de mayo de 1999", make_date_new_val_es("5 May 1999"));
    }

    public function test_date_with_month_day_year()
    {
        $this->assertEquals("15 de agosto de 2010", make_date_new_val_es("August 15, 2010"));
    }

    public function test_date_with_month_day_year_no_comma()
    {
        $this->assertEquals("1 de abril de 2020", make_date_new_val_es("April 1 2020"));
    }

    public function test_date_with_single_digit_day()
    {
        $this->assertEquals("3 de junio de 2005", make_date_new_val_es("June 3, 2005"));
    }

    public function test_date_with_mixed_case()
    {
        $this->assertEquals("20 de diciembre de 2015", make_date_new_val_es("DECEMBER 20, 2015"));
    }

    public function test_date_with_no_match_returns_original()
    {
        $this->assertEquals("Invalid Date", make_date_new_val_es("Invalid Date"));
        $this->assertEquals("2023/10/15", make_date_new_val_es("2023/10/15"));
    }

    public function test_date_with_empty_input()
    {
        $this->assertEquals("", make_date_new_val_es(""));
    }

    public function test_date_with_whitespace_only()
    {
        $this->assertEquals("", make_date_new_val_es("   "));
    }

    public function test_date_with_partial_month_name()
    {
        $this->assertEquals("Jan 2023", make_date_new_val_es("Jan 2023"));
    }

    public function test_date_with_non_english_month()
    {
        $this->assertEquals("يناير 2023", make_date_new_val_es("يناير 2023"));
    }

    public function test_date_with_day_month_year_spaces()
    {
        $this->assertEquals("7 de julio de 2023", make_date_new_val_es("  7   July   2023  "));
    }

    public function test_date_with_month_day_year_spaces()
    {
        $this->assertEquals("12 de septiembre de 2023", make_date_new_val_es("September  12,  2023"));
    }
}
