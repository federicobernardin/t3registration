<?php


namespace TYPO3\T3registration\Validator;


class RequiredValidator extends AbstractValidator{

    /**
     * {@inheritdoc }
     */
    public function getLabel() {
        return 'validator_requiredValidator';
    }

    /**
     * {@inheritdoc }
     */
    public function validate($value) {
        if(strlen($value) == 0){
            $this->addError(
                'validator.required.notvalid',
                3000000003);
        }
        return $this->result;

    }
}