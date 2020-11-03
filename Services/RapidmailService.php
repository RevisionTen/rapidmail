<?php

declare(strict_types=1);

namespace RevisionTen\Rapidmail\Services;

use Rapidmail\ApiClient\Client;
use Rapidmail\ApiClient\Exception\ApiClientException;
use Rapidmail\ApiClient\Exception\ApiException;

class RapidmailService
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var Client
     */
    private $client;

    public function __construct(array $config)
    {
        $this->config = $config;
        $this->client = new Client($this->config['api_username_hash'], $this->config['api_password_hash']);
    }

    /**
     * Subscribes a user to a list.
     *
     * @param string      $campaign
     * @param string      $email
     * @param string|NULL $source
     * @param array       $mergeFields
     *
     * @return bool
     *
     * @throws ApiClientException
     */
    public function subscribe(string $campaign, string $email, string $source = null, array $mergeFields = []): bool
    {
        if (!isset($this->config['campaigns'][$campaign])) {
            return false;
        }

        $recipientsService = $this->client->recipients();

        $requestData = [
            'recipientlist_id' => $this->config['campaigns'][$campaign]['list_id'], // Required
            'email' => $email, // Required
            'firstname' => $mergeFields['FNAME'] ?? null,
            'lastname' => $mergeFields['LNAME'] ?? null,
            'gender' => $mergeFields['gender'] ?? null,
        ];

        $recipientsService->create($requestData, [
                'send_activationmail' => 'yes',
            ]
        );

        return true;
    }

    /**
     * Unsubscribes a user from a list.
     *
     * @param string $campaign
     * @param string $email
     *
     * @return bool
     *
     * @throws ApiException
     */
    public function unsubscribe(string $campaign, string $email): bool
    {
        if (!isset($this->config['campaigns'][$campaign])) {
            return false;
        }

        $recipientsService = $this->client->recipients();

        $collection = $recipientsService->query([
            'email' => $email,
        ]);

        $recipientId = $collection->current();

        print_r($recipientId);exit;

        return $recipientsService->delete($recipientId);
    }
}
