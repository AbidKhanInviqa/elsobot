<?php

namespace spec\Reiss\Slack\SlashCommandHandler;

use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Reiss\Slack\Input;
use Reiss\Slack\Output;
use Reiss\Slack\Reply\GhostReply;
use Reiss\Slack\SlashCommandHandler\GhostHandler;
use PhpSpec\ObjectBehavior;

class GhostHandlerSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(GhostHandler::class);
    }

    function it_works_the_way_Abid_wants_it_to(Input $input, Client $client, ResponseInterface $response, StreamInterface $body)
    {
        $input->getCommand()->willReturn('/ghost');
        $input->getText()->willReturn('run buytoship www.platform.sh/pr3432');

        // https://ghostinspector.com/docs/api/suites/#execute

        $client->request('GET', 'https://api.ghostinspector.com/v1/suites/58f74dec69d0b303b85ee134/execute/', [
            'query' => [
                'apiKey' => '7bc5513d85f078b555e3d4c25b28b3fbe727b08a',
                'startUrl' => 'www.platform.sh/pr3432',
            ]
        ])
            ->willReturn($response);

        $response->getBody()->willReturn($body);
        $body->getContents()->willReturn(<<<JSON
{
  "code": "SUCCESS",
  "data": [
    "some long response, we only care about the `code` above"
  ]
}
JSON
        );

        $output = new Output(GhostReply::success());

        $this->execute($input)->shouldBeLike($output);
    }
}
