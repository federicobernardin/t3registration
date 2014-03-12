<?php


namespace Tests\Unit\Validator;


use TYPO3\T3registration\Utility\ValidatorUtility;

class ValidatorUtilityTest extends  \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {

    public function tearDown() {
        ValidatorUtility::removeAll();
    }

    /**
     * @test
     */
    public function testAddValidatorImplementedInterface(){
        ValidatorUtility::addValidator('TYPO3\T3registration\Validator\\IntegerValidator');
        $this->assertEquals(1,count(ValidatorUtility::getValidators()));
    }



    /**
     * @test
     */
    public function testRemoveValidator(){
        ValidatorUtility::addValidator('TYPO3\T3registration\Validator\\IntegerValidator');
        ValidatorUtility::removeValidator('TYPO3\T3registration\Validator\\IntegerValidator');
        $this->assertEquals(1,count(ValidatorUtility::getValidators()));
    }

    /**
     * @test
     */
    public function testAddValidatorNotImplementedInterface(){
        ValidatorUtility::addValidator('TYPO3\\T3registration\\Cache\\CacheBuilder');
        $this->assertEquals(0,count(ValidatorUtility::getValidators()));
    }
}
