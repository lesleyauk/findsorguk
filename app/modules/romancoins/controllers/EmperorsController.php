<?php
/** Controller for displaying Roman Emperors
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package Pas_Controller_Action 
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @uses Emperors
 * @uses Pas_Exception_Param
 * @uses Mints
 * @uses Denominations
*/
class RomanCoins_EmperorsController extends Pas_Controller_Action_Admin {

    /** The Emperors model
     * @access protected
     * @var \Emperors
     */
    protected $_emperors;

    /** The contexts array
     * @access protected
     * @var array
     */
    protected $_contexts = array('xml','json', 'rdf');

    /** Set up the ACL and contexts
    * @todo Move the api key to the view
    */
    public function init() {
        $this->_helper->_acl->allow(null);
        $this->_helper->contextSwitch()->setAutoJsonSerialization(false);
        $this->_helper->contextSwitch()->setAutoDisableLayout(true)
                ->addContext('rdf',
                        array(
                            'suffix' => 'rdf',
                            'headers' => array(
                                'Content-Type' => 'application/rdf+xml',
                                )
                            ))
                ->addActionContext('index', $this->_contexts)
                ->addActionContext('emperor', $this->_contexts)
                ->addActionContext('data', array('json'))
                ->initContext();
        $this->_emperors = new Emperors();
        
    }
    
    /** Set up the emperor index pages
     * @access public
     * @return void
     */
    public function indexAction() {
        if(!in_array($this->_helper->contextSwitch->getCurrentContext(),$this->_contexts)){
            $this->view->julioclaudian = $this->_emperors->getDynEmp(1);
            $this->view->civilwar = $this->_emperors->getDynEmp(2);
            $this->view->flavian = $this->_emperors->getDynEmp(3);
            $this->view->adoptive = $this->_emperors->getDynEmp(4);
            $this->view->antonine = $this->_emperors->getDynEmp(5);
            $this->view->waremperors = $this->_emperors->getDynEmp(6);
            $this->view->severan = $this->_emperors->getDynEmp(7);
            $this->view->thirdcentury = $this->_emperors->getDynEmp(8);
            $this->view->british = $this->_emperors->getDynEmp(9);
            $this->view->gallic = $this->_emperors->getDynEmp(10);
            $this->view->tetrarchy = $this->_emperors->getDynEmp(11);
            $this->view->constantine = $this->_emperors->getDynEmp(12);
            $this->view->valentinian = $this->_emperors->getDynEmp(13);
            $this->view->theodosius = $this->_emperors->getDynEmp(14);
            $this->view->fourthcentury = $this->_emperors->getDynEmp(16);
        } else {
            $this->view->emperors = $this->_emperors->getEmperors();
        }
    }

    /** Set up the individual emperor
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function emperorAction() {
        if($this->getParam('id',false)){
            $id = (int)$this->getParam('id');
            $this->view->emps = $this->_emperors->getEmperorDetails($id);
            $denoms = new Denominations();
            $this->view->denoms = $denoms->getEmperorDenom($id);
            $mints = new Mints();
            $this->view->mints = $mints->getMintEmperorList($id);
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Action to run the timeline
     * @access public
     * @return void
     */
    public function timelineAction() {
        $this->_helper->layout->disableLayout();
    }

    /** Data action for timeline etc
     * @access public
     * @return void
     */
    public function dataAction(){
        $this->view->emperors = $this->_emperors->getEmperorsTimeline();
    }
}