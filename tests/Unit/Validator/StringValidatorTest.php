<?php


namespace Tests\Unit\Validator;


use TYPO3\CMS\Core\Utility\GeneralUtility;

class StringValidatorTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {

    /**
     * @var \TYPO3\T3registration\Validator\StringValidator
     */
    private $fixture;

    public function setUp() {
        $this->fixture = GeneralUtility::makeInstance('TYPO3\\T3registration\\Validator\\StringValidator');
    }

    /**
     * @test
     */
    public function isStringCorrect(){
        $this->assertFalse($this->fixture->validate('This is a string 222')->hasErrors());
    }

    /**
     * @test
     */
    public function isStringWrong(){
        $this->assertTrue($this->fixture->validate(23)->hasErrors());
    }
}
