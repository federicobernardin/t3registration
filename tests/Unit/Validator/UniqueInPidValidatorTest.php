<?php


namespace TYPO3\T3registration\Tests\Unit\Validator;

//@todo rimuovere e risolvere il problema del perchè non include il repository
require_once(__DIR__ . '/../../../Classes/Domain/Repository/UserRepository.php');

class UniqueInPidValidatorTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
     */
    protected $fixture;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
     */
    protected $userRepository;


    public function setUp() {
        parent::setUp();
        $this->fixture = $this->getAccessibleMock('TYPO3\\T3registration\\Validator\\UniqueInPidValidator',array('dummy'));
        $this->userRepository = $this->getAccessibleMock('TYPO3\\T3registration\\Domain\\Repository\\UserRepository',array('countUniqueByField'),array(),'',false);

    }


    /**
     * @test
     */
    public function isUnique(){
        $this->fixture->_set('userRepository',$this->userRepository);
        $this->userRepository->expects($this->once())->method('countUniqueByField')->will($this->returnValue(0));
        $this->assertTrue($this->fixture->validate('value'));
    }

    /**
     * @test
     */
    public function isNotUnique(){
        $this->fixture->_set('userRepository',$this->userRepository);
        $this->userRepository->expects($this->once())->method('countUniqueByField')->will($this->returnValue(1));
        $this->assertFalse($this->fixture->validate('value'));

    }
}
