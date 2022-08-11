<?php

namespace App\Services\Bigcommerce;

use App\Models\BigcommerceStore;
use App\Services\Bigcommerce;
use Illuminate\Support\Facades\Log;

class WebhookManager
{

    /**
     * Array of webhook notification URLs indexed by scope.
     */
    private array $requiredWebhooks = [];

    private Bigcommerce $bigcommerce;

    public function __construct(Bigcommerce $bigcommerce)
    {
        $this->requiredWebhooks = [
            'store/app/uninstalled' => route('webhook.app.uninstalled'),
        ];

        $this->bigcommerce = $bigcommerce;
    }

    /**
     * Check currently set up BigCommerce webhooks that we're subscribing to
     * Create new or update old webhooks as necessary.
     *
     * @throws \Exception
     * @throws Response\Exception
     * @throws Response\NotFoundException
     * @throws Response\TooManyRequestsException
     * @throws Response\UnauthorizedException
     */
    public function setupAndMaintain(BigcommerceStore $store)
    {
        // If store has been uninstalled since job was added to the queue, don't bother checking webhooks.
        if (!$store->installed) {
            Log::info(sprintf('Not updating webhooks because store (with hash %s) is uninstalled', $store->store_hash));
            return;
        }

        $currentWebhooks = $this->getWebhooks($store);

        foreach ($this->requiredWebhooks as $scope => $destination) {
            $existingWebhook = $this->getWebhookByScope($currentWebhooks, $scope);

            if (!$existingWebhook) {
                $this->createWebhook($store, $scope, $destination);
                continue;
            }

            if (array_key_exists('destination', $existingWebhook) && $existingWebhook['destination'] !== $destination) {
                $this->updateWebhookDestination($store, $existingWebhook, $destination);
            }

            if (isset($existingWebhook['is_active']) && $existingWebhook['is_active'] === false) {
                Log::info('Found inactive ' . $existingWebhook['scope'] . ' webhook for store with ID: ' . $store->id);
                $this->updateWebhookIsActive($store, $existingWebhook);
            }
        }
    }


    /**
     * @throws \Exception
     * @throws Response\Exception
     * @throws Response\NotFoundException
     * @throws Response\TooManyRequestsException
     * @throws Response\UnauthorizedException
     */
    private function getWebhooks(BigcommerceStore $store): array
    {
        $response = $this->bigcommerce->fetchWebhooks($store->access_token, $store->store_hash);
        return $response['data'];

    }

    /**
     * Search webhooks for one with provided scope
     */
    private function getWebhookByScope(array $webhooks, string $scope): ?array
    {
        foreach ($webhooks as $webhook) {
            if ($webhook['scope'] === $scope) {
                return $webhook;
            }
        }

        return null;
    }

    /**
     * Create new webhook
     *
     * @throws Response\Exception
     * @throws Response\NotFoundException
     * @throws Response\TooManyRequestsException
     * @throws Response\UnauthorizedException
     */
    private function createWebhook(BigcommerceStore $store, string $scope, string $destination)
    {
        $webhook = [
            'scope' => $scope,
            'destination' => $destination,
            'is_active' => true,
            'headers' => [
                'secret' => config('bigcommerce.webhook_secret'),
            ],
        ];

        $this->bigcommerce->createWebhook($store->access_token, $store->store_hash, $webhook);
    }

    /**
     * Update webhook with new destination.
     *
     * @throws \Exception
     * @throws Response\Exception
     * @throws Response\NotFoundException
     * @throws Response\TooManyRequestsException
     * @throws Response\UnauthorizedException
     */
    private function updateWebhookDestination(BigcommerceStore $store, array $webhook, string $destination)
    {
        if (!isset($webhook['id'])) {
            throw new \Exception('Missing webhook ID');
        }

        $webhook['destination'] = $destination;

        $this->bigcommerce->updateWebhook($store->access_token, $store->store_hash, $webhook['id'], $webhook);
    }

    /**
     * Re-enable webhook
     *
     * @throws \Exception
     * @throws Response\Exception
     * @throws Response\NotFoundException
     * @throws Response\TooManyRequestsException
     * @throws Response\UnauthorizedException
     */
    private function updateWebhookIsActive(BigcommerceStore $store, array $webhook)
    {
        if (!isset($webhook['id'])) {
            throw new \Exception('Missing webhook ID');
        }

        $webhook['is_active'] = true;

        $this->bigcommerce->updateWebhook($store->access_token, $store->store_hash, $webhook['id'], $webhook);
    }
}
