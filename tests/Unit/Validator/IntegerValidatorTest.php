<?php


namespace Tests\Unit\Validator;


use TYPO3\CMS\Core\Utility\GeneralUtility;

class IntegerValidatorTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \TYPO3\T3registration\Validator\IntegerValidator
     */
    private $fixture;

    public function setUp() {
        $this->fixture = GeneralUtility::makeInstance('TYPO3\\T3registration\\Validator\\IntegerValidator');
    }

    /**
     * @test
     */
    public function isNumberCorrect(){
        $this->assertFalse($this->fixture->validate('222736')->hasErrors());
    }

    /**
     * @test
     */
    public function isNumberWrong(){
        $this->assertTrue($this->fixture->validate('asder')->hasErrors());

    }
}
