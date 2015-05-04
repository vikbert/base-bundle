<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 12.03.15
 * Time: 14:36
 */

namespace Wk\BaseBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class Controller
 *
 * @package Wk\BaseBundle\Controller
 */
class Controller extends FOSRestController
{
    /**
     * @param string     $message
     * @param \Exception $previous
     * @param int        $code
     *
     * @return BadRequestHttpException
     */
    public function createBadRequestException($message = 'Bad Request', \Exception $previous = null, $code = 0)
    {
        return new BadRequestHttpException($message, $previous, $code);
    }
}