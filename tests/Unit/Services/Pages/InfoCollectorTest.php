<?php

namespace Tests\Unit\Services\Pages;

use App\Models\Page;
use App\Services\Pages\InfoCollector;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Model;

class InfoCollectorTest extends TestCase
{
    protected $infoCollector;

    public function setUp(): void
    {
        parent::setUp();
        $this->infoCollector = new InfoCollector(new Page());
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->infoCollector = null;
        \Mockery::close();
    }

    public function testSetClient()
    {
        $this->infoCollector->setClient();
        $this->assertInstanceOf(Client::class, $this->infoCollector->client);
    }

    public function testGetDomainsList()
    {
        $response = [
            [
                "rank" => 1,
                "rootDomain" => "example.com",
                "linkingRootDomains" => 100,
                "domainAuthority" => 0
            ],
            [
                "rank" => 2,
                "rootDomain" => "example.org",
                "linkingRootDomains" => 100,
                "domainAuthority" => 0
            ],
        ];
        $domains = ['example.com', 'example.org'];
        config(['services.github.domains_list_url' => 'http://example.com']);

        $clientMock = $this->createMock(Client::class);
        $clientMock->method('get')->willReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode($response)));

        $this->infoCollector->client = $clientMock;

        $result = $this->infoCollector->getDomainsList();

        $this->assertEquals([$domains], $result);
    }

    public function testGetPagesInfo()
    {
        config(['services.openpagerank.url' => 'http://example.com']);
        $domainsList = ['example.com', 'example.org'];
        $response =
            [
                "status_code" => 200,
                "response" => [
                    [
                        "status_code" => 200,
                        "error" => "",
                        "page_rank_integer" => 10,
                        "page_rank_decimal" => 10,
                        "rank" => "6",
                        "domain" => "example.com"
                    ],
                    [
                        "status_code" => 200,
                        "error" => "",
                        "page_rank_integer" => 8,
                        "page_rank_decimal" => 7.63,
                        "rank" => "40",
                        "domain" => "example.org"
                    ],
                ],
                "last_updated" => "15th Mar 2024"
            ];

        $clientMock = $this->createMock(Client::class);
        $clientMock->method('get')->willReturn(new \GuzzleHttp\Psr7\Response(200, [], json_encode($response)));

        $this->infoCollector->client = $clientMock;

        $result = $this->infoCollector->getPagesInfo($domainsList);

        $this->assertEquals($response['response'], $result);
    }

    public function testUpdateInfo()
    {
        $pageMock = \Mockery::mock(Page::class);

        $pages = [
            ['domain' => 'example.com', 'rank' => 5],
            ['domain' => 'example.org', 'rank' => 10],
        ];

        $pageMock->shouldReceive('updateOrCreate')
            ->with(['domain' => 'example.com'], ['rank' => 5])
            ->once()
            ->andReturn(true);

        $pageMock->shouldReceive('updateOrCreate')
            ->with(['domain' => 'example.org'], ['rank' => 10])
            ->once()
            ->andReturn(true);

        Log::shouldReceive('error')->never();

        $infoCollector = new InfoCollector($pageMock);
        $infoCollector->updateInfo($pages);
    }

}
