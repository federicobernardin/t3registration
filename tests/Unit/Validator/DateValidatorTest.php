<?php


namespace TYPO3\T3registration\Tests\Unit\Validator;



require_once __DIR__ . '/AbstractValidatorTestcase.php';

class DateValidatorTest extends AbstractValidatorTestcase {

    /**
     * @var \TYPO3\T3registration\Validator\DateValidator
     */
    protected $validator;

    protected $validatorClassName = 'TYPO3\\T3registration\\Validator\\DateValidator';

    /**
     * @test
     */
    public function callTwiceButErrorShouldBeOne(){
        $this->validatorOptions(array('type' => 'future','strftime' => 'd/m/Y'));
        $result = $this->validator->validate('1/3/1900');
        $result = $this->validator->validate('1/3/1900');
        $this->assertCount(1,$this->validator->getErrors());
    }

    /**
     * @test
     */
    public function OptionTypeIsNotDefined(){
        $result = $this->validator->validate('12/12/2019');
        $this->assertFalse($result);
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator_type_notdefined', 3000000005), $this->validator->getErrors()));
    }

    /**
     * @test
     */
    public function OptionStrftimeIsNotDefined(){
        $result = $this->validator->validate('12/12/2019');
        $this->assertFalse($result);
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator_strftime_notdefined', 3000000006), $this->validator->getErrors()));
    }

    /**
     * @test
     */
    public function DateIsInWrongFormat(){
        $this->validatorOptions(array('type' => 'past','strftime' => 'd/m/Y'));
        $result = $this->validator->validate('');
        $this->assertFalse($result);
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator_date_wrongformat', 3000000007), $this->validator->getErrors()));
    }

    /**
     * @test
     */
    public function OptionTypeIsInvalid(){
        $this->validatorOptions(array('type' => 'after','strftime' => 'd/m/Y'));
        $result = $this->validator->validate('12/12/2012');
        $this->assertFalse($result);
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator_date_wrongtype', 3000000010), $this->validator->getErrors()));
    }

    /**
     * @test
     */
    public function DateIsInFuture(){
        $this->validatorOptions(array('type' => 'future','strftime' => 'd/m/Y'));
        $result = $this->validator->validate('1/3/2190');
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function DateIsInPast(){
        $this->validatorOptions(array('type' => 'past','strftime' => 'd/m/Y'));
        $result = $this->validator->validate('1/3/2013');
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function DateShouldBeInRangeButRangeIsMissed(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y'));
        $result = $this->validator->validate('1/3/2013');
        $this->assertFalse($result);
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator_range_invalid', 3000000008), $this->validator->getErrors()));
    }

    /**
     * @test
     */
    public function DateShouldBeBeInRangeOnlyBefore(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y','before' => '1/4/2013'));
        $result = $this->validator->validate('1/3/2013');
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function DateShouldBeBeInRangeOnlyAfter(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y','after' => '1/1/2013'));
        $result = $this->validator->validate('1/3/2013');
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function DateShouldNotBeInRangeBeforeMinimum(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y','after' => '1/4/2013'));
        $result = $this->validator->validate('1/3/2013');
        $this->assertFalse($result);
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator_range_outofrange', 3000000009), $this->validator->getErrors()));
    }

    /**
     * @test
     */
    public function DateShouldNotBeBeInRangeAfterMaximum(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y','before' => '1/2/2013'));
        $result = $this->validator->validate('1/3/2013');
        $this->assertFalse($result);
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator_range_outofrange', 3000000009), $this->validator->getErrors()));
    }

    /**
     * @test
     */
    public function DateShouldBeInRange(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y','after' => '1/1/2013', 'before' => '1/4/2013'));
        $result = $this->validator->validate('1/3/2013');
        $this->assertTrue($result);
    }

    /**
     * @test
     */
    public function DateShouldNotBeInRange(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y','after' => '1/4/2013', 'before' => '1/6/2013'));
        $result = $this->validator->validate('1/3/2013');
        $this->assertFalse($result);
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator_range_outofrange', 3000000009), $this->validator->getErrors()));
    }

    public function isNumberWrong(){
        $this->assertFalse($this->validator->validate('asder'));
    }
}
