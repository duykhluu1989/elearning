<?php

namespace App\Libraries\Helpers;

class Html
{
    public static function a($innerHtml, $attributes)
    {
        $html = '<a';

        foreach($attributes as $name => $value)
            $html .= ' ' . $name . '="' . $value . '"';

        $html .= '>' . $innerHtml . '</a>';

        echo $html;
    }

    public static function button($innerHtml, $attributes)
    {
        $html = '<button';

        foreach($attributes as $name => $value)
            $html .= ' ' . $name . '="' . $value . '"';

        $html .= '>' . $innerHtml . '</button>';

        echo $html;
    }
}