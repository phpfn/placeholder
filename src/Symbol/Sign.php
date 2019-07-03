<?php
/**
 * This file is part of Placeholder package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Placeholder\Symbol;

/**
 * Class Sign
 */
final class Sign implements SignInterface
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $context;

    /**
     * Sign constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->context = $this->createContext();
    }

    /**
     * @return array|null
     */
    private function createContext(): ?array
    {
        foreach (\debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS) as $trace) {
            if (isset($trace['file']) && \strpos($trace['file'], $this->getRootDirectory()) !== 0) {
                return $trace;
            }
        }

        return null;
    }

    /**
     * @return string
     */
    private function getRootDirectory(): string
    {
        return \dirname(__FILE__, 2);
    }

    /**
     * @return array
     */
    public function getContext(): array
    {
        return $this->context;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param resource $resource
     * @param string|null $name
     * @return \Serafim\Placeholder\Symbol\Sign
     */
    public static function fromResourceStream($resource, string $name = null): self
    {
        return new static($name ?? self::anonymous($resource));
    }

    /**
     * @param resource $resource
     * @return string
     */
    private static function anonymous($resource): string
    {
        return 'symbol@anonymous#' . (int)$resource;
    }
}
