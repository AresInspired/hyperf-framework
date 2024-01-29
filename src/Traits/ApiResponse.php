<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace AresInspired\Framework\Traits;

use Hyperf\Codec\Json;
use Hyperf\Context\Context;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;

trait ApiResponse
{
    public function success(array $data = [], array $meta = []): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);

        $result = [
            'success' => true,
            'error_code' => 0,
            'data' => $data,
        ];

        if ($meta) {
            $result['meta'] = $meta;
        }

        return $response
            ->withStatus(200)
            ->withAddedHeader('content-type', 'application/json; charset=utf-8')
            ->withBody(new SwooleStream(Json::encode($result)));
    }

    /**
     * 返回错误信息.
     */
    public function error(ResponseInterface $response, int $errorCode = 500, string $message = '', array $payload = []): ResponseInterface
    {
        $body = array_merge($payload, [
            'success' => false,
            'error_code' => $errorCode,
            'error_msg' => ! empty($message) ? $message : '',
        ]);

        $stream = new SwooleStream(json_encode($body));

        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->withBody($stream);
    }
}
