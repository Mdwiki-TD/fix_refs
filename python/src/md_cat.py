"""
MDWiki category management module

Usage:
    from src.md_cat import add_Translated_from_MDWiki, get_url_curl
"""

import re
import json
import os
from pathlib import Path
import requests
from src.test_bot import echo_test


def get_url_curl(url: str) -> str:
    """
    Fetch content from URL using requests
    
    Args:
        url: URL to fetch
        
    Returns:
        Response content as string
    """
    usr_agent = 'WikiProjectMed Translation Dashboard/1.0 (https://mdwiki.toolforge.org/; tools.mdwiki@toolforge.org)'
    
    try:
        response = requests.get(
            url,
            headers={'User-Agent': usr_agent},
            timeout=5
        )
        return response.text
    except Exception as e:
        echo_test(f"<br>Request Error: {e}<br>{url}")
        return ""


def load_from_local_file():
    """
    Load categories from local JSON file
    
    Returns:
        Dictionary of categories or empty dict
    """
    local_file = Path(__file__).parent.parent / 'resources' / 'mdwiki_categories.json'
    
    if not local_file.is_file():
        return {}
    
    try:
        with open(local_file, 'r', encoding='utf-8') as f:
            content = f.read()
            if not content:
                return {}
            return json.loads(content)
    except:
        return {}


def get_cats():
    """
    Get MDWiki categories from Wikidata or local file
    
    Returns:
        Dictionary of categories
    """
    url = "https://www.wikidata.org/w/rest.php/wikibase/v1/entities/items/Q107014860/sitelinks"
    
    # Use a simple cache mechanism
    if not hasattr(get_cats, 'json_cache'):
        data = get_url_curl(url)
        try:
            decoded = json.loads(data) if data else {}
        except:
            decoded = {}
        
        if not decoded:
            decoded = load_from_local_file()
        
        get_cats.json_cache = decoded if isinstance(decoded, dict) else {}
    
    return get_cats.json_cache


def Get_MdWiki_Category(lang):
    """
    Get MDWiki category for a specific language
    
    Args:
        lang: Language code
        
    Returns:
        Category string or empty string
    """
    # Skip certain languages
    skip_langs = ["it"]
    
    if lang in skip_langs:
        return ""
    
    cats = get_cats()
    
    # Get category for language
    cat = cats.get(f"{lang}wiki", {}).get("title", "Category:Translated from MDWiki")
    
    return cat


def add_Translated_from_MDWiki(text, lang):
    """
    Add MDWiki translation category to text if not present
    
    Args:
        text: Wikitext to process
        lang: Language code
        
    Returns:
        Modified text with category added
    """
    # Check if category already exists
    if re.search(r':\s*Translated[ _]from[ _]MDWiki\s*\]\]', text, re.IGNORECASE):
        return text
    
    cat = Get_MdWiki_Category(lang)
    
    if cat and cat not in text:
        text += f"\n[[{cat}]]\n"
    
    return text
