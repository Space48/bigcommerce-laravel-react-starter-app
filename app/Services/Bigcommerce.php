<?php

namespace App\Services;

use App\Services\Bigcommerce\Request\CreateCategoryMetafieldRequest;
use App\Services\Bigcommerce\Request\CreateWebhookRequest;
use App\Services\Bigcommerce\Request\CreateWidgetTemplateRequest;
use App\Services\Bigcommerce\Request\DeleteWidgetTemplateRequest;
use App\Services\Bigcommerce\Request\FetchBrandsRequest;
use App\Services\Bigcommerce\Request\FetchCategoryMetafieldsRequest;
use App\Services\Bigcommerce\Request\FetchCategoryProductsSortOrderRequest;
use App\Services\Bigcommerce\Request\FetchCategoryRequest;
use App\Services\Bigcommerce\Request\FetchCategoryTreeRequest;
use App\Services\Bigcommerce\Request\FetchChannelsRequest;
use App\Services\Bigcommerce\Request\FetchCountryRequest;
use App\Services\Bigcommerce\Request\FetchProductRequest;
use App\Services\Bigcommerce\Request\FetchProductsRequest;
use App\Services\Bigcommerce\Request\FetchStoreInformationRequest;
use App\Services\Bigcommerce\Request\FetchWebhooksRequest;
use App\Services\Bigcommerce\Request\FetchWidgetTemplatesRequest;
use App\Services\Bigcommerce\Request\Payload\WidgetTemplatePayload;
use App\Services\Bigcommerce\Request\PermanentTokenRequest;
use App\Services\Bigcommerce\Request\ProxyRequest;
use App\Services\Bigcommerce\Request\UpdateCategoryMetafieldRequest;
use App\Services\Bigcommerce\Request\UpdateCategoryProductsSortOrderRequest;
use App\Services\Bigcommerce\Request\UpdateProductsRequest;
use App\Services\Bigcommerce\Request\UpdateWebhookRequest;
use App\Services\Bigcommerce\Request\UpdateWidgetTemplateRequest;
use Exception;
use Illuminate\Http\Client\Response as HttpResponse;

/**
 * BigCommerce API Service
 *
 * Service to interact with the BigCommerce API. Conscious effort to just let it focus
 * on API communication and returning the response data, with little post-processing, leaving
 * that task to the consumers.
 *
 * Class Bigcommerce
 * @package App\Services
 */
