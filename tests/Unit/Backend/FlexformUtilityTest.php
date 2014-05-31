<?php


namespace TYPO3\T3registration\Tests\Unit\Backend;
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
     *  the Free Software Foundation; either version 2 of the License, or
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

/**
 * Test case for class FlexformUtility.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @package TYPO3
 * @subpackage T3Registration
 *
 * @author Federico Bernardin <federico@bernardin.it>
 */


require_once __DIR__ . '/UserFakeClass.php';

class FlexformUtilityTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

    /**
     * @var \TYPO3\CMS\Backend\Form\FormEngine
     */
    protected $formEngine;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface
     */
    protected $fixture;

    public function setUp() {
        $this->fixture = $this->getAccessibleMock('TYPO3\\T3registration\\Backend\\FlexformUtility',array('getReflectedClassForUser','getValidators','sL','getArrayFromXml'));
        $this->formEngine = $this->getAccessibleMock('TYPO3\\CMS\\Backend\\Form\\FormEngine',array('dummy'));
    }

    public function tearDown() {
        unset($this->fixture);
    }

    /**
     * Test properties read for user fake class
     * @test
     */
    public function getFrontendUserPropertiesItemsForFormEngine() {
        $reflectedClass = new \ReflectionClass('\TYPO3\T3registration\Tests\Unit\Backend\UserFakeClass');
        $this->fixture->expects($this->once())->method('getReflectedClassForUser')->will($this->returnValue($reflectedClass));
        $this->fixture->expects($this->atLeastOnce())->method('sL')->will($this->returnValue('labelTest'));
        $config['items'] = array();
        $this->fixture->getFrontendUserProperties($config,$this->formEngine);
        $compare['items'] = array(
            array('labelTest','name',''),
            array('labelTest','surname','')
        );
        $this->assertEquals($compare,$config);
    }

    /**
     * Test validator list
     * @test
     */
    public function getValidatorsListItemsForFormEngine() {
        $validators['\TYPO3\T3registration\Validator\DateValidator'] = new \TYPO3\T3registration\Validator\DateValidator;
        $this->fixture->expects($this->once())->method('getValidators')->will($this->returnValue($validators));
        $this->fixture->expects($this->atLeastOnce())->method('sL')->will($this->returnValue('labelTest'));
        $config['items'] = array();
        $this->fixture->getFrontendUserValidators($config,$this->formEngine);
        $compare['items'] = array(
            array('labelTest','\TYPO3\T3registration\Validator\DateValidator','')
        );
        $this->assertEquals($compare,$config);
    }

    /**
     * Test regexp object
     * @test
     */
    public function getRegularExpressionObjectForFormEngine() {
        $data['data']['fieldsSheet']['lDEF']['settings.fields']['el']['1']['databaseField']['el']['validators']['vDEF'] = 'TYPO3%5CT3registration%5CValidator%5CRegexpValidator|Regexp';
        $DataArray['itemFormElName'] = 'elementName';
        $DataArray['itemFormElValue'] = 'elementValue';
        $DataArray['fieldChangeFunc'] = array();
        $DataArray['onFocus'] = '';
        $DataArray['row']['pi_flexform'] = '';
        $DataArray['itemFormElID'] = '[data][fieldsSheet][lDEF][settings.fields][el][1][databaseField][el][validators][vDEF]';
        $validators['\TYPO3\T3registration\Validator\DateValidator'] = new \TYPO3\T3registration\Validator\DateValidator;
        $this->fixture->expects($this->once())->method('getArrayFromXml')->will($this->returnValue($data));
        $config['items'] = array();
        $formFieldActual = $this->fixture->getRegularExpressionField($DataArray,$this->formEngine);
        $formField = '<div style="padding: 5px;">';
        $formField .= '<input type="text" name="' . $DataArray['itemFormElName'] . '"';
        $formField .= ' value="' . htmlspecialchars($DataArray['itemFormElValue']) . '"';
        $formField .= ' onchange="' . htmlspecialchars(implode('', $DataArray['fieldChangeFunc'])) . '"';
        $formField .= $DataArray['onFocus'];
        $formField .= ' /></div>';
        $this->assertEquals($formField,$formFieldActual);
    }



}