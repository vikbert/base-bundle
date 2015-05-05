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
     *
     * @return mixed
     */
    public static function convertType($variable)
    {
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
                $variable[$key] = self::convertType($value);
            }
        }

        return $variable;
    }
}

