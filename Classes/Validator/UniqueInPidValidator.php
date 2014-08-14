<?php


namespace TYPO3\T3registration\Validator;


use TYPO3\T3registration\Domain\Repository\UserRepository;

class UniqueInPidValidator extends AbstractValidator{

    /**
     * @var \TYPO3\T3registration\Domain\Repository\UserRepository
     * @inject
     */
    protected $userRepository = null;

    /**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManager
     */
    protected $objectManager = null;

    /**
     * {@inheritdoc }
     */
    public function getLabel() {
        return 'validator_uniqueInPidValidator';
    }

    /**
     * {@inheritdoc }
     */
    public function validate($value) {
        $this->getUserRepository();
        $field = (isset($this->options['usernameField']))?$this->options['usernameField']:'username';
        $pid = (isset($this->options['pid']))?$this->options['pid']:$GLOBALS['TSFE']->id;
        $count = $this->userRepository->countUniqueByField($field,$value,$pid);
        if($count>0){
            $this->addError(
                'validator_unique_notvalid',
                3000000013);
        }
        return !$this->result->hasErrors();
    }

    protected function getUserRepository() {
        if ($this->userRepository == null) {
            if ($this->objectManager == null) {
                $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
            }
            $this->userRepository = $this->objectManager->get('TYPO3\\T3registration\\Domain\\Repository\\UserRepository');
        }
    }
}