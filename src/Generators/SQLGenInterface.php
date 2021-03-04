<?php namespace DBDiff\Generators;


interface SQLGenInterface {
    public function getUp();
    public function getDown();
}
