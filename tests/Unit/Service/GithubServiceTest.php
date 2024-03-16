<?php

namespace App\Tests\Unit\Service;

use App\Enum\HealthStatus;
use App\Service\GithubService;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GithubServiceTest extends TestCase
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    #[DataProvider('dinoNameProvider')]
    public function testgetHealthReportReturnCorrectHealthStatusForDino(HealthStatus $expectedStatus, string $dinoName): void
    {
        $service = new GithubService();

        $this->assertSame($expectedStatus, $service->getHealthReport($dinoName));
    }

    public static function dinoNameProvider(): \Generator
    {
        yield 'Sick Dino' => [
            HealthStatus::Sick,
            'Daisy'
        ];

        yield 'Healthy Dino' => [
            HealthStatus::Healthy,
            'Maverick'
        ];
    }


}