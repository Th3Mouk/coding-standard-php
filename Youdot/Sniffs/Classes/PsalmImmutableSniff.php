<?php

declare(strict_types=1);

namespace Youdot\Sniffs\Classes;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;
use SlevomatCodingStandard\Helpers\AnnotationHelper;
use SlevomatCodingStandard\Helpers\DocCommentHelper;
use SlevomatCodingStandard\Helpers\TokenHelper;

/**
 * @psalm-immutable
 */
class PsalmImmutableSniff implements Sniff
{
    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array<int|string>
     */
    public function register(): array
    {
        return [T_CLASS, T_INTERFACE];
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

        $first_token_of_line = TokenHelper::findFirstTokenOnLine($phpcs_file, $stack_ptr);

        if (!DocCommentHelper::hasDocComment($phpcs_file, $stack_ptr)) {
            $phpcs_file->addFixableError(
                'The class has no PhpDoc block, and must have one with @psalm-immutable tag',
                $stack_ptr,
                'MissingTag'
            );

            $phpcs_file->fixer->addContent(
                $first_token_of_line - 1,
                "/**\n * @psalm-immutable\n */\n"
            );

            return;
        }

        $annotations = AnnotationHelper::getAnnotationsByName($phpcs_file, $stack_ptr, '@psalm-immutable');
        if (!empty($annotations)) {
            return;
        }

        $doc_block_start_pointer = TokenHelper::findPrevious(
            $phpcs_file,
            T_DOC_COMMENT_OPEN_TAG,
            $first_token_of_line
        );

        $phpcs_file->addFixableError(
            'The PhpDoc block of the class does not contains @psalm-immutable tag',
            $doc_block_start_pointer,
            'MissingTag'
        );

        // Single line comment must be exploded in multiple
        if ($tokens[$stack_ptr]['line'] - 1 === $tokens[$doc_block_start_pointer]['line']) {
            $phpcs_file->fixer->beginChangeset();
            $phpcs_file->fixer->addContent($doc_block_start_pointer, "\n * @psalm-immutable\n *\n *");

            $phpdoc_content_pointer = TokenHelper::findNext(
                $phpcs_file,
                [T_DOC_COMMENT_STRING, T_DOC_COMMENT_TAG],
                $doc_block_start_pointer,
                $stack_ptr
            );

            if ($tokens[$phpdoc_content_pointer]['code'] === T_DOC_COMMENT_STRING) {
                $content = trim($tokens[$phpdoc_content_pointer]['content']);
                $phpcs_file->fixer->replaceToken($phpdoc_content_pointer, "$content\n ");
                $phpcs_file->fixer->endChangeset();

                return;
            }

            if ($tokens[$phpdoc_content_pointer + 2]['code'] === T_DOC_COMMENT_STRING) {
                $content = trim($tokens[$phpdoc_content_pointer + 2]['content']);
                $phpcs_file->fixer->replaceToken($phpdoc_content_pointer + 2, "$content\n ");
                $phpcs_file->fixer->endChangeset();

                return;
            }

            $phpcs_file->fixer->addNewline($phpdoc_content_pointer);
            $phpcs_file->fixer->endChangeset();

            return;
        }

        $phpcs_file->fixer->addContent($doc_block_start_pointer, "\n * @psalm-immutable\n *");
        $phpcs_file->fixer->endChangeset();

        return;
    }
}
