<?php


namespace TYPO3\T3registration\Validator;


class DateValidator extends AbstractValidator{

    /**
     * {@inheritdoc }
     */
    public function getLabel() {
        return 'validator_dateValidator';
    }

    /**
     * {@inheritdoc }
     */
    public function validate($value){
        if (!isset($this->options['type'])) {
            $this->addError(
                'validator.date.notdefined',
                3000000005);
        }
        if (!isset($this->options['strftime'])) {
            $this->addError(
                'validator.strftime.notdefined',
                3000000006);
        }
        if (!isset($this->options['timezone'])) {
            $this->options['timezone'] = 'UTC';
        }
        if(($date = $this->getTimestampFromDate($value)) === false){
            $this->addError(
                'validator.date.wrongformat',
                3000000007);
        }
        switch($this->options['type']){
            case 'future':
                if($date <= $this->getTimestampFromDate(null)){
                    $this->addError(
                        'validator.date.notinfuture',
                        3000000011);
                }
                break;
            case 'past':
                if($date >= $this->getTimestampFromDate(null)){
                    $this->addError(
                        'validator.date.notinpast',
                        3000000012);
                }
                break;
            case 'range':
                $this->testRange($date);
                break;
            default:
                $this->addError(
                    'validator.date.wrongtype',
                    3000000010);
        }
        return $this->result;
    }

    protected function testRange($date){
        $after = (!isset($this->options['after'])) ? false : $this->getTimestampFromDate($this->options['after']);
        $before = (!isset($this->options['before'])) ? false : $this->getTimestampFromDate($this->options['before']);
        if($after === false && $before === false){
            $this->addError(
                'validator.range.invalid',
                3000000008);
        }
        if(($before !== false && $date > $before) || ($after !== false && $date < $after)){
            $this->addError(
                'validator.range.outofrange',
                3000000009);
        }
    }

    protected function getTimestampFromDate($date) {
        $timezone = $this->options['timezone'];
        date_default_timezone_set($timezone);
        if($date === ''){
            return false;
        }
        if (isset($this->options['strftime'])) {
            if($date === null){
                $date = date($this->options['strftime']);
            }
            $parsedArray = date_parse_from_format($this->options['strftime'], $date);
            if ($parsedArray['error_count'] == 0) {
                if ($parsedArray['warning_count'] > 0) {
                    return false;
                } else {
                    $timestamp = mktime(0, 0, 0, $parsedArray['month'], $parsedArray['day'], $parsedArray['year']);
                    return $timestamp;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}