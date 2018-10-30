<?php

namespace Reiss\Slack\Controller;

use GuzzleHttp\Client;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Reiss\Slack\Input;
use Reiss\Slack\SlashCommandHandler\GhostHandler;
use Zend\Diactoros\CallbackStream;
use Zend\Diactoros\Response;

class Endpoint
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var GhostHandler
     */
    private $ghostHandler;

    public function __construct(LoggerInterface $logger, GhostHandler $ghostHandler)
    {
        $this->logger = $logger;
        $this->ghostHandler = $ghostHandler;
    }

    /**
     * Example request body
     *
     *   token=gIkuvaNzQIHg97ATvDxqgjtO
     *   team_id=T0001
     *   team_domain=example
     *   enterprise_id=E0001
     *   enterprise_name=Globular%20Construct%20Inc
     *   channel_id=C2147483705
     *   channel_name=test
     *   user_id=U2147483697
     *   user_name=Steve
     *   command=/weather
     *   text=94070
     *   response_url=https://hooks.slack.com/commands/1234/5678
     *
     *
     *
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function __invoke(ServerRequestInterface $request)
    {
        $slackInput = new Input($request);

        switch ($slackInput->getCommand()) {
            case '/ghost':

                $stream = fopen('php://memory', 'wb');
                fwrite($stream, \GuzzleHttp\json_encode(
                    $this->ghostHandler->execute($slackInput))
                );

                return new Response($stream, 200, ['Content-Type' => 'application/json']);

            default:
                $name = $slackInput->getUserName() ?: 'World';
                $stream = new CallbackStream($this->callback($name));

                return new Response($stream, 200, ['Content-Type' => 'application/json']);
        }


    }

    /**
     * @param string $name
     *
     * @return \Closure
     */
    private function callback($name)
    {
        return function () use ($name) {
            return \GuzzleHttp\json_encode([
                "text" => "Hello @{$name}!",
                "attachments" => [
                    [
                        "text" => "Where are we going for lunch tomorrow?"
                    ]
                ]
            ]);
        };
    }
}
