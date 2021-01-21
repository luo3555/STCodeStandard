<?php
/**
 * Throws errors if there's no blank line before return statements. Symfony
 * coding standard specifies: "Add a blank line before return statements,
 * unless the return is alone inside a statement-group (like an if statement);"
 *
 * @author Luo <luoting@shinetechsoftware.com>
 */
namespace STCodeStandard\Sniffs\Formatting;

use PHP_CodeSniffer\Files\File;
use PHP_CodeSniffer\Sniffs\Sniff;

class BlankLineBeforeReturnSniff implements Sniff
{
    /**
     * String representation of warning.
     *
     * @var string
     */
    protected $errorMessage = 'Missing blank line before return statement';

    /**
     * Warning violation code.
     *
     * @var string
     */
    protected $errorCode = 'BlankLineBeforeReturn';

    /**
     * A list of tokenizers this sniff supports.
     *
     * @var array
     */
    public $supportedTokenizers = array(
        'PHP',
        'JS',
    );

    /**
     * Returns an array of tokens this test wants to listen for.
     *
     * @return array
     */
    public function register()
    {
        return array(T_RETURN);
    }

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param PHP_CodeSniffer_File $phpcsFile All the tokens found in the document.
     * @param int                  $stackPtr  The position of the current token in
     *                                        the stack passed in $tokens.
     *
     * @return void
     */
    public function process(File $phpcsFile, $stackPtr)
    {
        $tokens          = $phpcsFile->getTokens();
        $current         = $stackPtr;
        $previousLine    = $tokens[$stackPtr]['line'] - 1;
        $prevLineTokens  = array();

        while ($tokens[$current]['line'] >= $previousLine) {
            if ($tokens[$current]['line'] == $previousLine &&
                $tokens[$current]['type'] != 'T_WHITESPACE' &&
                $tokens[$current]['type'] != 'T_COMMENT'
            ) {
                $prevLineTokens[] = $tokens[$current]['type'];
            }
            $current--;
        }

        if (isset($prevLineTokens[0])
            && $prevLineTokens[0] == 'T_OPEN_CURLY_BRACKET'
        ) {
            return;
        } else if(count($prevLineTokens) > 0) {
            $fix = $phpcsFile->addFixableError($this->errorMessage, $stackPtr, $this->errorCode, $data);
            if ($fix === true) {
                $phpcsFile->fixer->addNewlineBefore($stackPtr-1);
            }
        }

        return;
    }
}