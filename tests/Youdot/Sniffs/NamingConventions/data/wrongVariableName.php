<?php

$totoTest = true;

class Foo {
    public static $yoloTest;
}

class Bar extends Foo {
    private $totoMachin;
    private $totoMachinTruc;
    private $_toto_truc;
    private $biduleUUID;

    public function __construct()
    {
        $this->totoMachin = 'truc';
    }

    public function trySomething(string $testToto) {
        return "$testToto test";
    }
}

(new Bar())->trySomething($totoTest);
