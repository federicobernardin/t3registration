<?php


namespace Tests\Unit\Validator;


abstract class AbstractValidatorTestcase extends \PHPUnit_Framework_TestCase {

    protected $validatorClassName;

    /**
     * @var \TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface
     */
    protected $validator;

    public function setUp() {
        $this->validator = $this->getValidator();
    }

    /**
     * @param array $options
     * @return mixed
     */
    protected function getValidator($options = array()) {
        $validator = new $this->validatorClassName($options);
        return $validator;
    }

    /**
     * @param array $options
     */
    protected function validatorOptions($options) {
        $this->validator = $this->getValidator($options);
    }
}