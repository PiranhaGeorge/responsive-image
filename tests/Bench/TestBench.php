<?php

namespace Tempest\Markdown\Tests\Bench;

use Generator;
use PhpBench\Attributes as Bench;

#[Bench\Warmup(1)]
#[Bench\RetryThreshold(5)]
#[Bench\OutputTimeUnit('milliseconds', 3)]
#[Bench\Iterations(5)]
#[Bench\Revs(3)]
#[Bench\ParamProviders('provideFixtures')]
final readonly class TestBench
{
    public function bench(array $params): void
    {
        $contents = $params['contents'] ?? '';

        usleep(1000);
    }

    public function provideFixtures(): Generator
    {
        $files = glob(__DIR__ . '/Fixtures/*') ?: [];

        foreach ($files as $path) {
            yield pathinfo($path, PATHINFO_FILENAME) => ['contents' => file_get_contents($path)];
        }
    }
}
