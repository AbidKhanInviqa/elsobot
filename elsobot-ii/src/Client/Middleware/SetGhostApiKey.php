<?php
/**
 * @author    Adam Elsodaney <adam.elsodaney@reiss.com>
 * @date      2017-06-13
 * @copyright Copyright (c) Reiss Clothing Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Reiss\Slack\Client\Middleware;

use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class SetGhostApiKey
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param RequestInterface $request
     *
     * @return RequestInterface
     */
    public function __invoke(RequestInterface $request): RequestInterface
    {
        return $request->withUri($this->createUriWithApiKey($request->getUri()));
    }

    /**
     * @param UriInterface $uri
     *
     * @return UriInterface
     */
    private function createUriWithApiKey(UriInterface $uri): UriInterface
    {
        return Uri::withQueryValue($uri, 'apiKey', $this->apiKey);
    }
}
