<?php

namespace App\Services\Pages;


use App\Models\Page;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class InfoCollector
{
    public Client $client;

    public function __construct(protected Page $page)
    {
    }

    /**
     * @throws \Exception
     */
    public function collectInfo(): void
    {
        $this->setClient();
        $domainsList = $this->getDomainsList();

        foreach ($domainsList as $list) {
            $pages = $this->getPagesInfo($list);
            $this->updateInfo($pages);
        }
    }

    public function setClient(): void
    {
        $this->client = new Client();
    }

    public function getDomainsList(): array
    {
        $domains = [];

        try {
            $response = $this->client->get(config('services.github.domains_list_url'));
            $items = json_decode($response->getBody()->getContents(), true);

            foreach ($items as $item) {
                $domains[] = $item['rootDomain'];
            }

            return array_chunk($domains, 100);
        } catch (\Exception $e) {
            throw new \Exception('Error while getting domains list');
        }
    }

    public function getPagesInfo($domainsList)
    {
        try {

            $response = $this->client->get(config('services.openpagerank.url'), [
                'query' => [
                    'domains' => $domainsList
                ],
                'headers' => [
                    'API-OPR' => config('services.openpagerank.api_key'),
                ]
            ]);

            $response = json_decode($response->getBody()->getContents(), true);

            return $response['response'];

        } catch (\Exception $e) {
            throw new \Exception('Error while getting pages info');
        }
    }

    public function updateInfo($pages): void
    {
        foreach ($pages as $page) {

            if (!empty($page['error'])) {

                Log::error('Error while getting page info',
                    [
                        'domain' => $page['domain'],
                        'error' => $page['error'],
                        'status_code' => $page['status_code']
                    ]);

                continue;
            }

            $this->page->updateOrCreate(
                ['domain' => $page['domain']],
                [
                    'rank' => (int)$page['rank'],
                ]
            );
        }
    }

}
