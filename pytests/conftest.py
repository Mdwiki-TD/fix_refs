"""Configuration and fixtures for pytest"""
import sys
from pathlib import Path

# Add python_src to path for imports
python_src_path = Path(__file__).parent.parent / "python_src" / "src"
sys.path.insert(0, str(python_src_path))
