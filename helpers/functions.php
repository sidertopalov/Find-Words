<?php 

function file_get_contents_utf8($fn) {
    $content = file_get_contents($fn);
    return iconv(mb_detect_encoding($content, mb_detect_order(), true), "UTF-8", $content);
}
