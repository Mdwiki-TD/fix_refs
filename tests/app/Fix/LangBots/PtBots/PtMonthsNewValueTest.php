<?php



use Tests\MyFunctionTest;
use App\Fix\Bots\MonthsNewValue;

class ptMonthsNewValueTest extends MyFunctionTest
{
    public function testDateWithFullDate(): void  {
        $this->assertEquals("25 de dezembro 2016", MonthsNewValue::make_date_new_val_pt("25 December 2016"));
    }
    public function testDateWithDayMonthYear(): void  {
        $this->assertEquals("10 de janeiro 2023", MonthsNewValue::make_date_new_val_pt("10 January, 2023"));
    }

    public function testDateWithCommaYear(): void  {
        $this->assertEquals("janeiro 2024", MonthsNewValue::make_date_new_val_pt("January, 2024"));
    }

    public function testDateWithMonthAndYear(): void  {
        $this->assertEquals("novembro 2022", MonthsNewValue::make_date_new_val_pt("November 2022"));
    }

    public function testDateWithDayMonthYearLower(): void  {
        $this->assertEquals("10 de março 2023", MonthsNewValue::make_date_new_val_pt("10 march, 2023"));
    }
    public function testDateWithDayMonthYearNoComma(): void  {
        $this->assertEquals("5 de maio 1999", MonthsNewValue::make_date_new_val_pt("5 May 1999"));
    }

    public function testDateWithMonthDayYear(): void  {
        $this->assertEquals("15 de agosto 2010", MonthsNewValue::make_date_new_val_pt("August 15, 2010"));
    }

    public function testDateWithMonthDayYearNoComma(): void  {
        $this->assertEquals("1 de abril 2020", MonthsNewValue::make_date_new_val_pt("April 1 2020"));
    }

    public function testDateWithSingleDigitDay(): void  {
        $this->assertEquals("3 de junho 2005", MonthsNewValue::make_date_new_val_pt("June 3, 2005"));
    }

    public function testDateWithMixedCase(): void  {
        $this->assertEquals("20 de dezembro 2015", MonthsNewValue::make_date_new_val_pt("DECEMBER 20, 2015"));
    }

    public function testDateWithNoMatchReturnsOriginal(): void  {
        $this->assertEquals("Invalid Date", MonthsNewValue::make_date_new_val_pt("Invalid Date"));
        $this->assertEquals("2023/10/15", MonthsNewValue::make_date_new_val_pt("2023/10/15"));
    }

    public function testDateWithEmptyInput(): void  {
        $this->assertEquals("", MonthsNewValue::make_date_new_val_pt(""));
    }

    public function testDateWithWhitespaceOnly(): void  {
        $this->assertEquals("", MonthsNewValue::make_date_new_val_pt("   "));
    }

    public function testDateWithPartialMonthName(): void  {
        $this->assertEquals("Jan 2023", MonthsNewValue::make_date_new_val_pt("Jan 2023"));
    }

    public function testDateWithNonEnglishMonth(): void  {
        $this->assertEquals("يناير 2023", MonthsNewValue::make_date_new_val_pt("يناير 2023"));
    }

    public function testDateWithDayMonthYearSpaces(): void  {
        $this->assertEquals("7 de julho 2023", MonthsNewValue::make_date_new_val_pt("  7   July   2023  "));
    }

    public function testDateWithMonthDayYearSpaces(): void  {
        $this->assertEquals("12 de setembro 2023", MonthsNewValue::make_date_new_val_pt("September  12,  2023"));
    }
}
