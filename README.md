# Fix Refs

This repository used by [publish repo](https://github.com/Mdwiki-TD/publish) to fix references in wikitext format.

## How It Works

The main script to run is `index.php`. It processes the references in Wikipedia articles by performing the following steps:

1. **Read Configuration File:**
   - The script reads the `fixwikirefs.json` file to get the necessary settings for fixing the references.

2. **Check File Existence:**
   - It checks if the configuration file exists at the specified location. If not, it tries an alternative path.

3. **Load Settings:**
   - The settings from the configuration file are loaded into an array.

4. **Fix Page Content:**
   - The `fix_page_here` function is called to fix the page content based on the loaded settings. This function applies various fixes such as expanding infoboxes, removing duplicate references, moving dots, adding English language references, and applying language-specific adjustments.

   1. **Expand Infobox if Required:**
      - If the settings specify, expand the infobox in the text.

   2. **Remove Duplicate References:**
      - Remove any duplicate references found in the text.

   3. **Move Dots if Required:**
      - Move dots in the text if the settings indicate this should be done.

   4. **Add English Language References if Required:**
      - Add English language references if specified in the settings.

   5. **Apply Language-specific Fixes:**
      - Apply specific fixes based on the language of the text, such as adjustments for Portuguese, Spanish, or Swahili.

5. **Apply Language-specific Fixes:**
   - The script applies specific fixes based on the language of the text, such as adjustments for Portuguese, Spanish, or Swahili.

6. **Add MDWiki Category:**
   - The MDWiki category is added to the text if it is not already present.

7. **Return Modified or Original Text:**
   - The modified text is returned if changes were made, otherwise, the original text is returned.

