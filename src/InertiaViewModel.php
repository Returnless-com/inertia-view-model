<?php

declare(strict_types=1);

namespace Returnless\InertiaViewModel;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Response;

/**
 * @implements \Illuminate\Contracts\Support\Arrayable<array-key, mixed>
 */
abstract class InertiaViewModel implements Arrayable, Responsable
{
    /** @var array<array-key, mixed> */
    protected array $data = [];

    /** @var list<string> */
    protected array $ignore = [];

    protected string $viewPath;

    public function toResponse($request): JsonResponse|Response
    {
        if ($request->wantsJson()) {
            return new JsonResponse($this->items());
        }

        /** @var \Inertia\Response $inertiaResponse */
        $inertiaResponse = inertia($this->viewPath . '/Page', $this->items());

        return $inertiaResponse->toResponse($request);
    }

    public function toArray(): array
    {
        return $this->items()->all();
    }

    /**
     * @return \Illuminate\Support\Collection<array-key, mixed>
     */
    protected function items(): Collection
    {
        $class = new ReflectionClass($this);

        $publicProperties = collect($class->getProperties(ReflectionProperty::IS_PUBLIC))
            ->reject(fn (ReflectionProperty $property) => $this->shouldIgnore($property->getName()))
            ->mapWithKeys(function (ReflectionProperty $property) {
                return [$property->getName() => $this->{$property->getName()}];
            });

        $publicMethods = collect($class->getMethods(ReflectionMethod::IS_PUBLIC))
            ->reject(fn (ReflectionMethod $method) => $this->shouldIgnore($method->getName()))
            ->mapWithKeys(function (ReflectionMethod $method) {
                return [
                    $method->getName() => $this->{$method->getName()}(),
                ];
            });

        return $publicProperties
            ->merge($publicMethods)
            ->merge($this->data);
    }

    private function shouldIgnore(string $methodName): bool
    {
        if (Str::startsWith($methodName, '__')) {
            return true;
        }

        return in_array($methodName, $this->ignoredMethods(), true);
    }

    /**
     * @return list<string>
     */
    private function ignoredMethods(): array
    {
        return array_merge([
            'toArray',
            'toResponse',
        ], $this->ignore);
    }
}
