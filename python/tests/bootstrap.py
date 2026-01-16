"""
Test bootstrap module

Base test class for all tests
"""


class MyFunctionTest:
    """Base test class with utility methods"""

    def assertEqualCompare(self, expected: str, input_text: str, result: str):
        """
        Compare expected and result, fail if result equals input but not expected

        Args:
            expected: Expected output
            input_text: Original input
            result: Actual result
        """
        # Normalize line endings
        result = result.replace('\r\n', '\n')
        expected = expected.replace('\r\n', '\n')

        if result == input_text and result != expected:
            raise AssertionError(
                f"No changes were made! The function returned the input unchanged:\n{result}"
            )
        else:
            assert expected == result, f"Unexpected result:\n{result}"

    def assertEqual(self, expected, result):
        """
        Assert that expected equals result

        Args:
            expected: Expected value
            result: Actual value
        """
        assert expected == result, f"Expected {expected}, but got {result}"
