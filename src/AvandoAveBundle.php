<?php

declare(strict_types=1);

namespace Avando\AveBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class AvandoAveBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }
}
