<?php


namespace TYPO3\T3registration\Tests\Unit\Validator;


class ValidatorManagerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
     */
    protected $fixture;

    public function SetUp(){
        $this->fixture = $this->getAccessibleMock('TYPO3\\T3registration\\Validator\\ValidatorManager',array('reset'));
    }

    /**
     * @test
     */
    public function noErrorsFromValidators(){
        $this->fixture->expects($this->once())->method('reset');
        $requiredValidator = new \TYPO3\T3registration\Validator\RequiredValidator();
        $result = new \TYPO3\CMS\Extbase\Error\Result();
        $this->fixture->_set('validators',array('TYPO3\T3registration\Validator\RequiredValidator' => $requiredValidator));
        $this->fixture->_set('result',$result);
        $this->assertTrue($this->fixture->validate('pippo',array()));
        $this->assertEquals(0,count($this->fixture->getResult()->getErrors()));
    }

    /**
     * @test
     */
    public function oneErrorFromValidators(){
        $this->fixture->expects($this->once())->method('reset');
        $requiredValidator = new \TYPO3\T3registration\Validator\RequiredValidator();
        $result = new \TYPO3\CMS\Extbase\Error\Result();
        $this->fixture->_set('validators',array('TYPO3\T3registration\Validator\RequiredValidator' => $requiredValidator));
        $this->fixture->_set('result',$result);
        $this->assertTrue($this->fixture->validate('',array()));
        $this->assertEquals(1,count($this->fixture->getResult()->getErrors()));
    }
}
