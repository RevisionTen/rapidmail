<?php

declare(strict_types=1);

namespace RevisionTen\Rapidmail\Services;

use Rapidmail\ApiClient\Client;
use Rapidmail\ApiClient\Exception\ApiException;
use Rapidmail\ApiClient\Service\Response\HalResponse;
use Rapidmail\ApiClient\Service\Response\HalResponseResourceIterator;

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
     */
    public function subscribe(string $campaign, string $email, string $source = null, array $mergeFields = []): bool
    {
        if (!isset($this->config['campaigns'][$campaign])) {
            return false;
        }

        $recipientsService = $this->client->recipients();

        $requestData = [
            'recipientlist_id' => $this->config['campaigns'][$campaign]['list_id'],
            'email' => $email,
        ];

        if (!empty($mergeFields['FNAME'])) {
            $requestData['firstname'] = $mergeFields['FNAME'];
        }
        if (!empty($mergeFields['LNAME'])) {
            $requestData['lastname'] = $mergeFields['LNAME'];
        }
        if (!empty($mergeFields['gender'])) {
            $requestData['gender'] = $mergeFields['gender'];
        }

        try {
            $recipientsService->create($requestData, [
                    'send_activationmail' => 'yes',
                ]
            );
        } catch (ApiException $exception) {
            if ($exception->getCode() === 409) {
                // Is already subscribed.
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Unsubscribes a user from a list.
     *
     * @param string $campaign
     * @param string $email
     *
     * @return bool
     */
    public function unsubscribe(string $campaign, string $email): bool
    {
        if (!isset($this->config['campaigns'][$campaign])) {
            return false;
        }

        $recipientsService = $this->client->recipients();

        try {
            /**
             * @var HalResponseResourceIterator
             */
            $collection = $recipientsService->query([
                'recipientlist_id' => $this->config['campaigns'][$campaign]['list_id'],
                'email' => $email,
            ]);

            foreach ($collection as $result) {
                /**
                 * @var HalResponse $result
                 */
                $id = $result->toArray()['id'] ?? null;
                if ($id && $recipientsService->delete($id)) {
                    return true;
                }
            }
        } catch (ApiException $exception) {
            return false;
        }

        return false;
    }
}
