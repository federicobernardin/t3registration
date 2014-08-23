<?php


namespace TYPO3\T3registration\Tests\Unit\Validator;

require_once __DIR__ . '/AbstractValidatorTestcase.php';

class RequiredValidatorTest extends AbstractValidatorTestcase {

    protected $validatorClassName = 'TYPO3\\T3registration\\Validator\\RequiredValidator';

    /**
     * @test
     */
    public function callTwiceButErrorShouldBeOne(){
        $this->validator->validate('');
        $this->validator->validate('');
        $this->assertCount(1,$this->validator->getErrors());
    }



    /**
     * @test
     */
    public function valueIsValid(){
        $this->assertTrue($this->validator->validate('value'));
    }

    /**
     * @test
     */
    public function valueIsWrong(){
        $this->assertFalse($this->validator->validate(''));

    }
}
