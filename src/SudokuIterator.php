<?php

namespace Sudoku;

/**
 * Class SudokuIterator
 * @package Sudoku
 */
class SudokuIterator {

    /**
     * @param callable $callable
     */
    public function __invoke(callable $callable)
    {
        for ($i = 1; $i < 10; $i++) {
            for ($j = 1; $j < 10; $j++) {
                $callable($i, $j);
            }
        }
    }
}
