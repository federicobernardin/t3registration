<?php


namespace TYPO3\T3registration\Validator;


class RegexpValidator extends AbstractValidator{

    /**
     * {@inheritdoc }
     */
    public function getLabel() {
        return 'validator_regexpValidator';
    }

    /**
     * {@inheritdoc }
     */
    public function validate($value) {
        if (!isset($this->options['regularExpression'])) {
            $this->addError(
                    'validator.regexp.empty',
                    3000000004);
        }
        $result = preg_match($this->options['regularExpression'], $value);
        if ($result === 0) {
            $this->addError(
                'validator.regexp.nomatch',
                3000000004);
        }
        if ($result === FALSE) {
            $this->addError(
                'validator.regexp.error',
                3000000005);
            return FALSE;
        }
        return $this->result;
    }
}