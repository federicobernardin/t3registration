<?php


namespace TYPO3\T3registration\Tests\Unit\Validator;



require_once __DIR__ . '/AbstractValidatorTestcase.php';

class DateValidatorTest extends AbstractValidatorTestcase {

    protected $validatorClassName = 'TYPO3\\T3registration\\Validator\\DateValidator';

    /**
     * @test
     */
    public function OptionTypeIsNotDefined(){
        /** @var \TYPO3\CMS\Extbase\Error\Result $result */
        $result = $this->validator->validate('12/12/2019');
        $this->assertTrue($result->hasErrors());
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator.date.notdefined', 3000000005), $result->getErrors()));
    }

    /**
     * @test
     */
    public function OptionStrftimeIsNotDefined(){
        /** @var \TYPO3\CMS\Extbase\Error\Result $result */
        $result = $this->validator->validate('12/12/2019');
        $this->assertTrue($result->hasErrors());
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator.strftime.notdefined', 3000000006), $result->getErrors()));
    }

    /**
     * @test
     */
    public function DateIsInWrongFormat(){
        $this->validatorOptions(array('type' => 'past','strftime' => 'd/m/Y'));
        /** @var \TYPO3\CMS\Extbase\Error\Result $result */
        $result = $this->validator->validate('');
        $this->assertTrue($result->hasErrors());
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator.date.wrongformat', 3000000007), $result->getErrors()));
    }

    /**
     * @test
     */
    public function OptionTypeIsInvalid(){
        $this->validatorOptions(array('type' => 'after','strftime' => 'd/m/Y'));
        /** @var \TYPO3\CMS\Extbase\Error\Result $result */
        $result = $this->validator->validate('12/12/2012');
        $this->assertTrue($result->hasErrors());
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator.date.wrongtype', 3000000010), $result->getErrors()));
    }

    /**
     * @test
     */
    public function DateIsInFuture(){
        $this->validatorOptions(array('type' => 'future','strftime' => 'd/m/Y'));
        /** @var \TYPO3\CMS\Extbase\Error\Result $result */
        $result = $this->validator->validate('1/3/2190');
        $this->assertFalse($result->hasErrors());
    }

    /**
     * @test
     */
    public function DateIsInPast(){
        $this->validatorOptions(array('type' => 'past','strftime' => 'd/m/Y'));
        /** @var \TYPO3\CMS\Extbase\Error\Result $result */
        $result = $this->validator->validate('1/3/2013');
        $this->assertFalse($result->hasErrors());
    }

    /**
     * @test
     */
    public function DateShouldBeInRangeButRangeIsMissed(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y'));
        /** @var \TYPO3\CMS\Extbase\Error\Result $result */
        $result = $this->validator->validate('1/3/2013');
        $this->assertTrue($result->hasErrors());
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator.range.invalid', 3000000008), $result->getErrors()));
    }

    /**
     * @test
     */
    public function DateShouldBeBeInRangeOnlyBefore(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y','before' => '1/4/2013'));
        /** @var \TYPO3\CMS\Extbase\Error\Result $result */
        $result = $this->validator->validate('1/3/2013');
        $this->assertFalse($result->hasErrors());
    }

    /**
     * @test
     */
    public function DateShouldBeBeInRangeOnlyAfter(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y','after' => '1/1/2013'));
        /** @var \TYPO3\CMS\Extbase\Error\Result $result */
        $result = $this->validator->validate('1/3/2013');
        $this->assertFalse($result->hasErrors());
    }

    /**
     * @test
     */
    public function DateShouldNotBeInRangeBeforeMinimum(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y','after' => '1/4/2013'));
        /** @var \TYPO3\CMS\Extbase\Error\Result $result */
        $result = $this->validator->validate('1/3/2013');
        $this->assertTrue($result->hasErrors());
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator.range.outofrange', 3000000009), $result->getErrors()));
    }

    /**
     * @test
     */
    public function DateShouldNotBeBeInRangeAfterMaximum(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y','before' => '1/2/2013'));
        /** @var \TYPO3\CMS\Extbase\Error\Result $result */
        $result = $this->validator->validate('1/3/2013');
        $this->assertTrue($result->hasErrors());
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator.range.outofrange', 3000000009), $result->getErrors()));
    }

    /**
     * @test
     */
    public function DateShouldBeInRange(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y','after' => '1/1/2013', 'before' => '1/4/2013'));
        /** @var \TYPO3\CMS\Extbase\Error\Result $result */
        $result = $this->validator->validate('1/3/2013');
        $this->assertFalse($result->hasErrors());
    }

    /**
     * @test
     */
    public function DateShouldNotBeInRange(){
        $this->validatorOptions(array('type' => 'range','strftime' => 'd/m/Y','after' => '1/4/2013', 'before' => '1/6/2013'));
        /** @var \TYPO3\CMS\Extbase\Error\Result $result */
        $result = $this->validator->validate('1/3/2013');
        $this->assertTrue($result->hasErrors());
        $this->assertTrue(in_array(new \TYPO3\CMS\Extbase\Validation\Error('validator.range.outofrange', 3000000009), $result->getErrors()));
    }

    public function isNumberWrong(){
        $this->assertTrue($this->validator->validate('asder')->hasErrors());

    }
}
