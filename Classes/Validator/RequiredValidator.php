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
        $this->resetValidatorResult();
        if(strlen($value) == 0){
            $this->addError(
                'validator_required_notvalid',
                3000000003);
        }
        return !$this->result->hasErrors();

    }
}