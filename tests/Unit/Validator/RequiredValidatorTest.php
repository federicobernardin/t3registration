<?php


namespace Tests\Unit\Validator;

require_once __DIR__ . '/AbstractValidatorTestcase.php';

class RequiredValidatorTest extends AbstractValidatorTestcase {

    protected $validatorClassName = 'TYPO3\\T3registration\\Validator\\RequiredValidator';


    /**
     * @test
     */
    public function isNumberCorrect(){
        $this->assertFalse($this->validator->validate('value')->hasErrors());
    }

    /**
     * @test
     */
    public function isNumberWrong(){
        $this->assertTrue($this->validator->validate('')->hasErrors());

    }
}
