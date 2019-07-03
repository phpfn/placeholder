<?php
/**
 * This file is part of Placeholder package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Placeholder;

use Serafim\Placeholder\Symbol\Sign;
use Serafim\Placeholder\Symbol\SignInterface;

/**
 * Class Symbol
 */
final class Symbol
{
    /**
     * @var string
     */
    private const TYPE = 'stream';

    /**
     * @var string
     */
    private const CONTEXT_FIELD_SIGN = '__signifier';

    /**
     * @var string
     */
    private const ERROR_FOPEN = 'Cannot create a symbol: Memory stream is not accessible';

    /**
     * @var string
     */
    private const ERROR_TYPE = '%s() expects parameter 1 to be a symbol, but %s given';

    /**
     * @param string|null $name
     * @return mixed
     */
    public static function create(string $name = null)
    {
        $resource = @\fopen('php://memory', 'rb+');

        \assert(\is_resource($resource), self::ERROR_FOPEN);

        return self::signed($resource, $name);
    }

    /**
     * @param resource $resource
     * @param string|null $name
     * @return resource
     */
    private static function signed($resource, ?string $name)
    {
        \stream_context_set_option($resource, [
            'php' => [
                self::CONTEXT_FIELD_SIGN => Sign::fromResourceStream($resource, $name),
            ],
        ]);

        return $resource;
    }

    /**
     * @param resource|mixed $symbol
     * @return string|null
     */
    public static function nameOf($symbol): ?string
    {
        if (! self::isSymbol($symbol)) {
            throw new \TypeError(\sprintf(self::ERROR_TYPE, __METHOD__, \gettype($symbol)));
        }

        /** @var SignInterface|null $sign */
        $sign = self::signOf($symbol);

        return $sign ? $sign->getName() : null;
    }

    /**
     * @param mixed $symbol
     * @return bool
     */
    public static function isSymbol($symbol): bool
    {
        return self::isStreamResource($symbol) && self::isSignedStreamResource($symbol);
    }

    /**
     * @param resource|mixed $resource
     * @return bool
     */
    private static function isStreamResource($resource): bool
    {
        try {
            return \get_resource_type($resource) === self::TYPE;
        } catch (\TypeError $e) {
            return false;
        }
    }

    /**
     * @param resource $resource
     * @return bool
     */
    private static function isSignedStreamResource($resource): bool
    {
        return self::signOf($resource) instanceof SignInterface;
    }

    /**
     * @param resource $symbol
     * @return \Serafim\Placeholder\Symbol\SignInterface|null
     */
    private static function signOf($symbol): ?SignInterface
    {
        $options = \stream_context_get_options($symbol);

        return $options['php'][self::CONTEXT_FIELD_SIGN] ?? null;
    }

    /**
     * @param resource|mixed $symbol
     * @return array|null
     */
    public static function contextOf($symbol): ?array
    {
        if (! self::isSymbol($symbol)) {
            throw new \TypeError(\sprintf(self::ERROR_TYPE, __METHOD__, \gettype($symbol)));
        }

        /** @var SignInterface|null $sign */
        $sign = self::signOf($symbol);

        return $sign ? $sign->getContext() : null;
    }
}
