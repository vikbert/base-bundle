<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 23.03.15
 * Time: 23:01
 */

namespace Wk\BaseBundle\Tests\Lib;

use Wk\BaseBundle\Lib\Object;

/**
 * Class InheritedObject
 *
 * @package Wk\BaseBundle\Tests\Lib
 */
class InheritedObject extends Object
{
    /**
     * @var string
     */
    public $blubb;

    /**
     * @var boolean
     */
    public $bla;

    /**
     * @var integer
     */
    public $test;
}