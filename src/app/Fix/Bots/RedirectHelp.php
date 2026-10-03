<?php

namespace App\Fix\Bots\RedirectHelp;

function page_is_redirect($title, $text)
{
    // #пренасочване

    if (preg_match('/^#(пренасочване|redirect)/', $text)) {
        return true;
    }

    return false;
}
