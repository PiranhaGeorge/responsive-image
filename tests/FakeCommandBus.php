<?php

namespace Tempest\ResponsiveImage\Tests;

use Tempest\CommandBus\CommandBus;

final class FakeCommandBus implements CommandBus
{
    public array $dispatched = [];

    public function dispatch(object $command): void
    {
        $this->dispatched[] = $command;
    }

    public function getHistory(): array
    {
        return $this->dispatched;
    }
}
