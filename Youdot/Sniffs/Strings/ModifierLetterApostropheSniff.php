<?php

declare(strict_types=1);

namespace Youdot\Sniffs\Strings;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

/**
 * This sniffer is based on
 * https://tedclancy.wordpress.com/2015/06/03/which-unicode-character-should-represent-the-english-apostrophe-and-why-the-unicode-committee-is-very-wrong/
 */
class ModifierLetterApostropheSniff implements Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array<int|string>
     */
    public function register(): array
    {
        return [
            T_STRING,
            T_COMMENT,
            T_DOC_COMMENT_STRING,
            T_CONSTANT_ENCAPSED_STRING,
        ];
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcs_file The file being scanned.
     * @param int  $stack_ptr  The position of the current token
     *                         in the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcs_file, $stack_ptr)
    {
        $tokens = $phpcs_file->getTokens();

        $current_token_content = $tokens[$stack_ptr]['content'];

        preg_match("|[a-z]+[\\\]?[\x{0027}\x{2019}\x{FF07}][a-z]+|u", $current_token_content, $matches);

        if (!empty($matches)) {
            $phpcs_file->addFixableError($current_token_content, $stack_ptr, 'IncorrectApostrophe');

            $new_content = preg_replace(
                "|([a-z]+)[\\\]?[\x{0027}\x{2019}\x{FF07}]([a-z]+)|u",
                "$1\u{02BC}$2",
                $current_token_content
            );

            $phpcs_file->fixer->replaceToken($stack_ptr, $new_content);
        }

        return;
    }
}
