<?php

/**
 * Class Toto
 *
 * With a bad character don't
 */
class Toto {
    // we'll
    private $toto;

    /**
     * Must be fixed doesn't
     */
    public function toto(): string
    {
        return 'can\'t';
    }

    public function titi(): string
    {
        return 'can’t';
    }
}
