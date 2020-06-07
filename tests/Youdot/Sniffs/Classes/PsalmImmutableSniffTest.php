<?php

declare(strict_types=1);

namespace Youdot\Sniffs\Classes;

use SlevomatCodingStandard\Sniffs\TestCase;

final class PsalmImmutableSniffTest extends TestCase
{
    public function testNotErrors(): void
    {
        $report = self::checkFile(__DIR__ . '/data/validClasses.php');
        self::assertNoSniffErrorInFile($report);

    }

    public function testErrorsAndFix(): void
    {
        $report = self::checkFile(__DIR__ . '/data/wrongClasses.php');

        self::assertSame(8, $report->getErrorCount());

        self::assertSniffError($report, 3, 'MissingTag');
        self::assertSniffError($report, 5, 'MissingTag');
        self::assertSniffError($report, 10, 'MissingTag');
        self::assertSniffError($report, 13, 'MissingTag');
        self::assertSniffError($report, 16, 'MissingTag');
        self::assertSniffError($report, 19, 'MissingTag');
        self::assertSniffError($report, 21, 'MissingTag');
        self::assertSniffError($report, 23, 'MissingTag');

        self::assertAllFixedInFile($report);
    }
}
