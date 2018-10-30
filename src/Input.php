<?php

namespace Reiss\Slack;

use Psr\Http\Message\ServerRequestInterface;

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
 */
class Input
{
    private $token;
    private $teamId;
    private $teamDomain;
    private $enterpriseId;
    private $enterpriseName;
    private $channelId;
    private $channelName;
    private $userId;
    private $userName;
    private $command;
    private $text;
    private $responseUrl;

    public function __construct(ServerRequestInterface $request)
    {
        $data = $request->getMethod() === 'GET' ? $request->getQueryParams() : $request->getParsedBody();

        if (empty($data)) {
            return;
        }

        $this->token = @$data['token'];
        $this->teamId = @$data['team_id'];
        $this->teamDomain = @$data['team_domain'];
        $this->enterpriseId = @$data['enterprise_id'];
        $this->enterpriseName = @$data['enterprise_name'];
        $this->channelId = @$data['channel_id'];
        $this->channelName = @$data['channel_name'];
        $this->userId = @$data['user_id'];
        $this->userName = @$data['user_name'];
        $this->command = @$data['command'];
        $this->text = @$data['text'];
        $this->responseUrl = @$data['response_url'];
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * @return string
     */
    public function getTeamDomain()
    {
        return $this->teamDomain;
    }

    /**
     * @return string
     */
    public function getEnterpriseId()
    {
        return $this->enterpriseId;
    }

    /**
     * @return string
     */
    public function getEnterpriseName()
    {
        return $this->enterpriseName;
    }

    /**
     * @return string
     */
    public function getChannelId()
    {
        return $this->channelId;
    }

    /**
     * @return string
     */
    public function getChannelName()
    {
        return $this->channelName;
    }

    /**
     * @return string
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @return array
     */
    public function getTextAsArguments(): array
    {
        return empty($this->text) ? [] : explode(' ', $this->text);
    }

    /**
     * @param int $nth Non-zero based.
     *
     * @return string|null
     */
    public function getNthArgument(int $nth)
    {
        return $this->getTextAsArguments()[$nth-1] ?? null;
    }

    /**
     * @return string
     */
    public function getResponseUrl()
    {
        return $this->responseUrl;
    }
}
