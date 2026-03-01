<?php

namespace WpRefs\Bots\Redirect;
/*
usage:
use function WpRefs\Bots\Redirect\page_is_redirect;
*/

function page_is_redirect($title, $text)
{
    // #пренасочване
    // ---
    if (preg_match('/^#(пренасочване|redirect)/', $text)) {
        return true;
    }
    // ---
    return false;
}
