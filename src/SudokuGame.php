<?php

namespace Sudoku;

/**
 * Class SudokuGame
 * @package Sudoku
 */
class SudokuGame {

    /**
     * @param $cells SudokuCell[]
     * @return SudokuCell[]
     */
    public function solve($cells)
    {
        foreach ($cells as $cell) {
            $cell->guessValue();
        }

        return $cells;
    }

    /**
     * @param $cells SudokuCell[]
     * @return SudokuCell[]
     */
    public function solveAlways($cells)
    {
        $isSolved = false;
        while ($isSolved === false) {
            $isSolved = true;
            foreach ($cells as $cell) {
                if ($cell->isSolved() === false) {
                    $isSolved = false;
                }

                $cell->guessValue();
            }
        }

        return $cells;
    }

    /**
     * @param array $dataPuzzle
     */
    function display(array $dataPuzzle) {
        $i=0;
        $j=0;
        foreach ($dataPuzzle as $cell) {
            if ($cell->getValue() === 0) {
                $display = '{'. implode(', ', $cell->getPossibleValues()) .'}';
            } else {
                $display = $cell->getValue() . ' ';
            }

            echo str_pad($display, 20, ' ');
            $i++;
            if ($i % 3 == 0) {
                echo ' | ';
            }
            if ($i == 9) {
                $j++;
                if ($j % 3 == 0) {
                    echo PHP_EOL;
                    echo str_repeat('-', 188);
                }
                $i = 0;
                echo PHP_EOL;
            }
        }
        echo PHP_EOL;
    }

    /**
     * Build empty game
     * @return SudokuCell[]
     */
    public function buildNewGame()
    {
        /** @var SudokuCell[] $cells */
        $cells = [];
        $builderCells = function($i, $j) use (&$cells) { $cells[] = new SudokuCell($i, $j); };
        $puzzleIterator = new SudokuIterator();
        $puzzleIterator($builderCells);

        foreach ($cells as $cellToObserve) {
            foreach ($cells as $cell) {
                if ($cellToObserve->getRelation($cell)) {
                    $cellToObserve->attach($cell);
                }

            }
        }

        return $cells;
    }

    /**
     * @param SudokuCell[] $cells
     * @param array $puzzle
     * @return SudokuCell[]
     */
    public function setPuzzle($cells, $puzzle)
    {
        foreach ($cells as $cell) {
            $cell->setKnownValue($puzzle[$cell->getRow() - 1][$cell->getColumn() - 1]);
        }

        return $cells;
    }
}