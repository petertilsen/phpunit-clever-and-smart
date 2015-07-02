<?php
namespace PHPUnit\Runner\CleverAndSmart;


/**
 * Interface ModeInterface
 * @package PHPUnit\Runner\CleverAndSmart
 */
interface ModeInterface
{
    const MERGE_MODE_ALL = 'all';
    const MERGE_MODE_ERROR_ONLY = 'error_only';
    const MERGE_MODE_ERROR_AND_SKIP = 'error_and_skip';


    /**
     * @param $mergeMode
     *
     * @return mixed
     */
    public function setMergeMode($mergeMode);
}
