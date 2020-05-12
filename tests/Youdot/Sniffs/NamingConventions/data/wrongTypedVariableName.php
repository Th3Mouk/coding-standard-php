<?php

class Bar {
    private string $totoMachin;
    private string $totoMachinTruc;
    private string $biduleUUID;

    private function __construct(string $fooBar)
    {
        $this->totoMachin = $fooBar;
        $this->totoMachinTruc = $fooBar;
        $this->biduleUUID = $fooBar;
    }

    public static function create(string $fooBar) {
        return new self("$fooBar test");
    }
}
