from __future__ import annotations

from src.bots.months_new_value import make_date_new_val_es


class es_months_new_valueTest:
    def test_date_with_full_date(self) -> None:
        assert make_date_new_val_es("July 25, 1975") == "25 de julio de 1975"

    def test_date_with_month_and_year(self) -> None:
        assert make_date_new_val_es("November 2022") == "noviembre de 2022"

    def test_date_with_comma_year(self) -> None:
        assert make_date_new_val_es("January, 2024") == "enero de 2024"

    def test_date_with_day_month_year(self) -> None:
        assert make_date_new_val_es("10 March, 2023") == "10 de marzo de 2023"

    def test_date_with_day_month_year_lower(self) -> None:
        assert make_date_new_val_es("10 march, 2023") == "10 de marzo de 2023"

    def test_date_with_day_month_year_no_comma(self) -> None:
        assert make_date_new_val_es("5 May 1999") == "5 de mayo de 1999"

    def test_date_with_month_day_year(self) -> None:
        assert make_date_new_val_es("August 15, 2010") == "15 de agosto de 2010"

    def test_date_with_month_day_year_no_comma(self) -> None:
        assert make_date_new_val_es("April 1 2020") == "1 de abril de 2020"

    def test_date_with_single_digit_day(self) -> None:
        assert make_date_new_val_es("June 3, 2005") == "3 de junio de 2005"

    def test_date_with_mixed_case(self) -> None:
        assert make_date_new_val_es("DECEMBER 20, 2015") == "20 de diciembre de 2015"

    def test_date_with_no_match_returns_original(self) -> None:
        assert make_date_new_val_es("Invalid Date") == "Invalid Date"
        assert make_date_new_val_es("2023/10/15") == "2023/10/15"

    def test_date_with_empty_input(self) -> None:
        assert make_date_new_val_es("") == ""

    def test_date_with_whitespace_only(self) -> None:
        assert make_date_new_val_es("   ") == ""

    def test_date_with_partial_month_name(self) -> None:
        assert make_date_new_val_es("Jan 2023") == "Jan 2023"

    def test_date_with_non_english_month(self) -> None:
        assert make_date_new_val_es("يناير 2023") == "يناير 2023"

    def test_date_with_day_month_year_spaces(self) -> None:
        assert make_date_new_val_es("  7   July   2023  ") == "7 de julio de 2023"

    def test_date_with_month_day_year_spaces(self) -> None:
        assert make_date_new_val_es("September  12,  2023") == "12 de septiembre de 2023"
