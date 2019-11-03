<?php
namespace App\Util;

use ReflectionClass;

/*
https://github.com/victortassinari/PhpClassSerialization/blob/master/ClassSerialization.php
*/
class Serialize {
    private static function objToArray($obj) {
        $reflectionClass = new ReflectionClass($obj);
        $methods = $reflectionClass->getMethods();
        $arrReturn = array();
        foreach ($methods as $method) {
            if (strpos($method->name, "get") !== false) {
                $name = str_replace("get", "", $method->name);
                $value = $method->invoke($obj);
                if (is_object($value)) {
                    $value = self::objToArray($value);
                } else if (is_array($value) && is_object($value [0])) {
                    $value = self::arrObjToArray($value);
                }
                $arrReturn [$name] = $value;
            }
        }
        return $arrReturn;
    }
    private static function arrObjToArray($arrObj) {
        if (!is_array($arrObj) && is_object($arrObj))
            return self::objToArray($arrObj);
        $arrRetorno = array();
        foreach ($arrObj as $obj) {
            $arrRetorno [] = self::objToArray($obj);
        }
        return $arrRetorno;
    }


    public static function serialize($obj) {
        if (is_array($obj)) {
            return json_encode(self::arrObjToArray($obj), JSON_PRETTY_PRINT);
        } else if (is_object($obj)) {
            return json_encode(self::objToArray($obj), JSON_PRETTY_PRINT);
        }
        return null;
    }
}
?>
