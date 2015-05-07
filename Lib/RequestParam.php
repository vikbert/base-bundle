<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 08.01.15
 * Time: 15:05
 */

namespace Wk\BaseBundle\Lib;

/**
 * Class RequestParam
 *
 * @package Wk\BaseBundle\Lib
 */
class RequestParam
{
    /**
     * @param mixed $variable
     * @param mixed $fixedType
     *
     * @return mixed
     */
    public static function convertType($variable, $fixedType = null)
    {
        if (is_string($fixedType) && !is_array($variable)) {
            if ($fixedType == 'bool' && $variable === 'false') {
                return false;
            }
            settype($variable, $fixedType);
            return $variable;
        }

        if (is_numeric($variable)) {
            $float = floatval($variable);
            $int = intval($variable);
            if ($int == $float) {
                return $int;
            } else {
                return $float;
            }
        }

        // Convert the values recursive if it's an array
        if (is_array($variable)) {
            foreach ($variable as $key => $value) {
                if (is_array($fixedType) && isset($fixedType[$key])) {
                    $variable[$key] = self::convertType($value, $fixedType[$key]);
                } else {
                    $variable[$key] = self::convertType($value);
                }
            }
        }

        return $variable;
    }
}
