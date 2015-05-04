<?php
/**
 * Created by PhpStorm.
 * User: thomas
 * Date: 11.09.14
 * Time: 18:25
 */

namespace Wk\BaseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller as Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class RedirectController
 *
 * @package Wk\BaseBundle\Controller
 */
class RedirectController extends Framework\RedirectController
{
    /**
     * Redirects to another route with the given name.
     *
     * The response status code is 302 if the permanent parameter is false (default),
     * and 301 if the redirection is permanent.
     *
     * In case the route name is empty, the status code will be 404 when permanent is false
     * and 410 otherwise.
     *
     * @param Request    $request          The request instance
     * @param string     $route            The route name to redirect to
     * @param bool       $permanent        Whether the redirection is permanent
     * @param bool|array $ignoreAttributes Whether to ignore attributes or an array of attributes to ignore
     * @param array|bool $ignoreQueryString
     *
     * @return RedirectResponse A Response instance
     *
     * @throws HttpException In case the route name is empty
     */
    public function redirectAction(Request $request, $route, $permanent = false, $ignoreAttributes = false, $ignoreQueryString = false)
    {
        if ($ignoreAttributes !== true) {
            $ignoreAttributes[] = 'ignoreQueryString';
        }

        /** @var RedirectResponse $response */
        $response = parent::redirectAction($request, $route, $permanent, $ignoreAttributes);

        // If the query string is irrelevant then don't manipulate
        if ($ignoreQueryString === true) {
            return $response;
        }

        $queryString = $request->getQueryString();
        if (is_array($ignoreQueryString)) {
            $pairs = explode('&', $queryString);
            foreach ($pairs as $index => $pair) {
                list($key) = explode('=', $pair);
                if (in_array($key, $ignoreQueryString)) {
                    unset($pairs[$index]);
                }
            }
            $queryString = implode('&', $pairs);
        }

        $targetUrl = $response->getTargetUrl();
        if ($queryString) {
            if (strpos($targetUrl, '?') === false) {
                $targetUrl .= '?' . $queryString;
            } else {
                $targetUrl .= '&' . $queryString;
            }
        }
        $response->setTargetUrl($targetUrl);

        return $response;
    }
}