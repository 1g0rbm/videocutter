<?php

declare(strict_types=1);

namespace App\Dto\Request;

use Symfony\Component\HttpFoundation\Request;

interface RequestDtoInterface
{
    public function __construct(Request $request);
}