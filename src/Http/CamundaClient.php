<?php

declare(strict_types=1);

namespace Laravolt\Camunda\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Laravolt\Camunda\Exceptions\InvalidArgumentException;

class CamundaClient
{
    public static function make(): PendingRequest
    {
        return Http::baseUrl(config('services.camunda.url'));
    }

    protected static function makeIdentifierPath(string $path, array $args): string
    {
        $args += ['id' => false, 'key' => false, 'tenantId' => false];
        $identifier = $args['id'];
        if ($args['key']) {
            $identifier = 'key/'.$args['key'];
            if ($args['tenantId']) {
                $identifier .= '/tenant-id/'.$args['tenantId'];
            }
        }

        if (! $identifier) {
            throw new InvalidArgumentException('');
        }

        return str_replace('{identifier}', $identifier, $path);
    }

    protected static function formatVariables(array $data): array
    {
        $map = [
            'status_administrasi' => 'Boolean',
            'lulus_wawancara' => 'Boolean',
        ];

        return collect($data)->transform(
            function ($item, $key) use ($map) {
                $type = $map[$key] ?? 'String';

                $item = match ($type) {
                    'Boolean' => (bool) $item,
                    default => $item
                };

                return [
                    'type' => $type,
                    'value' => $item,
                ];
            }
        )->toArray();
    }
}
