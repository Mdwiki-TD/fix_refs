<?php

include_once __DIR__ . '/../../src/include_files.php';

use PHPUnit\Framework\TestCase;
use function WpRefs\Bots\es_refs\get_refs;
use function WpRefs\Bots\es_refs\check_short_refs;
use function WpRefs\Bots\es_refs\make_line;
use function WpRefs\Bots\es_refs\add_line_to_temp;
use function WpRefs\Bots\es_refs\mv_es_refs;
