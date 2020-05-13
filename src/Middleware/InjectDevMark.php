<?php

namespace LaravelDevMark\Middleware;

use Closure;
use Illuminate\Http\Request;
use LaravelDevMark\LaravelDevMark;

class InjectDevMark
{
    /**
     * @var LaravelDevMark
     */
    protected $devMark;

    public function __construct(LaravelDevMark $devMark)
    {
        $this->devMark = $devMark;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$this->devMark->isEnabled()) {
            return $next($request);
        }

        $response = $next($request);

        $this->devMark->modifyResponse($response);

        return $response;
    }
}
