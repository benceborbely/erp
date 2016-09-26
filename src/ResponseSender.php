<?php

namespace Bence;

use Psr\Http\Message\ResponseInterface;

/**
 * Class ResponseSender
 *
 * @author Bence Borbély
 */
class ResponseSender
{

    /**
     * @param ResponseInterface $response
     */
    public function send(ResponseInterface $response)
    {
        if (!headers_sent()) {

            header(sprintf('HTTP/%s %s %s', $response->getProtocolVersion(), $response->getStatusCode(), $response->getReasonPhrase()), true, $response->getStatusCode());

            foreach ($response->getHeaders() as $header => $values) {
                foreach ($values as $value) {
                    header($header.': '.$value, false, $response->getStatusCode());
                }
            }
        }
        echo $response->getBody();
    }

}
