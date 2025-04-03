

<component_mapping>
1. Core Controller: 
   - Main entry point: index.php (at the project root)
   - Additional controller logic: src/index.php
2. WikiParse Module:
   - Processing and parsing logic: src/WikiParse
   - Detailed parsing submodules and data models: 
       • Parsing logic: src/WikiParse/parsewiki 
       • Data model classes: src/WikiParse/parsewiki/DataModel (includes Citation.php, ExternalLink.php, InternalLink.php, Table.php, Template.php)
   - Specific parsing files: src/WikiParse/Category.php, src/WikiParse/Citations.php, src/WikiParse/Citations_reg.php, src/WikiParse/Template.php, src/WikiParse/include_it.php, and parser files such as ParserCategorys.php, ParserCitations.php, ParserExternalLinks.php, ParserInternalLinks.php, ParserTemplate.php, ParserTemplates.php (located under src/WikiParse/parsewiki)
3. Bot Module:
   - Bot scripts and utilities: src/bots (which includes es_months.php, es_refs.php, expend_refs.php, fix_pt_months.php, remove_duplicate_refs.php, txtlib2.php)
4. Additional Utility Scripts:
   - Support/auxiliary scripts outside of the main modules: 
       • src/infobox.php, src/infobox2.php
       • src/md_cat.php
       • src/mv_dots.php
       • src/sw.php
       • src/es.php, src/include_files.php, src/test_bot.php
5. Supporting CI/CD and External Integration:
   - GitHub workflows for continuous integration: .github/workflows (includes update.yaml)
</component_mapping>
