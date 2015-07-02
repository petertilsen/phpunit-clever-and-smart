<?php
namespace PHPUnit\Runner\CleverAndSmart;

use ArrayIterator;
use IteratorAggregate;
use SplPriorityQueue;
use SplQueue;

class SegmentedQueue implements IteratorAggregate, ModeInterface
{
    /** @var SplQueue */
    public $unknown;

    /** @var SplQueue */
    public $errors;

    /** @var SplPriorityQueue */
    public $timed;

    /** @var string */
    private $mergeMode = self::MERGE_MODE_ALL;

    /** @var array */
    private $mergeModes = array();

    public function __construct(array $values = array())
    {
        $this->unknown = new SplQueue();
        array_map(array($this->unknown, 'push'), $values);
        $this->errors = new SplQueue();
        $this->timed = new PriorityQueue();
        $this->initMergeModes();
    }

    public function getIterator()
    {
        return new ArrayIterator(
            array_values(
                array_filter(
                    $this->merge(
                        iterator_to_array($this->errors),
                        iterator_to_array($this->unknown),
                        iterator_to_array($this->timed))
                )
            )
        );
    }

    public function setMergeMode($mergeMode)
    {
        if (in_array($mergeMode, $this->mergeModes) === false) {
            $this->mergeMode = self::MERGE_MODE_ALL;
            return;
        }
        $this->mergeMode = $mergeMode;
    }


    private function initMergeModes()
    {
        $this->mergeModes = array(
            self::MERGE_MODE_ALL,
            self::MERGE_MODE_ERROR_ONLY
        );
    }

    private function merge(array $errors = array(), array $unknown = array(), array $timed = array())
    {
        switch ($this->mergeMode) {
            case self::MERGE_MODE_ERROR_ONLY:
                $unknown = ((count($errors) !== 0) ? array() : $unknown);
                break;
        }

        return array_merge($errors, $unknown, $timed);
    }
}
