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
        $this->resetValidatorResult();
        if (!isset($this->options['type'])) {
            $this->addError(
                'validator_type_notdefined',
                3000000005);
        }
        if (!isset($this->options['strftime'])) {
            $this->addError(
                'validator_strftime_notdefined',
                3000000006);
        }
        if (!isset($this->options['timezone'])) {
            $this->options['timezone'] = 'UTC';
        }
        if(($date = $this->getTimestampFromDate($value)) === false){
            $this->addError(
                'validator_date_wrongformat',
                3000000007);
        }
        switch($this->options['type']){
            case 'future':
                if($date <= $this->getTimestampFromDate(null)){
                    $this->addError(
                        'validator_date_notinfuture',
                        3000000011);
                }
                break;
            case 'past':
                if($date >= $this->getTimestampFromDate(null)){
                    $this->addError(
                        'validator_date_notinpast',
                        3000000012);
                }
                break;
            case 'range':
                $this->testRange($date);
                break;
            //todo: forse manca il caso in cui non voglio controlli se non che la data sia una data
            default:
                $this->addError(
                    'validator_date_wrongtype',
                    3000000010);
        }
        return !$this->result->hasErrors();
    }

    protected function testRange($date){
        $after = (!isset($this->options['after'])) ? false : $this->getTimestampFromDate($this->options['after']);
        $before = (!isset($this->options['before'])) ? false : $this->getTimestampFromDate($this->options['before']);
        if($after === false && $before === false){
            $this->addError(
                'validator_range_invalid',
                3000000008);
        }
        if(($before !== false && $date > $before) || ($after !== false && $date < $after)){
            $this->addError(
                'validator_range_outofrange',
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