<?php
/**
 * This file is part of Placeholder package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Serafim\Placeholder\Tests;

use Serafim\Placeholder\Placeholder;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * Class PlaceholderTestCase
 */
class PlaceholderTestCase extends TestCase
{
    /**
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testPlaceholderIsConst(): void
    {
        $this->assertEquals(Placeholder::get(), Placeholder::get(), 'Placeholder should return same values for any invocation');
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testPlaceholderIsSameWithGlobalConst(): void
    {
        $this->assertEquals(Placeholder::get(), _, 'Placeholder class should be equals with global placeholder instance');
    }

    /**
     * @return void
     * @throws \PHPUnit\Framework\Exception
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public function testPlaceholderType(): void
    {
        $haystack = Placeholder::get();

        try {
            $this->assertIsResource($haystack, 'Placeholder should be a resource');
        } catch (ExpectationFailedException $e) {
            $this->assertIsString($haystack, 'Placeholder should be fallback to string');
        }
    }
}
