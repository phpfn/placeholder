<?php
/**
 * This file is part of Placeholder package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Placeholder;

/**
 * Class Placeholder
 */
final class Placeholder
{
    /**
     * @param iterable $items
     * @param \Closure $each
     * @return array
     */
    public static function map(iterable $items, \Closure $each): array
    {
        return \iterator_to_array(self::lazyMap($items, $each));
    }

    /**
     * @param iterable $items
     * @param \Closure $each
     * @return \Traversable
     */
    public static function lazyMap(iterable $items, \Closure $each): \Traversable
    {
        foreach ($items as $key => $value) {
            yield $key => self::match($value) ? $each($value, $key, $items) : $value;
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function match($value): bool
    {
        return $value === self::get();
    }

    /**
     * @return mixed
     */
    public static function get()
    {
        static $placeholder;

        return $placeholder ?? $placeholder = Symbol::create('_');
    }

    /**
     * @param iterable $items
     * @return array
     */
    public static function filter(iterable $items): array
    {
        return \iterator_to_array(self::lazyFilter($items));
    }

    /**
     * @param iterable $items
     * @return \Traversable
     */
    public static function lazyFilter(iterable $items): \Traversable
    {
        foreach ($items as $key => $value) {
            if (! self::match($value)) {
                yield $key => $value;
            }
        }
    }
}
