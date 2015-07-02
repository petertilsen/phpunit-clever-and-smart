<?php

namespace PHPUnit\Runner\CleverAndSmart;

use PHPUnit\Runner\CleverAndSmart\Storage\StorageInterface;
use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_TestSuite as TestSuite;

/**
 * Class Skipper
 * @package PHPUnit\Runner\CleverAndSmart
 */
class Skipper implements ModeInterface
{
    const SKIPPED_TEST_MESSAGE = 'This test has been skipped due to PHPUnit\Runner\CleverAndSmart configuration';

    /** @var TestSuite */
    private $suite;

    /** @var StorageInterface */
    private $storage;

    /** @var array */
    private $errors = array();

    /** @var string */
    private $mergeMode = self::MERGE_MODE_ALL;

    /**
     * @param TestSuite $suite
     * @param StorageInterface $storage
     * @param array $errors
     */
    public function __construct(TestSuite $suite, StorageInterface $storage, array $errors)
    {
        $this->suite = $suite;
        $this->storage = $storage;
        $this->errors = $errors;
        $this->initMergeModes();
    }

    /**
     * @param TestCase $test
     */
    public function skip(TestCase $test)
    {
        $this->markTestsSkipped($test, $this->errors);
    }

    /**
     * @param $mergeMode
     */
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
            self::MERGE_MODE_ERROR_AND_SKIP
        );
    }

    /**
     * Mark tests skipped
     *
     * @param TestCase $test
     * @param array $errors
     * @todo Mark tests skipped, unfortunately tests are skipped by invoking exceptions from the tests.
     */
    private function markTestsSkipped(TestCase $test, array $errors)
    {

        list($identifier, $className, $testName) = $this->storage->getTestIdentifiers($test);

        if ($this->mergeMode === self::MERGE_MODE_ERROR_AND_SKIP
            && count($errors) > 0
            && count($this->suite->tests()) > count($errors)
            && $this->storage->getRecording(array(StorageInterface::STATUS_PASSED), $identifier)) {

            // mark test skipped. quite hard, cause skipped tests are handled through exceptions
        }
    }
}
