<?php


namespace Tests\Unit\Validator;



require_once __DIR__ . '/AbstractValidatorTestcase.php';

class IntegerValidatorTest extends AbstractValidatorTestcase {

    protected $validatorClassName = 'TYPO3\\T3registration\\Validator\\IntegerValidator';


    /**
     * @test
     */
    public function isNumberCorrect(){
        $this->assertFalse($this->validator->validate('222736')->hasErrors());
    }

    /**
     * @test
     */
    public function isNumberWrong(){
        $this->assertTrue($this->validator->validate('asder')->hasErrors());

    }
}
