<?php

$toto_test = true;

class Foo {
    public static $yolo_test;
}

class Bar extends Foo {
    private $toto_machin;
    private $toto_machin_truc;
    private $toto_truc;
    private $bidule_uuid;

    public function __construct()
    {
        $this->toto_machin = 'truc';
    }

    public function trySomething(string $test_toto) {
        return "$test_toto test";
    }
}

(new Bar())->trySomething($toto_test);
