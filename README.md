[![Ask DeepWiki](https://deepwiki.com/badge.svg)](https://deepwiki.com/Mdwiki-TD/fix_refs)

# Fix Refs

This repository used by [publish repo](https://github.com/Mdwiki-TD/publish) to fix references in wikitext format.

## How It Works

The main script to run is `index.php`. It processes the references in Wikipedia articles by performing the following steps:

1. **Read and Load Configuration File:**
   - The script reads the `fixwikirefs.json` file to get the necessary settings for fixing the references.

2. **Fix Page Content:**
   - The `fix_page_here` function is called to applies various fixes in the content based on the loaded settings:

   1. **Expand Infobox:** Expand the infobox paramaters in the text.

   2. **Remove Duplicate References:** Remove any duplicate references found in the text.

   3. **Move Dots:** Move dots in the text after references.

   4. **Add English Language References:** Add `|language=en` to references.

   5. **Apply Language-specific Fixes:** Apply specific fixes based on the language of the text, such as adjustments for Portuguese, Spanish, or Swahili.

   6. **Add MDWiki Category:** The MDWiki category is added to the text if it is not already present.

3. **Return Modified or Original Text:**
   - The modified text is returned if changes were made, otherwise, the original text is returned.

