<?php

declare(strict_types=1);

namespace RevisionTen\Rapidmail\Services;

use Rapidmail\ApiClient\Client;
use Rapidmail\ApiClient\Exception\ApiClientException;

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
     * @throws ApiClientException
     *
     * @return bool
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
     * @throws GuzzleException
     */
    public function unsubscribe(string $campaign, string $email): bool
    {
        if (!isset($this->config['campaigns'][$campaign])) {
            return false;
        }

        $subscriberHash = md5(strtolower($email));

        $requestBody = json_encode([
            'email_address' => $email,
            'status' => 'unsubscribed',
        ]);

        // Unsubscribe email.
        $response = $this->client->request('PATCH', '/lists/'.$this->config['campaigns'][$campaign]['list_id'].'/members/'.$subscriberHash, [
            'body' => $requestBody,
            'http_errors' => false,
        ]);

        return 200 === $response->getStatusCode();
    }
}
