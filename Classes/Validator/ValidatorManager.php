<?php


namespace TYPO3\T3registration\Validator;


use TYPO3\T3registration\Utility\ValidatorUtility;

class ValidatorManager {

    protected $validators = array();

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager;

    /**
     * @var \TYPO3\CMS\Extbase\Error\Result
     */
    protected $result;

    protected $options = array();


    public function setValidators($validatorList){
        $validators = explode(',',$validatorList);
        $this->validators = array();
        foreach($validators as $validator){
            $this->validators[$validator] = ValidatorUtility::getValidator($validator);
        }
    }

    public function setOptions($options){
        $this->options = $options;
    }

    protected function reset($fieldConfiguration){
        $this->setValidators($fieldConfiguration['validators']);
        if(isset($fieldConfiguration['validator']['options']) && is_array($fieldConfiguration['validator']['options'])){
            $this->setOptions($fieldConfiguration['validator']['options']);
        }
        $this->result = $this->objectManager->get('TYPO3\CMS\Extbase\Error\Result');
    }

    public function getResult(){
        return $this->result;
    }

    public function validate($value,$fieldConfiguration){
        $this->reset($fieldConfiguration);
        /** @var \TYPO3\T3registration\Validator\AbstractValidator $validator */
        foreach($this->validators as $validatorName => $validator){
            if(isset($this->options[$validatorName])){
                $validator->setOptions($this->options[$validatorName]);
            }
            if(!$validator->validate($value)){
                foreach($validator->getErrors() as $error){
                    $this->result->addError($error);
                }
            }
        }
        return !$this->result->hasErrors();
    }

}