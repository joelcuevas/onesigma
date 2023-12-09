<?php

function mb_ucwords($string)
{
    return mb_convert_case($string ?? '', MB_CASE_TITLE);
}

function markdown_view($path)
{
    $lang = config('app.locale');
    $path = str_replace('.', '/', $path);

    $contents = Storage::disk('markdown')->get("{$lang}/{$path}.md");
    $html = null;

    if ($contents) {
        $md = new League\CommonMark\CommonMarkConverter();
        $html = $md->convertToHtml($contents);
    }

    return $html;
}