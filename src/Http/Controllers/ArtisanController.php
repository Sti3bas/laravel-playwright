<?php declare(strict_types=1);

namespace Saucebase\LaravelPlaywright\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Process;

class ArtisanController
{

    public function __invoke(Request $request): JsonResponse
    {
        $command = (string) $request->string('command');
        $parameters = (array) $request->input('parameters');

        $worker = request()->header('X-Playwright-Worker');

        $result = Process::env([
            'DB_CONNECTION' => 'playwright_' . $worker,
            'MEDIA_DISK' => 'playwright_' . $worker,
            'SCOUT_PREFIX' => 'playwright_' . $worker . '_',
        ])->run('php ' . base_path('artisan') . ' ' . $command . ' ' . implode(' ', $parameters));

        return Response::json([
            'code' => $result->exitCode(),
            'output' => $result->output(),
        ]);
    }

}
