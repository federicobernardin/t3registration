<?php


namespace TYPO3\T3registration\Tests\Unit\Validator;

require_once __DIR__ . '/AbstractValidatorTestcase.php';

class StringValidatorTest extends AbstractValidatorTestcase {

    protected $validatorClassName = 'TYPO3\\T3registration\\Validator\\StringValidator';

    /**
     * @test
     */
    public function isStringCorrect(){
        $this->assertTrue($this->validator->validate('This is a string 222'));
    }

    /**
     * @test
     */
    public function isStringWrong(){
        $this->assertFalse($this->validator->validate(23));
    }
}
