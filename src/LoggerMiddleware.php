<?php

declare(strict_types=1);

namespace Stryber\Logger;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

final class LoggerMiddleware
{
    private array $ignoreHeaders = [
        'cookie',
        'authorization',
    ];

    public function terminate(Request $request, Response $response): void
    {
        $context = array_merge(
            $this->getRequestContext($request),
            $this->getResponseContext($response),
        );

        $response->getStatusCode() < 400 ?
            Log::channel()->info("", $context) :
            Log::channel()->error("", $context);
    }

    private function clearHeaders(array $headers): array
    {
        return array_diff_key($headers, array_flip($this->ignoreHeaders));
    }

    private function getRequestContext(Request $request): array
    {
        return [
            'request_headers' => $this->clearHeaders($request->headers->all()),
            'request_data' => $request->all(),
            'route' => $request->route()->uri,
        ];
    }

    private function getResponseContext(Response $response): array
    {
        $responseContext = [
            'response_status' => $response->getStatusCode(),
        ];

        if ($response->getStatusCode() >= 400) {
            $responseContext['response_data'] = $this->resolveResponseData($response);
        }

        return $responseContext;
    }

    /**
     * @param Response $response
     * @return false|array|string
     */
    private function resolveResponseData(Response $response)
    {
        return $response instanceof JsonResponse ?
            $response->getData():
            $response->getContent();
    }
}
