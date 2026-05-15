<?php declare(strict_types=1);

namespace Saucebase\LaravelPlaywright\Http\Controllers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FactoryController
{

    public function __invoke(Request $request): JsonResponse
    {
        if ($request->has('items')) {
            $request->validate([
                'items' => 'required|array',
                'items.*.model' => 'required|string',
                'items.*.count' => 'nullable|integer',
                'items.*.attrs' => 'nullable|array',
                'items.*.states' => 'nullable|array',
            ]);

            $results = [];

            /** @var list<array{model: string, count?: int|null, attrs?: array<string, mixed>, states?: list<string>}> $items */
            $items = $request->input('items');

            foreach ($items as $item) {
                $results[] = $this->processItem(
                    $item['model'],
                    $item['count'] ?? null,
                    $item['attrs'] ?? [],
                    $item['states'] ?? []
                );
            }

            return Response::json($results);
        }

        $request->validate([
            'model' => 'string|required',
            'count' => 'nullable|integer',
            'attrs' => 'array',
            'states' => 'nullable|array',
        ]);

        $modelClass = (string) $request->string('model');
        $count = $request->has('count') ? $request->integer('count') : null;
        /** @var array<string, mixed> $attrs */
        $attrs = (array) $request->input('attrs');
        /** @var list<string> $states */
        $states = (array) $request->input('states', []);

        return Response::json($this->processItem($modelClass, $count, $attrs, $states));
    }

    /**
     * @param  array<string, mixed>  $attrs
     * @param  list<string>          $states
     * @return mixed
     */
    private function processItem(string $modelClass, ?int $count, array $attrs, array $states): mixed
    {
        if (!class_exists($modelClass)) {
            $modelClass = 'App\\Models\\' . $modelClass;
        }

        if (!class_exists($modelClass)) {
            abort(422, 'Model not found');
        }

        $model = app($modelClass);

        if (!$model instanceof Model) {
            abort(422, 'Model not found');
        }

        if (!method_exists($model, 'factory')) {
            abort(422, 'Model factory not found');
        }

        /** @var Factory<Model> $modelFactory */
        $modelFactory = $model->factory();

        if ($count !== null) {
            $modelFactory = $modelFactory->count($count);
        }

        foreach ($states as $state) {
            if (!method_exists($modelFactory, $state)) {
                abort(422, "Factory state [{$state}] not found");
            }

            $stateResult = $modelFactory->{$state}();

            if (!$stateResult instanceof Factory) {
                abort(422, "Factory state [{$state}] must return a factory instance");
            }

            $modelFactory = $stateResult;
        }

        return $modelFactory->create($attrs);
    }

}
