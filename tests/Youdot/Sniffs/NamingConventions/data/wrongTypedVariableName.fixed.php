<?php

class Bar {
    private string $toto_machin;
    private string $toto_machin_truc;
    private string $bidule_uuid;

    private function __construct(string $foo_bar)
    {
        $this->toto_machin = $foo_bar;
        $this->toto_machin_truc = $foo_bar;
        $this->bidule_uuid = $foo_bar;
    }

    public static function create(string $foo_bar) {
        return new self("$foo_bar test");
    }
}
