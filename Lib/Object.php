<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 20.08.14
 * Time: 16:50
 */

namespace Wk\BaseBundle\Lib;

/**
 * Class Object
 */
class Object
{
    /**
     * @return array
     */
    public function __toArray()
    {
        $reflection = new \ReflectionClass($this);
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE);

        $result = array();
        foreach ($properties as $property) {
            $key = $property->getName();
            $result[$key] = $this->$key;
        }

        return $result;
    }
}

