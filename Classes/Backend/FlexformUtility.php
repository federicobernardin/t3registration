<?php


namespace TYPO3\T3registration\Backend;


use TYPO3\T3registration\Utility\ValidatorUtility;

class FlexformUtility {


    public function getFrontendUserProperties(array &$config, \TYPO3\CMS\Backend\Form\FormEngine $parentFormObject) {
        /** @var \ReflectionClass $reflectedClass */
        $reflectedClass = new \ReflectionClass('\TYPO3\T3registration\Domain\Model\User');
        $properties = $reflectedClass->getProperties();
        foreach ($properties as $property) {
            if(($label = $GLOBALS['LANG']->sL($property->getName())) != ''){
                array_push($config['items'], array($label, $property->getName(), ''));
            }
            else{
                array_push($config['items'], array($property->getName(), $property->getName(), ''));
            }
        }
    }

    public function getFrontendUserValidators(array &$config,$parentFormObject) {
        $validators = ValidatorUtility::getValidators();
        foreach ($validators as $key => $validator) {
            if(strstr('LLL',$validator->getLabel()) != false){
                $label = $GLOBALS['LANG']->sL($validator->getLabel());
            }
            else{
                $label = $GLOBALS['LANG']->sL('LLL:EXT:t3registration/Resources/Private/Language/locallang_be.xlf:' . $validator->getLabel());
            }
            //TODO vedere se si può rimuovere il pezzo successivo
            if($label == ''){
                $label = $validator->getLabel();
            }
            array_push($config['items'], array($label, $key, ''));
        }
    }
}