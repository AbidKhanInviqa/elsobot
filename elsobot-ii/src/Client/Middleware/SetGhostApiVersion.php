<?php

namespace Reiss\Slack\Client\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

class SetGhostApiVersion
{
    public function __invoke(RequestInterface $request): RequestInterface
    {
        return $request->withUri($this->createUriWithApiVersion($request->getUri()));
    }

    /**
     * @param UriInterface $uri
     *
     * @return UriInterface
     */
    private function createUriWithApiVersion(UriInterface $uri): UriInterface
    {
        $path = '/v1' . $uri->getPath();

        return $uri->withPath($path);
    }
}
