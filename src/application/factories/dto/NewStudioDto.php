<?php

declare(strict_types=1);

namespace game\application\factories\dto;

class NewStudioDto
{
    public function __construct(
        readonly string $name
    ) {
    }
}