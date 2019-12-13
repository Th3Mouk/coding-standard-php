<?php

$toto_test = true;

class Foo {
    public static $yolo_test;
}

class Bar extends Foo {
    private $toto_machin;

    public function trySomething(string $test_toto) {
        return "$test_toto";
    }
}

(new Bar())->trySomething($toto_test);
