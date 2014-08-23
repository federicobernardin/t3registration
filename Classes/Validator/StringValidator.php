<?php


namespace TYPO3\T3registration\Validator;


class StringValidator extends AbstractValidator{

    /**
     * {@inheritdoc }
     */
    public function getLabel() {
        return 'validator_stringValidator';
    }

    /**
     * {@inheritdoc }
     */
    public function validate($value) {
        $this->resetValidatorResult();
        if (!is_string($value)) {
            $this->addError('validator_istring_notvalid',
                3000000001);
        }
        return !$this->result->hasErrors();
    }
}