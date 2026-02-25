<?php declare(strict_types=1);

namespace Saucebase\LaravelPlaywright\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Saucebase\LaravelPlaywright\Services\Config;
use Symfony\Component\HttpFoundation\Response;

class VerifyPlaywrightSecret
{

    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $secret = Config::secret();

        if ($secret !== null) {
            $provided = $request->header('X-Playwright-Secret')
                ?? $request->input('_secret');

            $provided = is_string($provided) ? $provided : '';

            if (!hash_equals($secret, $provided)) {
                abort(401, 'Unauthorized');
            }
        }

        return $next($request);
    }

}
