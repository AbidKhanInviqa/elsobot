<?php

namespace Reiss\Slack;

interface Reply
{
    const IN_CHANNEL = 'in_channel';
    const EPHEMERAL  = 'ephemeral';

    /**
     * @return string
     */
    public function getText(): string;

    /**
     * @return string One of 'in_channel' or 'ephemeral'.
     */
    public function getResponseType(): string;

    /**
     * @return null|Attachment
     */
    public function getAttachment();
}
