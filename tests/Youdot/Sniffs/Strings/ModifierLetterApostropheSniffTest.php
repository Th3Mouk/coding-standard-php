<?php

declare(strict_types=1);

namespace Youdot\Sniffs\Strings;

use SlevomatCodingStandard\Sniffs\TestCase;

final class ModifierLetterApostropheSniffTest extends TestCase
{
    public function testNotErrors(): void
    {
        $report = self::checkFile(__DIR__ . '/data/validModifierLetterApostrophe.php');
        self::assertNoSniffErrorInFile($report);

    }

    public function testBlablaErrors(): void
    {
        $report = self::checkFile(__DIR__ . '/data/wrongModifierLetterApostrophe.php');

        self::assertSame(5, $report->getErrorCount());

        self::assertSniffError($report, 6, 'IncorrectApostrophe');
        self::assertSniffError($report, 9, 'IncorrectApostrophe');
        self::assertSniffError($report, 13, 'IncorrectApostrophe');
        self::assertSniffError($report, 17, 'IncorrectApostrophe');
        self::assertSniffError($report, 22, 'IncorrectApostrophe');

        self::assertAllFixedInFile($report);
    }
}
