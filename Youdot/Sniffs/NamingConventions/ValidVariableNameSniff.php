<?php

declare(strict_types=1);

namespace Youdot\Sniffs\NamingConventions;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\AbstractVariableSniff;
use PHP_CodeSniffer\Util\Tokens;

class ValidVariableNameSniff extends AbstractVariableSniff
{
    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param File $phpcs_file The file being scanned.
     * @param int  $stack_ptr  The position of the current token in the
     *                         stack passed in $tokens.
     *
     * @return void
     */
    protected function processVariable(File $phpcs_file, $stack_ptr)
    {
        $tokens   = $phpcs_file->getTokens();
        $var_name = ltrim($tokens[$stack_ptr]['content'], '$');

        // If itʼs a php reserved var, then its ok.
        // phpcs:ignore
        if (isset($this->phpReservedVars[$var_name]) === true) {
            return;
        }

        $obj_operator = $phpcs_file->findNext([T_WHITESPACE], $stack_ptr + 1, null, true);
        if ($tokens[$obj_operator]['code'] === T_OBJECT_OPERATOR) {
            // Check to see if we are using a variable from an object.
            $var = $phpcs_file->findNext([T_WHITESPACE], $obj_operator + 1, null, true);
            if (is_int($var) && $tokens[$var]['code'] === T_STRING) {
                $bracket = $phpcs_file->findNext([T_WHITESPACE], $var + 1, null, true);
                if ($tokens[$bracket]['code'] !== T_OPEN_PARENTHESIS) {
                    $obj_var_name = $tokens[$var]['content'];

                    // There is no way for us to know if the var is public or
                    // private, so we have to ignore a leading underscore if there is
                    // one and just check the main part of the variable name.
                    $original_var_name = $obj_var_name;
                    if (substr($obj_var_name, 0, 1) === '_') {
                        $obj_var_name = substr($obj_var_name, 1);
                    }

                    if ($this->isSnakeCaseFormat($obj_var_name) === false) {
                        $error = 'Variable "%s" is not in valid snake case format';
                        $data  = [$original_var_name];
                        $fix   = $phpcs_file->addFixableError($error, $var, 'NotSnakeCase', $data);

                        if ($fix) {
                            $phpcs_file->fixer->replaceToken($var, $this->toSnakeCase($original_var_name));
                        }
                    }
                }//end if
            }//end if
        }//end if

        $original_var_name = $var_name;
        // There is no way for us to know if the var is public or private,
        // so we have to ignore a leading underscore if there is one and just
        // check the main part of the variable name.
        if (substr($var_name, 0, 1) === '_') {
            $obj_operator = $phpcs_file->findPrevious(T_WHITESPACE, $stack_ptr - 1, null, true);
            if ($tokens[$obj_operator]['code'] === T_DOUBLE_COLON) {
                // The variable lives within a class, and is referenced like
                // this: MyClass::$_variable, so we donʼt know its scope.
                $in_class = true;
            } else {
                // phpcs:ignore
                $in_class = $phpcs_file->hasCondition($stack_ptr, Tokens::$ooScopeTokens);
            }

            if ($in_class === true) {
                $var_name = substr($var_name, 1);
            }
        }

        if ($this->isSnakeCaseFormat($var_name) !== false) {
            return;
        }

        $error = 'Variable "%s" is not in valid snake case format';
        $data  = [$original_var_name];
        $fix   = $phpcs_file->addFixableError($error, $stack_ptr, 'NotSnakeCase', $data);

        if (!$fix) {
            return;
        }

        $phpcs_file->fixer->replaceToken($stack_ptr, '$' . $this->toSnakeCase($var_name));
    }//end processVariable()

