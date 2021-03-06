<?php

namespace Ejarnutowski\LaravelApiKey\Http\Middleware;

use function abort;
use function app;
use Closure;
use function config;
use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Ejarnutowski\LaravelApiKey\Models\ApiKeyAccessEvent;
use Ejarnutowski\LaravelApiKey\Providers\ApiUser;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use function preg_match;

class AuthorizeApiKey
{
    const AUTH_HEADER = 'X-Authorization';

    /**
     * Handle the incoming request
     *
     * @param Request $request
     * @param Closure $next
     * @return \Illuminate\Contracts\Routing\ResponseFactory|mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header(config('apikey.header.name'));

        $format = config('apikey.header.format');
        if ($format === null) {
            $value = $header;
        } else {
            // Get the value from the header.
            $values = [];
            preg_match('/' . $format . '/', $header, $values);
            $value = $values[1] ?? null;
        }

        if (config('app.debug') && $value === null) {
            $value = $request->input('apiKey');
        }

        if ($value !== null) {
            $apiKey = ApiKey::getByKey($value);

            if ($apiKey instanceof ApiKey) {
                $this->logAccessEvent($request, $apiKey);
                // Make the user available globally.
                $apiUser = app(ApiUser::class);
                $apiUser->setKey($apiKey->key);
                $apiUser->setName($apiKey->name);

                return $next($request);
            }
        }

        abort(Response::HTTP_UNAUTHORIZED);
        return null;
    }

    /**
     * Log an API key access event
     *
     * @param Request $request
     * @param ApiKey  $apiKey
     */
    protected function logAccessEvent(Request $request, ApiKey $apiKey)
    {
        $event = new ApiKeyAccessEvent;
        $event->api_key_id = $apiKey->id;
        $event->ip_address = $request->ip();
        $event->url        = $request->fullUrl();
        $event->save();
    }
}
