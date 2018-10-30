<?php
/**
 * @author    Adam Elsodaney <adam.elsodaney@reiss.com>
 * @date      2017-06-13
 * @copyright Copyright (c) Reiss Clothing Ltd.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Reiss\Slack\Agent;

use GuzzleHttp\ClientInterface;
use Reiss\Slack\Exception\InvalidGhostSuiteName;
use Webmozart\Assert\Assert;

class GuzzleHttpGhostAgent implements GhostAgent
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @link https://ghostinspector.com/docs/api/suites/#execute
     */
    public function executeSuite(string $suiteName = null, string $startUrl = null): array
    {
        $suiteId = $this->findSuiteId($suiteName);

        Assert::notNull($suiteId, 'No suite could be determined to execute.');

        $response = $this->client->request('GET', "/suites/{$suiteId}/execute/", [
            'query' => [
                'startUrl' => $startUrl,
                'immediate' => true,
            ],
        ]);

        return \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @return array
     */
    public function listSuites(): array
    {
        $response = $this->client->request('GET', "/suites/");

        $data = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);

        if ('SUCCESS' !== $data['code']) {
            throw new \RuntimeException('Could not get a list of suites.');
        }

        return $data['data'];
    }

    /**
     * @param string|null $name
     *
     * @return string|null
     */
    private function findSuiteId(string $name = null)
    {
        if (null === $name) {
            return null;
        }

        $map = $this->fetchSuiteMap();

        if (! isset($map[$name])) {
            $expected = implode(', ', array_keys($map));

            throw new InvalidGhostSuiteName("Suite name '{$name}' is not a known suite. Known suites are: [ $expected ].");
        }

        return $map[$name];
    }

    /**
     * @return array
     */
    private function fetchSuiteMap(): array
    {
        $map = [];

        $suites = $this->listSuites();

        foreach ($suites as $suite) {
            $normalizedName = strtolower(str_replace(' ', '', $suite['name']));

            $map[$normalizedName] = $suite['_id'];
        }

        return $map;
    }
}
