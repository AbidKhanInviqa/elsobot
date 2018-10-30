<?php

namespace Reiss\Slack\Reply;

use Reiss\Slack\Attachment;
use Reiss\Slack\Reply;

/**
 * @author Adam Elsodaney <adam.elsodaney@reiss.com>
 */
class GhostReply implements Reply
{
    private $success = true;
    /**
     * @var string
     */
    private $text;

    private function __construct(bool $success, string $text = null)
    {
        $this->success = $success;
        $this->text = $text;
    }

    public static function success($text = null)
    {
        return new self(true, $text);
    }

    public static function failed($text = null)
    {
        return new self(false, $text);
    }

    public function getText(): string
    {
        if ($this->text) {
            return $this->text;
        }

        return $this->success ? 'Executing tests, please wait.' : 'Sorry, I\'m afraid I can\'t do that. Check the command';
    }

    /**
     * @return null|Attachment
     */
    public function getAttachment()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getResponseType(): string
    {
        return self::IN_CHANNEL;
    }
}
