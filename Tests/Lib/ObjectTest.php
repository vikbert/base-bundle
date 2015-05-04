<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 28.08.14
 * Time: 12:12
 */

namespace Wk\BaseBundle\Tests\Lib;

use Wk\BaseBundle\Lib\Object;

/**
 * Class ObjectTest
 *
 * @package Wk\BaseBundle\Tests\Lib
 */
class ObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Data provider for testTo
     *
     * @return array
     */
    public function dataToArray()
    {
        $object1 = new InheritedObject();
        $object1->test = 123;
        $array1 = array('test' => 123, 'bla' => null, 'blubb' => null);

        $object2 = new InheritedObject();
        $object2->bla = true;
        $array2 = array('test' => null, 'bla' => true, 'blubb' => null);

        $object3 = new InheritedObject();
        $object3->blubb = 'text';
        $array3 = array('test' => null, 'bla' => null, 'blubb' => 'text');

        return array(
            array($array1, $object1),
            array($array2, $object2),
            array($array3, $object3),
        );
    }

    /**
     * @param array  $expectedArray
     * @param Object $object
     *
     * @dataProvider dataToArray
     */
    public function testToArray(array $expectedArray, Object $object)
    {
        $this->assertEquals($expectedArray, $object->__toArray());
    }
}
 