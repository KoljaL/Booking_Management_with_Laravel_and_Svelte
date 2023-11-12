<?php

use Illuminate\Support\Debug\HtmlDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

function ddd($variable) {
  // $cloner = new VarCloner();

  // $dumper = 'cli' === PHP_SAPI ? new CliDumper() : new HtmlDumper();
  // $dumper->dump($cloner->cloneVar($variable));
  $json = json_encode($variable, JSON_PRETTY_PRINT);
  echo $json;

  die(1);
}