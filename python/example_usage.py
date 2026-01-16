"""
Example usage of the fix_refs Python library

This script demonstrates how to use the main fix_page function
"""

from src.index import fix_page


def example_basic_usage():
    """Basic usage example"""
    print("=" * 60)
    print("Basic Usage Example")
    print("=" * 60)
    
    # Sample wikitext with reference issues
    text = """This is sample text.<ref name="test">Citation content</ref>

== References ==
<references />
"""
    
    print("Original text:")
    print(text)
    print()
    
    # Fix the page
    result = fix_page(
        text=text,
        title="Test Page",
        move_dots=True,
        infobox=False,
        add_en_lang=False,
        lang="en",
        sourcetitle="",
        mdwiki_revid=0
    )
    
    print("Fixed text:")
    print(result)
    print()


def example_armenian_language():
    """Example with Armenian language (dots after refs)"""
    print("=" * 60)
    print("Armenian Language Example (move dots after refs)")
    print("=" * 60)
    
    text = """Text with Armenian punctuation։<ref name="test"/>

Another sentence.<ref name="test2"/>"""
    
    print("Original text:")
    print(text)
    print()
    
    result = fix_page(
        text=text,
        title="Test",
        move_dots=True,
        infobox=False,
        add_en_lang=False,
        lang="hy",
        sourcetitle="",
        mdwiki_revid=0
    )
    
    print("Fixed text:")
    print(result)
    print()


def example_category_addition():
    """Example showing MDWiki category addition"""
    print("=" * 60)
    print("MDWiki Category Addition Example")
    print("=" * 60)
    
    text = """This is a translated article.

== References ==
<references />
"""
    
    print("Original text:")
    print(text)
    print()
    
    result = fix_page(
        text=text,
        title="Test",
        move_dots=False,
        infobox=False,
        add_en_lang=False,
        lang="fr",
        sourcetitle="",
        mdwiki_revid=0
    )
    
    print("Fixed text (French - category added):")
    print(result)
    print()


if __name__ == "__main__":
    example_basic_usage()
    example_armenian_language()
    example_category_addition()
