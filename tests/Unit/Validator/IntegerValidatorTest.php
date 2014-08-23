<?php


namespace TYPO3\T3registration\Tests\Unit\Validator;



require_once __DIR__ . '/AbstractValidatorTestcase.php';

class IntegerValidatorTest extends AbstractValidatorTestcase {

    protected $validatorClassName = 'TYPO3\\T3registration\\Validator\\IntegerValidator';

    /**
     * @test
     */
    public function callTwiceButErrorShouldBeOne(){
        $this->assertFalse($this->validator->validate('asder'));
        $this->assertFalse($this->validator->validate('asder'));
        $this->assertCount(1,$this->validator->getErrors());
    }


    /**
     * @test
     */
    public function isNumberCorrect(){
        $this->assertTrue($this->validator->validate('222736'));
    }

    /**
     * @test
     */
    public function isNumberWrong(){
        $this->assertFalse($this->validator->validate('asder'));

    }
}
