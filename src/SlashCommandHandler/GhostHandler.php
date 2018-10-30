<?php

namespace Reiss\Slack\SlashCommandHandler;

use Reiss\Slack\Agent\GhostAgent;
use Reiss\Slack\Exception\InvalidGhostSuiteName;
use Reiss\Slack\Input;
use Reiss\Slack\Output;
use Reiss\Slack\Reply\GhostReply;
use Reiss\Slack\SlashCommandHandler;
use Webmozart\Assert\Assert;

/**
 * @author Adam Elsodaney <adam.elsodaney@reiss.com>
 */
class GhostHandler implements SlashCommandHandler
{
    const SUCCESS = 'SUCCESS';

    /**
     * @var GhostAgent
     */
    private $ghostAgent;

    /**
     * @param GhostAgent $ghostAgent
     */
    public function __construct(GhostAgent $ghostAgent)
    {
        $this->ghostAgent = $ghostAgent;
    }

    /**
     * @param Input $input
     *
     * @return Output
     */
    public function execute(Input $input): Output
    {
        Assert::eq($input->getCommand(), '/ghost', "This slash command {$input->getCommand()} cannot be handled here, expected /ghost.");

        $data = [
            'code' => null,
        ];

        switch ($input->getNthArgument(1)) {
            case 'run':
                try {
                    $data = $this->ghostAgent->executeSuite($input->getNthArgument(2), $input->getNthArgument(3));

                } catch (InvalidGhostSuiteName $e) {
                    return new Output(GhostReply::failed($e->getMessage()));
                }
                break;
        }

        // @todo Figure out how to best handle the responses.

        if (self::SUCCESS === $data['code']) {
            return new Output(GhostReply::success());
        }

        return new Output(GhostReply::failed());
    }
}
