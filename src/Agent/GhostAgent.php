<?php

namespace Reiss\Slack\Agent;

interface GhostAgent
{
    public function executeSuite(string $suiteName, string $startUrl = null);

    public function listSuites();
}
