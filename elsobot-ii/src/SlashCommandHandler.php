<?php

namespace Reiss\Slack;

/**
 * @author Adam Elsodaney <adam.elsodaney@reiss.com>
 */
interface SlashCommandHandler
{
    /**
     * @param Input $input
     *
     * @return Output
     */
    public function execute(Input $input): Output;
}
