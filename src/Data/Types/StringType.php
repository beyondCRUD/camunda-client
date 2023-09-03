<?php

namespace BeyondCRUD\LaravelCamundaClient\Data\Types;

class StringType
{
    public function __invoke(string $value): array
    {
        return ['value' => (string) $value, 'type' => 'String'];
    }
}
