<?php


namespace App\Utilities;


class NameGenerator
{
    private static function createNameFromLabel($object)
    {
        if ($object->getName() == null)
            $object->setName(self::createName($object->getLabel()));
        else
            $object->setName(self::createName($object->getName()));
    }

    private static function createName($label)
    {

        return self::generateURLSafeString($label);

    }
    public static function defaultSave($content)
    {
        if (!$content->getName())
            self::createNameFromLabel($content);
    }

    private static function generateURLSafeString($string) {
        $safeString = preg_replace('/[^\d\w\-_\s]*/i','',$string);
        $safeString = trim(preg_replace('/\s{2,}/',' ',$safeString));
        $safeString = str_replace(' ','-',$safeString);
        $safeString = strtolower($safeString);
        return $safeString;
    }

}