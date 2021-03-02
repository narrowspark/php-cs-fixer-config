<?php

declare(strict_types=1);

/**
 * Copyright (c) 2015-2021 Daniel Bannert
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/narrowspark/php-cs-fixer-config
 */

namespace Narrowspark\CS\Config\Tests\AutoReview;

use Narrowspark\CS\Config\Config;
use PHPUnit\Framework\TestCase;
use const JSON_THROW_ON_ERROR;
use function explode;
use function file_get_contents;
use function json_decode;
use function sprintf;

/**
 * @internal
 *
 * @coversNothing
 *
 * @group auto-review
 * @group covers-nothing
 *
 * @medium
 */
final class ComposerTest extends TestCase
{
    public function testBranchAlias(): void
    {
        /** @var array<string, mixed> $composerJson */
        $composerJson = json_decode(
            (string) file_get_contents(__DIR__ . '/../../composer.json'),
            true,
            512,
            JSON_THROW_ON_ERROR
        );

        if (! isset($composerJson['extra']['branch-alias'])) {
            /** @psalm-suppress InternalMethod */
            $this->addToAssertionCount(1); // composer.json doesn't contain branch alias, all good!

            return;
        }

        self::assertSame(
            [
                'dev-master' => $this->convertAppVersionToAliasedVersion(Config::VERSION),
            ],
            $composerJson['extra']['branch-alias']
        );
    }

    private function convertAppVersionToAliasedVersion(string $version): string
    {
        $parts = explode('.', $version, 3);

        return sprintf('%d.%d-dev', $parts[0], $parts[1]);
    }
}
