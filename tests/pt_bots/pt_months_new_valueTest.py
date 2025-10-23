from __future__ import annotations

from src.bots.months_new_value import make_date_new_val_pt


class pt_months_new_valueTest:
    def test_date_with_full_date(self) -> None:
        assert make_date_new_val_pt("25 December 2016") == "25 de dezembro 2016"

    def test_date_with_day_month_year(self) -> None:
        assert make_date_new_val_pt("10 January, 2023") == "10 de janeiro 2023"

    def test_date_with_comma_year(self) -> None:
        assert make_date_new_val_pt("January, 2024") == "janeiro 2024"

    def test_date_with_month_and_year(self) -> None:
        assert make_date_new_val_pt("November 2022") == "novembro 2022"

    def test_date_with_day_month_year_lower(self) -> None:
        assert make_date_new_val_pt("10 march, 2023") == "10 de março 2023"

    def test_date_with_day_month_year_no_comma(self) -> None:
        assert make_date_new_val_pt("5 May 1999") == "5 de maio 1999"

    def test_date_with_month_day_year(self) -> None:
        assert make_date_new_val_pt("August 15, 2010") == "15 de agosto 2010"

    def test_date_with_month_day_year_no_comma(self) -> None:
        assert make_date_new_val_pt("April 1 2020") == "1 de abril 2020"

    def test_date_with_single_digit_day(self) -> None:
        assert make_date_new_val_pt("June 3, 2005") == "3 de junho 2005"

    def test_date_with_mixed_case(self) -> None:
        assert make_date_new_val_pt("DECEMBER 20, 2015") == "20 de dezembro 2015"

    def test_date_with_no_match_returns_original(self) -> None:
        assert make_date_new_val_pt("Invalid Date") == "Invalid Date"
        assert make_date_new_val_pt("2023/10/15") == "2023/10/15"

    def test_date_with_empty_input(self) -> None:
        assert make_date_new_val_pt("") == ""

    def test_date_with_whitespace_only(self) -> None:
        assert make_date_new_val_pt("   ") == ""

    def test_date_with_partial_month_name(self) -> None:
        assert make_date_new_val_pt("Jan 2023") == "Jan 2023"

    def test_date_with_non_english_month(self) -> None:
        assert make_date_new_val_pt("يناير 2023") == "يناير 2023"

    def test_date_with_day_month_year_spaces(self) -> None:
        assert make_date_new_val_pt("  7   July   2023  ") == "7 de julho 2023"

    def test_date_with_month_day_year_spaces(self) -> None:
        assert make_date_new_val_pt("September  12,  2023") == "12 de setembro 2023"
