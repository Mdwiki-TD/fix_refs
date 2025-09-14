<?php



use PHPUnit\Framework\TestCase;
use function WpRefs\Bots\MonthNewValue\make_date_new_val_pt;

class pt_months_new_valueTest extends TestCase
{
    public function test_date_with_full_date()
    {
        $this->assertEquals("25 de dezembro 2016", make_date_new_val_pt("25 December 2016"));
    }
    public function test_date_with_day_month_year()
    {
        $this->assertEquals("10 de janeiro 2023", make_date_new_val_pt("10 January, 2023"));
    }

    public function test_date_with_comma_year()
    {
        $this->assertEquals("janeiro 2024", make_date_new_val_pt("January, 2024"));
    }

    public function test_date_with_month_and_year()
    {
        $this->assertEquals("novembro 2022", make_date_new_val_pt("November 2022"));
    }

    public function test_date_with_day_month_year_lower()
    {
        $this->assertEquals("10 de março 2023", make_date_new_val_pt("10 march, 2023"));
    }
    public function test_date_with_day_month_year_no_comma()
    {
        $this->assertEquals("5 de maio 1999", make_date_new_val_pt("5 May 1999"));
    }

    public function test_date_with_month_day_year()
    {
        $this->assertEquals("15 de agosto 2010", make_date_new_val_pt("August 15, 2010"));
    }

    public function test_date_with_month_day_year_no_comma()
    {
        $this->assertEquals("1 de abril 2020", make_date_new_val_pt("April 1 2020"));
    }

    public function test_date_with_single_digit_day()
    {
        $this->assertEquals("3 de junho 2005", make_date_new_val_pt("June 3, 2005"));
    }

    public function test_date_with_mixed_case()
    {
        $this->assertEquals("20 de dezembro 2015", make_date_new_val_pt("DECEMBER 20, 2015"));
    }

    public function test_date_with_no_match_returns_original()
    {
        $this->assertEquals("Invalid Date", make_date_new_val_pt("Invalid Date"));
        $this->assertEquals("2023/10/15", make_date_new_val_pt("2023/10/15"));
    }

    public function test_date_with_empty_input()
    {
        $this->assertEquals("", make_date_new_val_pt(""));
    }

    public function test_date_with_whitespace_only()
    {
        $this->assertEquals("", make_date_new_val_pt("   "));
    }

    public function test_date_with_partial_month_name()
    {
        $this->assertEquals("Jan 2023", make_date_new_val_pt("Jan 2023"));
    }

    public function test_date_with_non_english_month()
    {
        $this->assertEquals("يناير 2023", make_date_new_val_pt("يناير 2023"));
    }

    public function test_date_with_day_month_year_spaces()
    {
        $this->assertEquals("7 de julho 2023", make_date_new_val_pt("  7   July   2023  "));
    }

    public function test_date_with_month_day_year_spaces()
    {
        $this->assertEquals("12 de setembro 2023", make_date_new_val_pt("September  12,  2023"));
    }
}
