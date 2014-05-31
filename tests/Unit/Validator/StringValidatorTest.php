<?php


namespace TYPO3\T3registration\Tests\Unit\Validator;

require_once __DIR__ . '/AbstractValidatorTestcase.php';

class StringValidatorTest extends AbstractValidatorTestcase {

    protected $validatorClassName = 'TYPO3\\T3registration\\Validator\\StringValidator';

    /**
     * @test
     */
    public function isStringCorrect(){
        $this->assertFalse($this->validator->validate('This is a string 222')->hasErrors());
    }

    /**
     * @test
     */
    public function isStringWrong(){
        $this->assertTrue($this->validator->validate(23)->hasErrors());
    }
}
