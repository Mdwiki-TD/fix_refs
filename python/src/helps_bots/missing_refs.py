"""
Missing refs module

Usage:
    from src.helps_bots.missing_refs import fix_missing_refs
"""

from src.test_bot import echo_test
from src.md_cat import get_url_curl


def get_full_text_url(sourcetitle, mdwiki_revid):
    """
    Get URL for full text
    
    Args:
        sourcetitle: Source title
        mdwiki_revid: MDWiki revision ID
        
    Returns:
        URL string or empty string
    """
    import os
    
    server = os.environ.get("SERVER_NAME", "localhost")
    server_path = "http://localhost:9001" if server == "localhost" else "https://mdwikicx.toolforge.org"
    
    if not mdwiki_revid or mdwiki_revid == 0:
        json_file = f"{server_path}/revisions_new/json_data.json"
        try:
            import json
            data = json.loads(get_url_curl(json_file)) if get_url_curl(json_file) else {}
            echo_test(f"url{json_file}")
            echo_test(f"count of data: {len(data)}")
            mdwiki_revid = data.get(sourcetitle.replace(" ", "_"), "")
        except:
            pass
    
    if not mdwiki_revid:
        echo_test("empty mdwiki_revid")
        return ""
    
    full_url = f"{server_path}/revisions_new/{mdwiki_revid}/wikitext.txt"
    echo_test(f"url{full_url}")
    text = get_url_curl(full_url)
    
    if not text:
        echo_test(f"Failed to fetch URL: {full_url}")
        return ""
    
    return text


def fix_missing_refs(text, sourcetitle, mdwiki_revid):
    """
    Fix missing references
    
    Args:
        text: Text to process
        sourcetitle: Source title
        mdwiki_revid: MDWiki revision ID
        
    Returns:
        Modified text
    """
    # Basic implementation - could be expanded with full ref matching
    return text
