<?php

include_once __DIR__ . '/../../src/include_files.php';

use PHPUnit\Framework\TestCase;
use function WpRefs\Bots\MonthNewValue\make_date_new_val_pt;
use function WpRefs\FixPtMonth\fix_one_cite_text;
use function WpRefs\FixPtMonth\fix_cites_text;
use function WpRefs\FixPtMonth\pt_months;
use function WpRefs\FixPtMonth\rm_ref_spaces;
use function WpRefs\FixPtMonth\pt_fixes;
