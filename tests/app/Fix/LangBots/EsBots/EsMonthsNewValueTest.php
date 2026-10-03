<?php



use Tests\MyFunctionTest;
use function App\Fix\Bots\MonthNewValue\make_date_new_val_es;

class esMonthsNewValueTest extends MyFunctionTest
{
    public function testDateWithFullDate()
    {
        $this->assertEquals("25 de julio de 1975", make_date_new_val_es("July 25, 1975"));
    }

    public function testDateWithMonthAndYear()
    {
        $this->assertEquals("noviembre de 2022", make_date_new_val_es("November 2022"));
    }

    public function testDateWithCommaYear()
    {
        $this->assertEquals("enero de 2024", make_date_new_val_es("January, 2024"));
    }

    public function testDateWithDayMonthYear()
    {
        $this->assertEquals("10 de marzo de 2023", make_date_new_val_es("10 March, 2023"));
    }
    public function testDateWithDayMonthYearLower()
    {
        $this->assertEquals("10 de marzo de 2023", make_date_new_val_es("10 march, 2023"));
    }
    public function testDateWithDayMonthYearNoComma()
    {
        $this->assertEquals("5 de mayo de 1999", make_date_new_val_es("5 May 1999"));
    }

    public function testDateWithMonthDayYear()
    {
        $this->assertEquals("15 de agosto de 2010", make_date_new_val_es("August 15, 2010"));
    }

    public function testDateWithMonthDayYearNoComma()
    {
        $this->assertEquals("1 de abril de 2020", make_date_new_val_es("April 1 2020"));
    }

    public function testDateWithSingleDigitDay()
    {
        $this->assertEquals("3 de junio de 2005", make_date_new_val_es("June 3, 2005"));
    }

    public function testDateWithMixedCase()
    {
        $this->assertEquals("20 de diciembre de 2015", make_date_new_val_es("DECEMBER 20, 2015"));
    }

    public function testDateWithNoMatchReturnsOriginal()
    {
        $this->assertEquals("Invalid Date", make_date_new_val_es("Invalid Date"));
        $this->assertEquals("2023/10/15", make_date_new_val_es("2023/10/15"));
    }

    public function testDateWithEmptyInput()
    {
        $this->assertEquals("", make_date_new_val_es(""));
    }

    public function testDateWithWhitespaceOnly()
    {
        $this->assertEquals("", make_date_new_val_es("   "));
    }

    public function testDateWithPartialMonthName()
    {
        $this->assertEquals("Jan 2023", make_date_new_val_es("Jan 2023"));
    }

    public function testDateWithNonEnglishMonth()
    {
        $this->assertEquals("يناير 2023", make_date_new_val_es("يناير 2023"));
    }

    public function testDateWithDayMonthYearSpaces()
    {
        $this->assertEquals("7 de julio de 2023", make_date_new_val_es("  7   July   2023  "));
    }

    public function testDateWithMonthDayYearSpaces()
    {
        $this->assertEquals("12 de septiembre de 2023", make_date_new_val_es("September  12,  2023"));
    }
}
