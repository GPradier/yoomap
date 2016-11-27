<?php

use Symfony\Component\ClassLoader\Psr4ClassLoader;
use Sudoku\SudokuGame;

require __DIR__.'/src/Psr4ClassLoader.php';
$loader = new Psr4ClassLoader();
$loader->addPrefix('Sudoku', __DIR__ .'/src');
$loader->register();

$puzzle = [
    [0,7,6,  0,1,0,  0,4,3],
    [0,0,0,  7,0,2,  9,0,0],
    [0,9,0,  0,0,6,  0,0,0],

    [0,0,0,  0,6,3,  2,0,4],
    [4,6,0,  0,0,0,  0,1,9],
    [1,0,5,  4,2,0,  0,0,0],

    [0,0,0,  2,0,0,  0,9,0],
    [0,0,4,  8,0,7,  0,0,1],
    [9,1,0,  0,5,0,  7,2,0],
];

$sudoku = new SudokuGame();
$cells = $sudoku->buildNewGame();
$cells = $sudoku->setPuzzle($cells, $puzzle);

$sudoku->display($cells);
$sudoku->display($sudoku->solve($cells));