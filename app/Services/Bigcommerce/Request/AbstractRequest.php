<?php

namespace App\Services\Bigcommerce\Request;

use App\Services\Bigcommerce\Response;
use Illuminate\Http\Client\Response as HttpResponse;
use Illuminate\Support\Facades\Http;

abstract class AbstractRequest implements RequestInterface
{
    /** General parameter storage */
    protected $parameters = [];

    /** Request Body */
    protected $data = [];

    /** Bigcommerce URLs */
    protected string $apiBaseUrl = 'https://api.bigcommerce.com/stores/';
    protected string $authUrl = 'https://login.bigcommerce.com/oauth2/token';

    public function __construct(string $clientId, string $accessToken, string $storeHash, array $queryParams = [])
    {
        $this->setClientId($clientId);
        $this->setAccessToken($accessToken);
        $this->setStoreHash($storeHash);
        $this->setQueryParams($queryParams);
    }

    /**
     * @throws \Exception
     * @throws Response\Exception
     * @throws Response\NotFoundException
     * @throws Response\TooManyRequestsException
     * @throws Response\UnauthorizedException
     */
    public function send(): Response
    {
        $httpResponse = $this->sendHttp();
        return new Response($this, $httpResponse);
    }

    /**
     * @throws \Exception
     */
    public function sendHttp(): HttpResponse
    {
        return Http::withHeaders($this->getHeaders())
            ->asJson()
            ->acceptJson()
            ->send(
                $this->getHttpMethod(),
                $this->getEndpoint(),
                ['json' => $this->getData()]
            );
    }

    public function getHeaders(): array
    {
        $headers = [];
        if ($this->getClientId()) {
            $headers['X-Auth-Client'] = $this->getClientId();
        }

        if ($this->getAccessToken()) {
            $headers['X-Auth-Token'] = $this->getAccessToken();
        }

        return $headers;
    }

    abstract public function getPath(): string;

    abstract public function getHttpMethod(): string;

    public function getEndpoint(): string
    {
        return $this->getPath() . $this->getQueryString();
    }

    /**
     * Get Request body data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Set Request body data
     *
     * @param array $data
     * @return void
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function successfulResponseShouldIncludeDataAttribute(): bool
    {
        return str_contains($this->getPath(), '/v3/') && in_array($this->getHttpMethod(), ['GET', 'PUT', 'POST']);
    }

    public function successfulResponseShouldIncludeMetaPaginationAttribute(): bool
    {
        return str_contains($this->getPath(), '/v3/')
            && $this->getResourceId() === ''
            && $this->getHttpMethod() === 'GET';
    }

    /**
     * Get parameter
     *
     * @param string $param
     * @return mixed
     */
    protected function getParameter(string $param)
    {
        return $this->parameters[$param] ?? null;
    }

    /**
     * Set parameter
     *
     * @param string $param
     * @param mixed $value
     * @return void
     */
    protected function setParameter(string $param, $value)
    {
        $this->parameters[$param] = $value;
    }

    /**
     * Get Oauth Token
     *
     * @return string
     */
    protected function getAccessToken(): string
    {
        return $this->getParameter('access_token') ?? '';
    }

    /**
     * Set Oauth token
     *
     * @param string $accessToken
     * @return void
     */
    protected function setAccessToken(string $accessToken): void
    {
        $this->setParameter('access_token', $accessToken);
    }

    protected function setStoreHash(string $storeHash): void
    {
        $this->setParameter('store_hash', $storeHash);
    }

    protected function getStoreHash(): string
    {
        return $this->getParameter('store_hash') ?? '';
    }

    /**
     * Get BC App Client ID
     *
     * @return string
     */
    protected function getClientId(): string
    {
        return $this->getParameter('client_id') ?? '';
    }

    /**
     * Set BC App Client ID
     *
     * @param string $clientId
     * @return string
     */
    protected function setClientId(string $clientId): void
    {
        $this->setParameter('client_id', $clientId);
    }

    protected function setQueryParams(array $queryParams): void
    {
        $this->setParameter('query_params', $queryParams);
    }

    protected function getQueryParams(): array
    {
        return $this->getParameter('query_params') ?? [];
    }

    /**
     * Get query params as string
     */
    protected function getQueryString(): string
    {
        $queryString = '';

        if ($params = $this->getQueryParams()) {
            $params = array_map(function ($param) {
                if (is_array($param)) {
                    $param = implode(',', $param);
                }
                return $param;
            }, $params);

            $queryString = '?' . http_build_query($params);
        }

        return $queryString;
    }

    protected function setResourceId(string $resourceId)
    {
        $this->setParameter('resource_id', $resourceId);
    }

    protected function getResourceId(): string
    {
        return $this->getParameter('resource_id') ?? '';
    }

    protected function setSubresourceId(string $subresourceId)
    {
        $this->setParameter('subresource_id', $subresourceId);
    }

    protected function getSubresourceId(): string
    {
        return $this->getParameter('subresource_id') ?? '';
    }

}
