<?php

namespace SLoggerLaravel\Watchers\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use SLoggerLaravel\Watchers\AbstractSLoggerWatcher;

class SLoggerHttpClientWatcher extends AbstractSLoggerWatcher
{
    public function register(): void
    {
        if ((float) $this->app->version() < 10) {
            /** @see SLoggerGuzzleHandlerFactory */

            return;
        }

        $headerKey = $this->app['config']['slogger.requests.header_parent_trace_id_key'];

        if (!$headerKey) {
            return;
        }

        Http::globalRequestMiddleware(
            function (RequestInterface $request) use ($headerKey) {
                if ($this->isSubscribeRequest($request)) {
                    $request->withHeader($headerKey, $this->traceIdContainer->getParentTraceId());

                    $this->handleRequest($request);
                }

                return $request;
            }
        );

        Http::globalResponseMiddleware(
            function (ResponseInterface $response) {
                if ($this->isSubscribeResponse($response)) {
                    $this->handleResponse($response);
                }

                return $response;
            }
        );
    }

    public function handleRequest(RequestInterface $request): void
    {
        $uri = (string) $request->getUri();

        $this->processor->push(
            type: 'http-request',
            tags: [
                $uri,
            ],
            data: [
                'method' => $request->getMethod(),
                'uri' => $uri,
                'headers' => $this->getRequestHeaders($request),
                'payload' => $this->getRequestPayload($request),
            ]
        );
    }

    public function handleResponse(ResponseInterface $response): void
    {
        $url = $this->getResponseUrl($response);

        $this->processor->push(
            type: 'http-response',
            tags: $url ? [$url] : [],
            data: [
                'status_code' => $response->getStatusCode(),
                'headers'     => $this->getResponseHeaders($response),
                'body'        => $this->getResponseBody($response),
            ]
        );
    }

    protected function isSubscribeRequest(RequestInterface $request): bool
    {
        return true;
    }

    protected function isSubscribeResponse(ResponseInterface $response): bool
    {
        return true;
    }

    protected function getRequestHeaders(RequestInterface $request): array
    {
        return $request->getHeaders();
    }

    protected function getRequestPayload(RequestInterface $request): array
    {
        $body = $request->getBody();

        $content = json_decode($body->getContents(), true) ?: [];

        $body->rewind();

        return $content;
    }

    protected function getResponseHeaders(ResponseInterface $response): array
    {
        return $response->getHeaders();
    }

    protected function getResponseBody(ResponseInterface $response): array
    {
        $body = $response->getBody();

        $content = json_decode($body->getContents(), true) ?: [];

        $body->rewind();

        return $content;
    }

    protected function getResponseUrl(ResponseInterface $response): ?string
    {
        $headers = $response->getHeaders();

        $location = Arr::get($headers, 'location') ?? Arr::get($headers, 'Location');

        if (!$location) {
            return null;
        }

        if (is_string($location)) {
            return $location;
        }

        if (!is_array($location)) {
            return null;
        }

        $location = $location[0] ?? null;


        if (is_string($location)) {
            return $location;
        }

        return null;
    }
}
