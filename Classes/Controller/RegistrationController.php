<?php
namespace TYPO3\T3registration\Controller;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Federico Bernardin <federico.bernardin@immaginario.com>, BFConsulting
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
use TYPO3\CMS\Core\FormProtection\Exception;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use TYPO3\T3registration\Utility\ValidatorUtility;

/**
 *
 *
 * @package t3registration
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class RegistrationController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{

    /**
     * userRepository
     *
     * @var \TYPO3\T3registration\Domain\Repository\UserRepository
     * @inject
     */
    protected $userRepository;

    /**
     * report
     *
     * @var \TYPO3\T3registration\Report\Report
     */
    protected $report;

    /**
     * @var \TYPO3\T3registration\Validator\ValidatorManager
     * @inject
     */
    protected $validatorManager;

    /**
     * @var \TYPO3\CMS\Extbase\Error\Result
     */
    protected $argumentsResult;

    /**
     * @var string name of back button for returning from preview to edit
     */
    protected $backButtonName = 'Back';

    /**
     * @var array the list of fields elaborated from flexform and reflection
     */
    protected $fields;

    /**
     * action new
     *
     * @param \TYPO3\T3registration\Domain\Model\User $user
     * @ignorevalidation $user
     * @t3registrationIgnoreValidation
     *
     * @return void
     */
    public function newAction(\TYPO3\T3registration\Domain\Model\User $user = NULL)
    {
        //$this->checkConfiguration();
        $validator = ValidatorUtility::getValidator('TYPO3\\T3registration\\Validator\\UniqueInPidValidator');
        $validator->setOptions(array('pid' => 36));
        $this->view->assign('user', $user);
    }

    /**
     * Use this parent function to inject Report System for generic configuration errors
     * It is called before setting view, in case of error switching to error view
     *
     * @param ViewInterface $view
     */
    protected function setViewConfiguration(ViewInterface $view)
    {
        parent::setViewConfiguration($view);
        $this->report = new \TYPO3\T3registration\Report\Report();
        $this->report->setView($view);
        $notHasError = $this->report->checkConfiguration($this->settings);
        if (!$notHasError) {
            $view->setTemplatePathAndFilename(ExtensionManagementUtility::extPath('t3registration') . 'Resources/Private/Templates/Registration/Error.html');
        }
    }

    /**
     * This class method set settings value to specific class properties
     */
    public function SetSettingsToClass(){
        if(isset($this->settings['backButtonName'])){
            $this->backButtonName = $this->settings['backButtonName'];
        }
    }

    /**
     * Overload of parent method to intercept generic misconfiguration messages
     *
     * @see setViewConfiguration
     */
    public function callActionMethod()
    {
        if (!$this->report->hasError()) {
            $this->SetSettingsToClass();
            $this->validateArguments();
            parent::callActionMethod();
        } else {
            $this->view->assign('messages', $this->report->getError());
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' . ExtensionManagementUtility::siteRelPath('t3skin') . 'Resources/Public/Css/visual/element_message.css" />');
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' . ExtensionManagementUtility::siteRelPath('t3skin') . 'Resources/Public/Css/structure/element_message.css" />');
            $this->response->addAdditionalHeaderData('<link rel="stylesheet" type="text/css" href="' . ExtensionManagementUtility::siteRelPath('t3registration') . 'Resources/Public/Css/message.css" />');
            $this->response->appendContent($this->view->render('Error'));
        }
    }

    /**
     * This function validates fields and saves result in Result object
     *
     * @param array $parameters the user object properties passed to controller
     * @return Result
     */
    private function validateFields($parameters)
    {
        $results = new \TYPO3\CMS\Extbase\Error\Result();
        //extract the list of validating fields from flexform, so external will not be validated
        $this->fields = $this->prepareFlexformFields();
        foreach ($this->fields as $field) {
            if($field['ignoreValidationFromClass']){
                continue;
            }
            if ($this->validatorManager->validate($parameters[$field['name']], $field) === true) {
                continue;
            }
            //overwrite every cycle the Result object
            $results->forProperty('user')->forProperty($field['name'])->merge($this->validatorManager->getResult());
        }
        return $results;
    }

    /**
     * This function validates argument and if some errors raise forward to previous action
     */
    private function validateArguments()
    {
        //get Arguments from request
        $arguments = $this->request->getArguments();
        //saves in argumentsResult the Result object from validating process (!! the posted user object must be called
        //user
        $this->argumentsResult = $this->validateFields($arguments['user']);
        if (count($this->argumentsResult->getSubResults())) {
            $methodTagsValues = $this->reflectionService->getMethodTagsValues(get_class($this), $this->actionMethodName);
            $ignoreValidationAnnotations = array();
            //if set annotation for method ignore validation don't execute forwarding procedure
            //this is similar to ignorevalidation
            if (isset($methodTagsValues['t3registrationIgnoreValidation'])) {
                return;
            }
            if (isset($methodTagsValues['ignorevalidation'])) {
                $ignoreValidationAnnotations = $methodTagsValues['ignorevalidation'];
            }
            // if there exists more errors than in ignoreValidationAnnotations_=> call error method
            // else => call action method
            $shouldCallActionMethod = TRUE;
            foreach ($this->argumentsResult->getSubResults() as $argumentName => $subValidationResult) {
                if (!$subValidationResult->hasErrors()) {
                    continue;
                }
                // setting @ignorevalidation $myvariable, myvariable will be not validated
                if (array_search('$' . $argumentName, $ignoreValidationAnnotations) !== FALSE) {
                    continue;
                }

                $shouldCallActionMethod = FALSE;
            }
            if (!$shouldCallActionMethod) {
                $this->forwardToPreviousAction();
            }
        }
    }

    /**
     * Extracts the list of fields from flexform
     *
     * @return array the extracted list of fields
     */
    private function prepareFlexformFields()
    {
        $fields = array();
        if (isset($this->settings['fields']) && is_array($this->settings['fields'])) {
            foreach ($this->settings['fields'] as $field) {
                $field['databaseField']['validators'] = $this->mergeValidatorsFromReflection($field);
                $field['databaseField']['ignoreValidationFromClass'] = $this->addIgnoreValidationFromReflection($field);
                $fields[] = $field['databaseField'];
            }
        }
        return $fields;
    }

    protected function addIgnoreValidationFromReflection($field){
        $additionalValidatorsFromTag = $this->reflectionService->getPropertyTagValues('\TYPO3\T3registration\Domain\Model\User',$field['databaseField']['name'],'ignorevalidation');
        if(count($additionalValidatorsFromTag)){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Adds validators from reflectiontag to those in the flexform
     *
     * @param array $field  $this->settings['fields'] from Flexform
     * @return string
     */
    protected function mergeValidatorsFromReflection($field){
        $validators = $field['databaseField']['validators'];
        $additionalValidatorsFromTag = $this->reflectionService->getPropertyTagValues('\TYPO3\T3registration\Domain\Model\User',$field['databaseField']['name'],'additionalValidators');
        if(count($additionalValidatorsFromTag)){
            $additionalValidatorsFromTag = explode(',',$additionalValidatorsFromTag[0]);
            $validatorsFromFlexform = explode(',',$field['databaseField']['validators']);
            $validators = array_merge($validatorsFromFlexform,$additionalValidatorsFromTag);
            $validators = implode(',',$validators);
        }
        return $validators;
    }

    /**
     * action create
     * @var \TYPO3\T3registration\Domain\Model\User $user
     *
     * @return void
     */
    public function createAction(\TYPO3\T3registration\Domain\Model\User $user)
    {
        $arguments = $this->request->getArguments();
        if ($arguments[$this->backButtonName]) {
            $this->forwardWithReferringDataToAction('new');
        } else {
            $this->userRepository->add($user);
            $this->flashMessageContainer->add('Your new User was created.');
            $this->redirect('new');
        }
    }

    protected function forwardWithReferringDataToAction($action){
        $referringRequest = $this->request->getReferringRequest();
        if ($referringRequest !== NULL) {
            $this->forward($action, $referringRequest->getControllerName(), $referringRequest->getControllerExtensionName(), $referringRequest->getArguments());
        }
        else{
            throw new \Exception('Referring request not found');
        }
    }

    /**
     * Forward to previous action including errors
     */
    public function forwardToPreviousAction()
    {
        $referringRequest = $this->request->getReferringRequest();
        if ($referringRequest !== NULL) {
            $originalRequest = clone $this->request;
            $this->request->setOriginalRequest($originalRequest);
            $this->request->setOriginalRequestMappingResults($this->argumentsResult);
            $this->forward($referringRequest->getControllerActionName(), $referringRequest->getControllerName(), $referringRequest->getControllerExtensionName(), $referringRequest->getArguments());
        }
    }

    /**
     * action edit
     *
     * @param \TYPO3\T3registration\Domain\Model\User $user
     * @return void
     */
    public function editAction(\TYPO3\T3registration\Domain\Model\User $user)
    {
        $this->view->assign('user', $user);
    }

    /**
     * action edit
     *
     * @param \TYPO3\T3registration\Domain\Model\User $user
     * @return void
     */
    public function previewAction(\TYPO3\T3registration\Domain\Model\User $user)
    {
        $this->view->assign('user', $user);
    }

    /**
     * action update
     *
     * @param \TYPO3\T3registration\Domain\Model\User $user
     * @return void
     */
    public function updateAction(\TYPO3\T3registration\Domain\Model\User $user)
    {
        $this->userRepository->update($user);
        $this->flashMessageContainer->add('Your User was updated.');
        $this->redirect('list');
    }

    /**
     * action delete
     *
     * @param \TYPO3\T3registration\Domain\Model\User $user
     * @return void
     */
    public function deleteAction(\TYPO3\T3registration\Domain\Model\User $user)
    {
        $this->userRepository->remove($user);
        $this->flashMessageContainer->add('Your User was removed.');
        $this->redirect('list');
    }


}

?>