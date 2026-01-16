"""
Test utilities module

Usage:
    from src.test_bot import echo_test, echo_debug
"""

import os

def echo_test(str_msg):
    """
    Echo message if test mode is enabled
    
    Args:
        str_msg: Message to echo
    """
    test = os.environ.get('TEST', '')
    if test:
        print(str_msg)


def echo_debug(str_msg):
    """
    Echo debug message if DEBUG is defined
    
    Args:
        str_msg: Debug message to echo
    """
    if os.environ.get('DEBUG'):
        print(str_msg)
