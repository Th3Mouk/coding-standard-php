<?php

declare(strict_types=1);

namespace Youdot\Sniffs\NamingConventions;

use SlevomatCodingStandard\Sniffs\TestCase;

final class ValidVariableNameSniffTest extends TestCase
{
    public function testNoErrors(): void
    {
        $report = self::checkFile(__DIR__ . '/data/validVariableName.php');
        self::assertNoSniffErrorInFile($report);

    }

    public function testErrors(): void
    {
        $report = self::checkFile(__DIR__ . '/data/wrongVariableName.php');

        self::assertSame(10, $report->getErrorCount());

        self::assertSniffError($report, 3, 'NotSnakeCase', "Variable \"totoTest\" is not in valid snake case format");
        self::assertSniffError($report, 6, 'MemberNotSnakeCase', "Member variable \"yoloTest\" is not in valid snake case format");
        self::assertSniffError($report, 10, 'MemberNotSnakeCase', "Member variable \"totoMachin\" is not in valid snake case format");
        self::assertSniffError($report, 11, 'MemberNotSnakeCase', "Member variable \"totoMachinTruc\" is not in valid snake case format");
        self::assertSniffError($report, 12, 'NoUnderscore', "Member variable \"_toto_truc\" must not contain a leading underscore");
        self::assertSniffError($report, 13, 'MemberNotSnakeCase', "Member variable \"biduleUUID\" is not in valid snake case format");
        self::assertSniffError($report, 17, 'NotSnakeCase', "Variable \"totoMachin\" is not in valid snake case format");
        self::assertSniffError($report, 20, 'NotSnakeCase', "Variable \"testToto\" is not in valid snake case format");
        self::assertSniffError($report, 21, 'StringNotSnakeCase', "Variable \"testToto\" is not in valid snake case format");
        self::assertSniffError($report, 25, 'NotSnakeCase', "Variable \"totoTest\" is not in valid snake case format");

        self::assertAllFixedInFile($report);
    }

    public function testErrorsOnTypedVariables(): void
    {
        $report = self::checkFile(__DIR__ . '/data/wrongTypedVariableName.php');

        self::assertSame(12, $report->getErrorCount());

        self::assertSniffError($report, 4, 'MemberNotSnakeCase', "Member variable \"totoMachin\" is not in valid snake case format");
        self::assertSniffError($report, 5, 'MemberNotSnakeCase', "Member variable \"totoMachinTruc\" is not in valid snake case format");
        self::assertSniffError($report, 6, 'MemberNotSnakeCase', "Member variable \"biduleUUID\" is not in valid snake case format");
        self::assertSniffError($report, 8, 'NotSnakeCase', "Variable \"fooBar\" is not in valid snake case format");
        self::assertSniffError($report, 10, 'NotSnakeCase', "Variable \"totoMachin\" is not in valid snake case format");
        self::assertSniffError($report, 10, 'NotSnakeCase', "Variable \"fooBar\" is not in valid snake case format");
        self::assertSniffError($report, 11, 'NotSnakeCase', "Variable \"totoMachinTruc\" is not in valid snake case format");
        self::assertSniffError($report, 11, 'NotSnakeCase', "Variable \"fooBar\" is not in valid snake case format");
        self::assertSniffError($report, 12, 'NotSnakeCase', "Variable \"biduleUUID\" is not in valid snake case format");
        self::assertSniffError($report, 12, 'NotSnakeCase', "Variable \"fooBar\" is not in valid snake case format");
        self::assertSniffError($report, 15, 'NotSnakeCase', "Variable \"fooBar\" is not in valid snake case format");
        self::assertSniffError($report, 16, 'StringNotSnakeCase', "Variable \"fooBar\" is not in valid snake case format");

        self::assertAllFixedInFile($report);
    }
}
