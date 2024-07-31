<?php

declare(strict_types=1);

namespace Returnless\InertiaViewModel\Tests\stubs;

use Returnless\InertiaViewModel\InertiaViewModel;
use stdClass;

final class DummyViewModel extends InertiaViewModel
{
    public string $property = 'abc';

    protected array $ignore = ['ignoredMethod'];

    public function __construct()
    {
        // This one is here for testing purposes
    }

    public function post(): stdClass
    {
        return (object) [
            'title' => 'title',
            'body' => 'body',
        ];
    }

    public function categories(): array
    {
        return [
            (object) [
                'name' => 'category A',
            ],
            (object) [
                'name' => 'category B',
            ],
        ];
    }

    public function ignoredMethod(): bool
    {
        return true;
    }
}
