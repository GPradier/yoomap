<?php

namespace Sudoku;

use SplObserver;
use SplSubject;

/**
 * Class SudokuCell
 * @package Sudoku
 */
class SudokuCell extends Observable implements SplObserver
{
    const IN_ROW_OBS = 1;
    const IN_COLUMN_OBS = 2;
    const IN_SQUARE_OBS = 3;
    private $row;
    private $column;
    private $isSolved;
    private $possibleValues;

    /**
     * SudokuCell constructor.
     * @param $row
     * @param $column
     */
    public function __construct($row, $column)
    {
        $this->row = $row;
        $this->column = $column;
        $this->isSolved = false;
        $this->possibleValues = range(0, 9);
        unset($this->possibleValues[0]);// key = value de 1 à 9
        parent::__construct();
    }

    /**
     * @return int
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @return int
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param SudokuCell $cell
     * @return bool
     */
    public function getRelation(SudokuCell $cell)
    {
        $isIdentical = ($cell->getRow() == $this->row) && ($cell->getColumn() == $this->column);
        $isInRow = ($cell->getRow() == $this->row);
        $isInColumn = ($cell->getColumn() == $this->column);

        $isInSquare = ((int) (($cell->getRow()-1) / 3) == (int) (($this->row-1) / 3))
            && ((int) (($cell->getColumn()-1) / 3) == (int) (($this->column-1) / 3));


        if (!$isIdentical && ($isInRow || $isInColumn || $isInSquare)) {
            return ($isInRow) ? self::IN_ROW_OBS : (($isInColumn) ? self::IN_COLUMN_OBS : self::IN_SQUARE_OBS);
        }

        return false;
    }

    /**
     * Receive update from subject
     * @link http://php.net/manual/en/splobserver.update.php
     * @param SplSubject $subject <p>
     * The <b>SplSubject</b> notifying the observer of an update.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function update(SplSubject $subject)
    {
        if ($this->isSolved) {
            return;
        }

        /** @var SudokuCell $subject */
        unset($this->possibleValues[$subject->getValue()]);
        if ($this->getValue() !== 0) {// can resolve him too, cascade notify
            $this->isSolved = true;
            $this->notify();
        }
    }

    public function setKnownValue($value)
    {
        if ($value === 0) {
            return;
        }

        $this->possibleValues = [$value => $value];
        $this->isSolved = true;
        $this->notify();
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return (count($this->possibleValues) === 1) ? current($this->possibleValues) : 0;
    }

    public function getPossibleValues()
    {
        return $this->possibleValues;
    }

    public function guessValue()
    {
        foreach ($this->possibleValues as $value) {
            $isValid = [ // possible values can be in either column, row or square
                self::IN_ROW_OBS    => true,
                self::IN_COLUMN_OBS => true,
                self::IN_SQUARE_OBS => true,
            ];

            foreach ($this->observers as $observer) {
                if (in_array($value, $observer->getPossibleValues())) {
                    $isValid[$observer->getRelation($this)] = false;
                }
            }

            if ($isValid[self::IN_ROW_OBS] || $isValid[self::IN_COLUMN_OBS] || $isValid[self::IN_SQUARE_OBS]) {
                $this->setKnownValue($value);
                break;
            }
        }
    }

    public function isSolved()
    {
        return $this->isSolved;
    }
}