class Bigcommerce
{
    /**
     * @param string $clientId - BigCommerce App Client ID
     * @param string $clientSecret - BigCommerce App ClientSecret
     */
    public function __construct(protected string $clientId, protected string $clientSecret)
    {
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     */
    public function requestPermanentOauthToken(string $code, string $scope, string $context, string $installUrl): array
    {
        $request = new PermanentTokenRequest(
            $this->clientId,
            $this->clientSecret,
            $installUrl,
            $code,
            $scope,
            $context
        );
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function fetchBrands(string $accessToken, string $storeHash, array $params = []): array
    {
        $params = array_merge(['limit' => '250'], $params);

        $request = new FetchBrandsRequest($this->clientId, $accessToken, $storeHash, $params);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     */
    public function fetchChannels(string $accessToken, string $storeHash): array
    {
        $request = new FetchChannelsRequest($this->clientId, $accessToken, $storeHash, [
            'available' => 'true' , 'type:in' => 'storefront'
        ]);
        $response = $request->send();
        return $response->getData();
    }


    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function fetchStoreInformation(string $accessToken, string $storeHash): array
    {
        $request = new FetchStoreInformationRequest($this->clientId, $accessToken, $storeHash);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     */
    public function fetchWidgetTemplates(string $accessToken, string $storeHash, int $channelId): array
    {
        $request = new FetchWidgetTemplatesRequest($this->clientId, $accessToken, $storeHash, $channelId);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     */
    public function createWidgetTemplate(string $accessToken, string $storeHash, WidgetTemplatePayload $widgetTemplate): array
    {
        $request = new CreateWidgetTemplateRequest($this->clientId, $accessToken, $storeHash, $widgetTemplate);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     */
    public function updateWidgetTemplate(string $accessToken, string $storeHash, string $widgetTemplateId, WidgetTemplatePayload $widgetTemplate): array
    {
        $request = new UpdateWidgetTemplateRequest($this->clientId, $accessToken, $storeHash, $widgetTemplateId, $widgetTemplate);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     */
    public function deleteWidgetTemplate(string $accessToken, string $storeHash, string $widgetTemplateId): array|null
    {
        $request = new DeleteWidgetTemplateRequest($this->clientId, $accessToken, $storeHash, $widgetTemplateId);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function fetchCategoryTree(string $accessToken, string $storeHash): array
    {
        $request = new FetchCategoryTreeRequest($this->clientId, $accessToken, $storeHash);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function fetchCategory(string $accessToken, string $storeHash, string $categoryId): array
    {
        $request = new FetchCategoryRequest($this->clientId, $accessToken, $storeHash, $categoryId);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function fetchCategoryMetafields(string $accessToken, string $storeHash, string $categoryId, $params = []): array
    {
        $request = new FetchCategoryMetafieldsRequest($this->clientId, $accessToken, $storeHash, $categoryId, $params);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function createCategoryMetafield(string $accessToken, string $storeHash, string $categoryId, array $metafield)
    {
        $request = new CreateCategoryMetafieldRequest($this->clientId, $accessToken, $storeHash, $categoryId, $metafield);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function updateCategoryMetafield(string $accessToken, string $storeHash, string $categoryId, string $metafieldId, array $metafield): array
    {
        $request = new UpdateCategoryMetafieldRequest($this->clientId, $accessToken, $storeHash, $categoryId, $metafieldId, $metafield);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function fetchCategoryProductsSortOrder(string $accessToken, string $storeHash, string $categoryId, $params = []): array
    {
        // 250 is the max BC supports
        if (isset($params['limit']) && $params['limit'] > 250) {
            $params['limit'] = 250;
        }

        $request = new FetchCategoryProductsSortOrderRequest($this->clientId, $accessToken, $storeHash, $categoryId, $params);
        $response = $request->send();

        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function updateCategoryProductsSortOrder(string $accessToken, string $storeHash, string $categoryId, array $productsSortOrder): array
    {
        $request = new UpdateCategoryProductsSortOrderRequest($this->clientId, $accessToken, $storeHash, $categoryId, $productsSortOrder);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function fetchProducts(string $accessToken, string $storeHash, array $params = []): array
    {
        $params = array_merge(['sort' => 'id', 'direction' => 'desc', 'limit' => '250'], $params);

        // If options or modifiers are requested, BigCommerce restricts limit to 10 products per page
        if (isset($params['include']) && $params['limit'] > 10 && preg_match('/options|modifiers/', $params['include'])) {
            $params['limit'] = 10;
        }

        $request = new FetchProductsRequest($this->clientId, $accessToken, $storeHash, $params);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function fetchProduct(string $accessToken, string $storeHash, string $productId, array $params = []): array
    {
        $request = new FetchProductRequest($this->clientId, $accessToken, $storeHash, $productId, $params);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function fetchLatestProduct(string $accessToken, string $storeHash, array $params = []): array
    {
        $params = array_merge(['limit' => '1'], $params);
        return $this->fetchProducts($accessToken, $storeHash, $params);
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function updateProducts(string $accessToken, string $storeHash, array $products, array $params = []): array
    {
        $request = new UpdateProductsRequest($this->clientId, $accessToken, $storeHash, $products, $params);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function fetchWebhooks(string $accessToken, string $storeHash): array
    {
        $request = new FetchWebhooksRequest($this->clientId, $accessToken, $storeHash);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function createWebhook(string $accessToken, string $storeHash, array $webhook): array
    {
        $request = new CreateWebhookRequest($this->clientId, $accessToken, $storeHash, $webhook);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function updateWebhook(string $accessToken, string $storeHash, string $webhookId, array $webhook): array
    {
        $request = new UpdateWebhookRequest($this->clientId, $accessToken, $storeHash, $webhookId, $webhook);
        $response = $request->send();
        return $response->getData();
    }

    /**
     * @throws Bigcommerce\Response\Exception
     * @throws Bigcommerce\Response\NotFoundException
     * @throws Bigcommerce\Response\TooManyRequestsException
     * @throws Bigcommerce\Response\UnauthorizedException
     * @throws Bigcommerce\Response\UnprocessableException
     */
    public function fetchCountry(string $accessToken, string $storeHash, string $countryId): array
    {
        $request = new FetchCountryRequest($this->clientId, $accessToken, $storeHash, $countryId);
        $response = $request->send();
        return $response->getData();
    }


    /**
     * Send an API request to BC without validating the message or decoding the response.
     *
     * @param string $accessToken - BC Access Token
     * @param string $method - HTTP Method
     * @param string $storeHash
     * @param string $endpoint - API URL including v2/v3
     * @param array $queryParams - Query parameters
     * @param null $data - Body data
     *
     * @return HttpResponse
     * @throws Exception
     */
    public function rawRequest(string $accessToken, string $method, string $storeHash, string $endpoint, array $queryParams = [], $data = null): HttpResponse
    {
        if (strrpos($endpoint, 'v2') !== false) {
            // For v2 endpoints, add a .json to the end of each endpoint, to normalize against the v3 API standards
            $endpoint .= '.json';
        }

        $request = new ProxyRequest(
            $this->clientId,
            $accessToken,
            $method,
            $storeHash,
            $endpoint,
            $queryParams,
            $data
        );

        return $request->sendHttp();
    }
}
