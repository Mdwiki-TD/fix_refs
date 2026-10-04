<?php



use Tests\MyFunctionTest;
use App\Fix\Bots\MonthsNewValue;

class esMonthsNewValueTest extends MyFunctionTest
{
    public function testDateWithFullDate(): void  {
        $this->assertEquals("25 de julio de 1975", MonthsNewValue::make_date_new_val_es("July 25, 1975"));
    }

    public function testDateWithMonthAndYear(): void  {
        $this->assertEquals("noviembre de 2022", MonthsNewValue::make_date_new_val_es("November 2022"));
    }

    public function testDateWithCommaYear(): void  {
        $this->assertEquals("enero de 2024", MonthsNewValue::make_date_new_val_es("January, 2024"));
    }

    public function testDateWithDayMonthYear(): void  {
        $this->assertEquals("10 de marzo de 2023", MonthsNewValue::make_date_new_val_es("10 March, 2023"));
    }
    public function testDateWithDayMonthYearLower(): void  {
        $this->assertEquals("10 de marzo de 2023", MonthsNewValue::make_date_new_val_es("10 march, 2023"));
    }
    public function testDateWithDayMonthYearNoComma(): void  {
        $this->assertEquals("5 de mayo de 1999", MonthsNewValue::make_date_new_val_es("5 May 1999"));
    }

    public function testDateWithMonthDayYear(): void  {
        $this->assertEquals("15 de agosto de 2010", MonthsNewValue::make_date_new_val_es("August 15, 2010"));
    }

    public function testDateWithMonthDayYearNoComma(): void  {
        $this->assertEquals("1 de abril de 2020", MonthsNewValue::make_date_new_val_es("April 1 2020"));
    }

    public function testDateWithSingleDigitDay(): void  {
        $this->assertEquals("3 de junio de 2005", MonthsNewValue::make_date_new_val_es("June 3, 2005"));
    }

    public function testDateWithMixedCase(): void  {
        $this->assertEquals("20 de diciembre de 2015", MonthsNewValue::make_date_new_val_es("DECEMBER 20, 2015"));
    }

    public function testDateWithNoMatchReturnsOriginal(): void  {
        $this->assertEquals("Invalid Date", MonthsNewValue::make_date_new_val_es("Invalid Date"));
        $this->assertEquals("2023/10/15", MonthsNewValue::make_date_new_val_es("2023/10/15"));
    }

    public function testDateWithEmptyInput(): void  {
        $this->assertEquals("", MonthsNewValue::make_date_new_val_es(""));
    }

    public function testDateWithWhitespaceOnly(): void  {
        $this->assertEquals("", MonthsNewValue::make_date_new_val_es("   "));
    }

    public function testDateWithPartialMonthName(): void  {
        $this->assertEquals("Jan 2023", MonthsNewValue::make_date_new_val_es("Jan 2023"));
    }

    public function testDateWithNonEnglishMonth(): void  {
        $this->assertEquals("يناير 2023", MonthsNewValue::make_date_new_val_es("يناير 2023"));
    }

    public function testDateWithDayMonthYearSpaces(): void  {
        $this->assertEquals("7 de julio de 2023", MonthsNewValue::make_date_new_val_es("  7   July   2023  "));
    }

    public function testDateWithMonthDayYearSpaces(): void  {
        $this->assertEquals("12 de septiembre de 2023", MonthsNewValue::make_date_new_val_es("September  12,  2023"));
    }
}
