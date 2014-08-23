<?php


namespace TYPO3\T3registration\Tests\Unit\Validator;


require_once __DIR__ . '/AbstractValidatorTestcase.php';

class RegexpValidatorTest extends AbstractValidatorTestcase {

    protected $validatorClassName = 'TYPO3\\T3registration\\Validator\\RegexpValidator';

    /**
     * @test
     */
    public function callTwiceButErrorShouldBeOne(){
        $this->validatorOptions(array('regularExpression' => '/^simple[0-9]expression$/'));
        $this->assertFalse($this->validator->validate('some subject that will not match'));
        $this->assertFalse($this->validator->validate('some subject that will not match'));
        $this->assertCount(1,$this->validator->getErrors());
    }

    /**
     * @test
     */
    public function regularExpressionValidatorMatchesABasicExpressionCorrectly(){
        $this->validatorOptions(array('regularExpression' => '/^simple[0-9]expression$/'));
        $this->assertTrue($this->validator->validate('simple1expression'));
        $this->assertFalse($this->validator->validate('simple1expressions'));
    }

    /**$this->validator
     * @test
     */
    public function regularExpressionValidatorCreatesTheCorrectErrorIfTheExpressionDidNotMatch(){
        $this->validatorOptions(array('regularExpression' => '/^simple[0-9]expression$/'));
        $this->assertFalse($this->validator->validate('some subject that will not match'));

    }
}
