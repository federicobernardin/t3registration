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
        if (!is_string($value)) {
            $this->addError('validator.istring.notvalid',
                3000000001);
        }
        return $this->result;
    }
}