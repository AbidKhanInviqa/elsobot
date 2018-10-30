<?php

namespace Reiss\Slack;

class Output implements \JsonSerializable
{
    /**
     * @var Reply
     */
    private $reply;

    /**
     * @param Reply $reply
     */
    public function __construct(Reply $reply)
    {
        $this->reply = $reply;
    }

    /**
     * @param Attachment $attachment
     */
    public function setAttachment(Attachment $attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'text' => $this->reply->getText(),
            'response_type' => $this->reply->getResponseType(),
        ];
    }
}
