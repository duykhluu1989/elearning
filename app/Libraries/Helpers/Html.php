<?php

namespace App\Libraries\Helpers;

class Html
{
    public static function a($innerHtml, $attributes)
    {
        return self::renderHtmlOpenCloseTag('a', $innerHtml, $attributes);
    }

    public static function button($innerHtml, $attributes)
    {
        return self::renderHtmlOpenCloseTag('button', $innerHtml, $attributes);
    }

    public static function span($innerHtml, $attributes)
    {
        return self::renderHtmlOpenCloseTag('span', $innerHtml, $attributes);
    }

    public static function i($innerHtml, $attributes)
    {
        return self::renderHtmlOpenCloseTag('i', $innerHtml, $attributes);
    }

    public static function renderHtmlOpenCloseTag($htmlTag, $innerHtml, $attributes)
    {
        $html = '<' . $htmlTag;

        foreach($attributes as $name => $value)
            $html .= ' ' . $name . '="' . $value . '"';

        $html .= '>' . $innerHtml . '</' . $htmlTag . '>';

        return $html;
    }
}