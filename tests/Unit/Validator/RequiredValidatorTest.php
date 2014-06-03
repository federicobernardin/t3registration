<?php


namespace TYPO3\T3registration\Tests\Unit\Validator;

require_once __DIR__ . '/AbstractValidatorTestcase.php';

class RequiredValidatorTest extends AbstractValidatorTestcase {

    protected $validatorClassName = 'TYPO3\\T3registration\\Validator\\RequiredValidator';


    /**
     * @test
     */
    public function isNumberCorrect(){
        $this->assertTrue($this->validator->validate('value'));
    }

    /**
     * @test
     */
    public function isNumberWrong(){
        $this->assertFalse($this->validator->validate(''));

    }
}
