from __future__ import annotations

from src.bots.attrs_utils import get_attrs, parseAttributes


class TestAttrsUtils:
    def setup_method(self) -> None:
        self.data = {
            "علامات اقتباس مزدوجة": (
                'name="reuters" group="G1"',
                {"name": '"reuters"', "group": '"G1"'},
            ),
            "علامات اقتباس مفردة": (
                "name='reuters' group='G1'",
                {"name": "'reuters'", "group": "'G1'"},
            ),
            "بدون علامات اقتباس": (
                "name=reuters group=G1",
                {"name": "", "group": ""},
            ),
            "سمات بدون قيمة": (
                'disabled name="test"',
                {"disabled": "", "name": '"test"'},
            ),
            "مزيج من السمات": (
                'name="reuters" group=\'G1\' access=public disabled',
                {
                    "name": '"reuters"',
                    "group": "'G1'",
                    "access": "",
                    "disabled": "",
                },
            ),
            "مسافات إضافية": (
                '  name = "reuters"   group = G1 ',
                {"name": '"reuters"', "group": "", "g1": ""},
            ),
            "حالة أحرف مختلفة لأسماء السمات": (
                'Name="reuters" GROUP="G1"',
                {"name": '"reuters"', "group": '"G1"'},
            ),
            "نص فارغ": (
                "",
                {},
            ),
            "سمة مع شرطة سفلية": (
                'access_date="2023-01-01"',
                {"access_date": '"2023-01-01"'},
            ),
        }

    def test_parse_attributes(self) -> None:
        for name, (text, expected) in self.data.items():
            assert parseAttributes(text) == expected, name

    def test_get_attrs(self) -> None:
        for name, (text, expected) in self.data.items():
            assert get_attrs(text) == expected, name

    def test_get_attrs_alt(self) -> None:
        tests = [
            {
                "text": 'name="test"',
                "expected": {"name": '"test"'},
            },
            {
                "text": "name",
                "expected": {"name": ""},
            },
            {
                "text": 'name="test" group="notes"',
                "expected": {"name": '"test"', "group": '"notes"'},
            },
            {
                "text": '  name  =  "test"  group  =  "notes"  ',
                "expected": {"name": '"test"', "group": '"notes"'},
            },
            {
                "text": "name='test'",
                "expected": {"name": "'test'"},
            },
            {
                "text": "name=test",
                "expected": {"name": ""},
            },
            {
                "text": "",
                "expected": {},
            },
            {
                "text": 'name="test value"',
                "expected": {"name": '"test value"'},
            },
        ]

        for case in tests:
            assert get_attrs(case["text"]) == case["expected"]
