<?php

declare(strict_types=1);

namespace Returnless\InertiaViewModel\Tests;

use PHPUnit\Framework\Attributes\Test;
use Returnless\InertiaViewModel\Tests\stubs\DummyViewModel;

final class InertiaViewModelTest extends TestCase
{
    #[Test]
    public function it_lists_public_methods(): void
    {
        $inertiaViewModel = new DummyViewModel;

        self::assertArrayHasKey('post', $inertiaViewModel->toArray());
        self::assertArrayHasKey('categories', $inertiaViewModel->toArray());
    }

    #[Test]
    public function it_lists_public_properties(): void
    {
        $inertiaViewModel = new DummyViewModel;

        self::assertArrayHasKey('property', $inertiaViewModel->toArray());
    }

    #[Test]
    public function it_ignores_ignored_methods(): void
    {
        $inertiaViewModel = new DummyViewModel;

        self::assertArrayNotHasKey('ignoredMethod', $inertiaViewModel->toArray());
    }

    #[Test]
    public function it_ignores_to_array_method(): void
    {
        $inertiaViewModel = new DummyViewModel;

        self::assertArrayNotHasKey('toArray', $inertiaViewModel->toArray());
    }

    #[Test]
    public function it_ignores_to_response_method(): void
    {
        $inertiaViewModel = new DummyViewModel;

        self::assertArrayNotHasKey('toResponse', $inertiaViewModel->toArray());
    }
}
