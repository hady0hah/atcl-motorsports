<?php


namespace App\Utilities;


class Utilities
{
    public static function humanizeString($string) {
        return ucfirst(strtolower(trim(preg_replace(array('/([A-Z])/', '/[_\s]+/'), array('_$1', ' '), $string))));
    }
}