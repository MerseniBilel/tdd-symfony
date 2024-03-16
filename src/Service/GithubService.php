<?php

namespace App\Service;

use App\Enum\HealthStatus;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GithubService
{

    public function __construct()
    {

    }

    private function extractStatusFromLabel(array $labels): HealthStatus
    {
        $status = null;

        foreach ($labels as $label) {
            $label = $label['name'];

            // we only care about the label starts with "Status"
            if (!str_contains($label, 'Status:')) {
                continue;
            }

            // remove the status form the label to get the health
            $status = trim(substr($label, strlen("Status:")));

            return HealthStatus::tryFrom($status);
        }

        return HealthStatus::Healthy;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getHealthReport(string $dinoName): HealthStatus
    {
        $health = HealthStatus::Healthy;

        $client = HttpClient::create();

        // call github API
        $response = $client->request(
            'GET',
            'https://api.github.com/repos/MerseniBilel/tdd-symfony/issues'
        );

        foreach ($response->toArray() as $issue) {
            if (str_contains($issue['title'], $dinoName)) {
                $health = $this->extractStatusFromLabel($issue['labels']);
            }
        }

        return $health;
    }


}