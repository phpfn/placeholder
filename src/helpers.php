<?php
/**
 * This file is part of Placeholder package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace {

    use Serafim\Placeholder\Placeholder;

    define('PLACEHOLDER', $placeholder = Placeholder::get());

    const _ = PLACEHOLDER;
}