    /**
     * Processes class member variables.
     *
     * @param File $phpcs_file The file being scanned.
     * @param int  $stack_ptr  The position of the current token in the
     *                         stack passed in $tokens.
     *
     * @return void
     */
    protected function processMemberVar(File $phpcs_file, $stack_ptr)
    {
        $tokens = $phpcs_file->getTokens();

        $var_name     = ltrim($tokens[$stack_ptr]['content'], '$');
        $member_props = $phpcs_file->getMemberProperties($stack_ptr);
        if (empty($member_props) === true) {
            // Couldnʼt get any info about this variable, which
            // generally means it is invalid or possibly has a parse
            // error. Any errors will be reported by the core, so
            // we can ignore it.
            return;
        }

        $error_data = [$var_name];

        if (substr($var_name, 0, 1) === '_') {
            $error = 'Member variable "%s" must not contain a leading underscore';
            $fix   = $phpcs_file->addFixableError($error, $stack_ptr, 'NoUnderscore', $error_data);

            if ($fix) {
                $fixed_var_name = ltrim($var_name, '_');
                $phpcs_file->fixer->replaceToken($stack_ptr, '$' . $fixed_var_name);
            }
        }

        // Remove a potential underscore prefix.
        $var_name = ltrim($var_name, '_');

        // Convert multiple consecutive uppercase characters to lowercase
        $matches = [];
        preg_match('|[A-Z][A-Z]+|', $var_name, $matches);

        foreach ($matches as $characters) {
            $var_name = str_replace(
                $characters,
                ucfirst(strtolower($characters)),
                $var_name
            );
        }

        if ($this->isSnakeCaseFormat($var_name) !== false) {
            return;
        }

        $error = 'Member variable "%s" is not in valid snake case format';
        $fix   = $phpcs_file->addFixableError($error, $stack_ptr, 'MemberNotSnakeCase', $error_data);

        if (!$fix) {
            return;
        }

        $phpcs_file->fixer->replaceToken($stack_ptr, '$' . $this->toSnakeCase($var_name));
    }//end processMemberVar()

    /**
     * Processes the variable found within a double quoted string.
     *
     * @param File $phpcs_file The file being scanned.
     * @param int  $stack_ptr  The position of the double quoted
     *                         string.
     *
     * @return void
     */
    protected function processVariableInString(File $phpcs_file, $stack_ptr)
    {
        $tokens = $phpcs_file->getTokens();

        if (preg_match_all('|[^\\\]\${?([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)|', $tokens[$stack_ptr]['content'], $matches) === 0) {
            return;
        }

        foreach ($matches[1] as $var_name) {
            // If itʼs a php reserved var, then its ok.
            // phpcs:ignore
            if (isset($this->phpReservedVars[$var_name]) === true) {
                continue;
            }

            if ($this->isSnakeCaseFormat($var_name) !== false) {
                continue;
            }

            $error = 'Variable "%s" is not in valid snake case format';
            $data  = [$var_name];
            $fix   = $phpcs_file->addFixableError($error, $stack_ptr, 'StringNotSnakeCase', $data);

            if (!$fix) {
                continue;
            }

            $complete_string = preg_replace_callback(
                "/\\\$$var_name/",
                fn($matches) => $this->toSnakeCase($matches[0]),
                $tokens[$stack_ptr]['content']
            );
            $phpcs_file->fixer->replaceToken($stack_ptr, $complete_string);
        }
    }//end processVariableInString()

    private function isSnakeCaseFormat(string $string): bool
    {
        // If there are space in the name, it canʼt be valid.
        if (strpos($string, ' ') !== false) {
            return false;
        }

        $valid_name = true;
        $name_bits  = explode('_', $string);

        foreach ($name_bits as $bit) {
            if (empty($bit) || $bit !== strtolower($bit)) {
                $valid_name = false;
                break;
            }
        }

        return $valid_name;
    }

    private function toSnakeCase(string $str): string
    {
        return preg_replace_callback(
            '/([A-Z])/',
            static fn($c) => '_' . strtolower($c[1]),
            $str
        );
    }
}//end class
