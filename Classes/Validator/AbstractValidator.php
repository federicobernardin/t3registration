<?php


namespace TYPO3\T3registration\Validator;


class AbstractValidator implements ValidatorInterface {

    /**
     * @var \TYPO3\CMS\Extbase\Error\Result
     */
    protected $result;

    /**
     * @var array
     */
    protected $options = array();

    /**
     * Sets options for the validator
     *
     * @param array $validationOptions Options for the validator
     * @api
     */
    public function __construct($validationOptions = array()) {
        $this->setOptions($validationOptions);
        $this->result = new \TYPO3\CMS\Extbase\Error\Result();
    }

    public function setOptions($validationOptions = array()){
        $this->options = $validationOptions;
    }

    public function addOption($key, $value){
        $this->options[$key] = $value;
    }

    /**
     * {@inheritdoc }
     */
    public function getErrors() {
        return $this->result->getErrors();
    }

    /**
     * {@inheritdoc }
     */
    public function getLabel() {
        return '';
    }

    /**
     * {@inheritdoc }
     */
    public function validate($value) {
        return true;
    }

    /**
     * Creates a new validation error object and adds it to $this->errors
     *
     * @param string $message The error message
     * @param integer $code The error code (a unix timestamp)
     * @param array $arguments Arguments to be replaced in message
     * @param string $title title of the error
     * @return void
     */
    protected function addError($message, $code, array $arguments = array(), $title = '') {
        if ($this->result !== NULL) {
            $this->result->addError(new \TYPO3\CMS\Extbase\Validation\Error($message, $code, $arguments, $title));
        }
    }

    /**
     * add optional options by configuration
     * Usually it is called by validatorManager with flexfomr Confirguration and set them to options
     *
     * @param array $configurationOptions
     */
    public function prepareOptions($configurationOptions = array()){

    }

}