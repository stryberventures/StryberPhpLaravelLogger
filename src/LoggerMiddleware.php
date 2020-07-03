<?php

declare(strict_types=1);

namespace Stryber\Logger;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

final class LoggerMiddleware
{
    private array $ignoreHeaders;
    private array $ignoreRequestParams;
    private array $ignoreResponseParams;

    public function __construct(array $ignoreHeaders, array $ignoreRequestParams, array $ignoreResponseParams)
    {
        $this->ignoreHeaders = $ignoreHeaders;
        $this->ignoreRequestParams = $ignoreRequestParams;
        $this->ignoreResponseParams = $ignoreResponseParams;
    }

    public function terminate(Request $request, Response $response): void
    {
        $context = array_merge(
            $this->getRequestContext($request),
            $this->getResponseContext($response),
        );

        $response->getStatusCode() < 400 ?
            Log::channel('stdout')->info("", $context) :
            Log::channel('stderr')->error("", $context);
    }

    private function cleanContext(array $context, array $ignore): array
    {
        return array_diff_key($context, array_flip($ignore));
    }

    private function getRequestContext(Request $request): array
    {
        return [
            'request_headers' => $this->cleanContext($request->headers->all(), $this->ignoreHeaders),
            'request_data' => $this->cleanContext($request->all(), $this->ignoreRequestParams),
            'route' => $request->route()->uri,
        ];
    }

    private function getResponseContext(Response $response): array
    {
        $responseContext = [
            'response_status' => $response->getStatusCode(),
        ];

        if ($response->getStatusCode() >= 400) {
            $responseData = $this->resolveResponseData($response);

            if (is_array($responseData)) {
                $responseContext = $this->cleanContext($responseData, $this->ignoreResponseParams);
            }

            $responseContext['response_data'] = $responseData;
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
