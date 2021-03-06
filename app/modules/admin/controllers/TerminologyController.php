<?php

/** Controller for administering the terminology on the database
 *
 * @category   Pas
 * @package Pas_Controller_Action
 * @subpackage Admin
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @version 1
 * @uses PrimaryActivities
 * @uses Workflows
 * @uses WorkflowForm
 * @uses ActivityForm
 * @uses DiscoMethods
 * @uses DiscoMethodsForm
 * @uses DecMethods
 * @uses DecMethodsForm
 * @uses SurfTreatments
 * @uses SurfTreatmentsForm
 * @uses Periods
 * @uses PeriodForm
 * @uses Cultures
 * @uses CultureForm
 * @uses MapOrigins
 * @uses OriginForm
 * @uses Preservations
 * @uses PreservationsForm
 * @uses Findofnotereasons
 * @uses ManufacturesForm
 * @uses Manufactures
 * @uses Materials
 * @uses MaterialForm
 * @uses DecStylesForm
 * @uses DecStyles
 * @uses Landuses
 * @uses LandusesForm
 */
class Admin_TerminologyController extends Pas_Controller_Action_Admin
{

    /** The redirect uri
     * @access protected
     * @var string
     */
    protected $_redirectUrl = '/admin/terminology/';

    /** The update constant
     *
     */
    CONST UPDATE = 'Update details';

    /** The deleted constant
     *
     */
    CONST DELETED = 'Record deleted!';

    /** Setup the ACL.
     * @access public
     * @return void
     */
    public function init()
    {
        $this->_helper->_acl->allow('fa', null);
        $this->_helper->_acl->allow('admin', null);

    }

    /** Display the index page
     * @access public
     * @return void
     */
    public function indexAction()
    {
        //Nothing doing here - redirect>
    }

    /** Display a list of activities for finders.
     * @access public
     * @return void
     */
    public function activitiesAction()
    {
        $activities = new PrimaryActivities();
        $this->view->activities = $activities->getActivitiesListAdmin();
    }

    /** Add an activity
     * @access public
     * @return void
     */
    public function addactivityAction()
    {
        $form = new ActivityForm();
        $form->submit->setLabel('Add a new activity');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $activities = new PrimaryActivities();
                $activities->add($form->getValues());
                $this->redirect($this->_redirectUrl . 'activities');
                $this->getFlash()->addMessage('Activity created!');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit an activity
     * @access public
     * @return void
     */
    public function editactivityAction()
    {
        if ($this->_getparam('id', false)) {
            $form = new ActivityForm();
            $form->submit->setLabel(self::UPDATE);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $activities = new PrimaryActivities();
                    $where = array();
                    $where[] = $activities->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
                    $activities->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Activity details updated');
                    $this->redirect($this->_redirectUrl . 'activities');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $activities = new PrimaryActivities();
                    $activity = $activities->fetchRow('id=' . (int)$id);
                    if (count($activity)) {
                        $form->populate($activity->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete an activity
     * @access public
     * @return void
     */
    public function deleteactivityAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $activities = new PrimaryActivities();
                $where = 'id = ' . $id;
                $activities->delete($where);
            }
            $this->redirect($this->_redirectUrl . 'activities');
            $this->getFlash()->addMessage(self::DELETED);
        } else {
            $id = (int)$this->_request->getParam('id');
            if ((int)$id > 0) {
                $activities = new PrimaryActivities();
                $this->view->activity = $activities->fetchRow('id=' . $id);
            }
        }
    }

    /** Display a list of discovery methods
     * @access public
     * @return void
     */
    public function methodsAction()
    {
        $methods = new DiscoMethods();
        $this->view->methods = $methods->getDiscMethodsListAdmin();
    }

    /** Edit a method of discovery
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editmethodAction()
    {
        if ($this->getParam('id', false)) {
            $form = new DiscoMethodsForm();
            $form->submit->setLabel(self::UPDATE);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $methods = new DiscoMethods();
                    $where = array();
                    $where[] = $methods->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
                    $methods->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Method of discovery information updated!');
                    $this->redirect($this->_redirectUrl . 'methods');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $methods = new DiscoMethods();
                    $method = $methods->fetchRow('id=' . $id);
                    if (count($method)) {
                        $form->populate($method->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a method of discovery
     * @access public
     * @return void
     */
    public function deletemethodAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $methods = new DiscoMethods();
                $where = 'id = ' . $id;
                $methods->delete($where);
            }
            $this->redirect($this->_redirectUrl . 'methods');
            $this->getFlash()->addMessage(self::DELETED);
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $methods = new DiscoMethods();
                $this->view->method = $methods->fetchRow('id=' . $id);
            }
        }
    }

    /** Add a method of discovery
     * @access public
     * @return void
     */
    public function addmethodAction()
    {
        $form = new DiscoMethodsForm();
        $form->submit->setLabel('Add a new discovery method');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $methods = new DiscoMethods();
                $methods->insert($form->getValues());
                $this->redirect($this->_redirectUrl . 'methods');
                $this->getFlash()->addMessage('Method of discovery created!');
            } else {
                $this->getFlash()->addMessage('Please correct errors');
                $form->populate($form->getValues());
            }
        }
    }

    /** List decorative methods
     * @access public
     * @return void
     */
    public function decorationmethodsAction()
    {
        $decs = new DecMethods();
        $this->view->decs = $decs->getDecorationDetailsListAdmin();
    }

    /** Add a decorative method
     * @access public
     * @return void
     */
    public function adddecorationmethodAction()
    {
        $form = new DecMethodsForm();
        $form->submit->setLabel('Add a new decoration method');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $decs = new DecMethods();
                $decs->add($form->getValues());
                $this->redirect($this->_redirectUrl . 'decorationmethods');
                $this->getFlash()->addMessage('A new decoration method has been created on the system!');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a decorative method.
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editdecorationmethodAction()
    {
        if ($this->getParam('id', false)) {
            $form = new DecMethodsForm();
            $form->submit->setLabel(self::UPDATE);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $decs = new DecMethods();
                    $where = array();
                    $where[] = $decs->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
                    $decs->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Decoration method information updated!');
                    $this->redirect($this->_redirectUrl . 'decorationmethods');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $decs = new DecMethods();
                    $dec = $decs->fetchRow('id=' . $id);
                    if (count($decs)) {
                        $form->populate($dec->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a decorative method
     * @access public
     * @return void
     */
    public function deletedecorationmethodAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $decs = new DecMethods();
                $where = 'id = ' . $id;
                $decs->delete($where);
            }
            $this->getFlash()->addMessage(self::DELETED);
            $this->redirect($this->_redirectUrl . 'decorationmethods');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $decs = new DecMethods();
                $this->view->dec = $decs->fetchRow('id=' . $id);
            }
        }
    }

    /** List surface treatments
     * @access public
     * @return void
     */
    public function surfacesAction()
    {
        $surfaces = new SurfaceTreatments();
        $this->view->surfaces = $surfaces->getSurfaceTreatmentsAdmin();
    }

    /** Add a surface treatment
     * @access public
     * @return void
     */
    public function addsurfaceAction()
    {
        $form = new SurfaceTreatmentsForm();
        $form->submit->setLabel('Add new surface treatment');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $surfaces = new SurfaceTreatments();
                $surfaces->add($form->getValues());
                $this->getFlash()->addMessage('A new surface treatment has been created on the system!');
                $this->redirect($this->_redirectUrl . 'surfaces');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a surface treatment
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editsurfaceAction()
    {
        if ($this->getParam('id', false)) {
            $form = new SurfaceTreatmentsForm();
            $form->submit->setLabel(self::UPDATE);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $surfaces = new SurfaceTreatments();
                    $where = array();
                    $where[] = $surfaces->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                    $surfaces->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Surface treatment information updated!');
                    $this->redirect($this->_redirectUrl . 'surfaces/');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $surfaces = new SurfaceTreatments();
                    $surface = $surfaces->fetchRow('id=' . $id);
                    if (count($surface)) {
                        $form->populate($surface->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a surface treatment
     * @access public
     * @return void
     */
    public function deletesurfaceAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $surfaces = new SurfaceTreatments();
                $where = 'id = ' . $id;
                $surfaces->delete($where);
            }
            $this->getFlash()->addMessage(self::DELETED);
            $this->redirect($this->_redirectUrl . 'surfaces');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $surfaces = new SurfaceTreatments();
                $this->view->surface = $surfaces->fetchRow('id=' . $id);
            }
        }
    }

    /** List periods in use
     * @access public
     * @return void
     */
    public function periodsAction()
    {
        $periods = new Periods();
        $this->view->periods = $periods->getPeriodsAll();
    }


    /** Edit a specific period
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editperiodAction()
    {
        if ($this->getParam('id', false)) {
            $form = new PeriodForm();
            $form->submit->setLabel(self::UPDATE);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $periods = new Periods();
                    $where = array();
                    $where[] = $periods->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                    $periods->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Period information updated');
                    $this->redirect($this->_redirectUrl . 'periods');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $periods = new Periods();
                    $period = $periods->fetchRow('id =' . $id);
                    if (count($period)) {
                        $form->populate($period->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a period
     * @access public
     * @return void
     */
    public function deleteperiodAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $periods = new Periods();
                $where = 'id = ' . $id;
                $periods->delete($where);
            }
            $this->getFlash()->addMessage(self::DELETED);
            $this->redirect($this->_redirectUrl . 'periods/');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $periods = new Periods();
                $this->view->period = $periods->fetchRow('id=' . $id);
            }
        }
    }

    /** Add a new period - won't be used much!
     * @access public
     * @return void
     */
    public function addperiodAction()
    {
        $form = new PeriodForm();
        $form->submit->setLabel('Add a new period');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $periods = new Periods();
                $periods->add($form->getValues());
                $this->getFlash()->addMessage('Record created!');
                $this->redirect($this->_redirectUrl . 'periods');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** List ascribed cultures
     * @access public
     * @return void
     */
    public function culturesAction()
    {
        $cultures = new Cultures();
        $this->view->cultures = $cultures->getCulturesListAdmin();
    }

    /** Add an ascribed culture
     * @access public
     * @return void
     */
    public function addcultureAction()
    {
        $form = new CultureForm();
        $form->details->setLegend('Ascribed Culture details: ');
        $form->submit->setLabel('Add new culture');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $cultures = new Cultures();
                $cultures->add($form->getValues());
                $this->redirect($this->_redirectUrl . 'cultures');
                $this->getFlash()->addMessage('A culture created!');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit an ascribed culture
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editcultureAction()
    {
        if ($this->getParam('id', false)) {
            $this->view->headTitle("Edit an ascribed culture ");
            $form = new CultureForm();
            $form->details->setLegend('Edit an ascribed culture');
            $form->submit->setLabel('Update details on database...');
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $cultures = new Cultures();
                    $where = array();
                    $where[] = $cultures->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                    $cultures->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Culture updated!');
                    $this->redirect($this->_redirectUrl . 'cultures');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $cultures = new Cultures();
                    $culture = $cultures->fetchRow('id = ' . $this->getParam('id'));
                    if (count($culture) != null) {
                        $form->populate($culture->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete an ascribed culture
     * @access public
     * @return void
     */
    public function deletecultureAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $cultures = new Cultures();
                $where = 'id = ' . $id;
                $cultures->delete($where);
            }
            $this->getFlash()->addMessage(self::DELETED);
            $this->redirect($this->_redirectUrl . 'cultures');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $cultures = new Cultures();
                $this->view->culture = $cultures->fetchRow('id=' . $id);
            }
        }
    }

    /** List workflows in use
     * @access public
     * @return void
     */
    public function workflowsAction()
    {
        $workflows = new Workflows();
        $this->view->workflows = $workflows->getStageNamesAdmin();
    }

    /** Add a new workflow stage
     */
    public function addworkflowAction()
    {
        $form = new WorkflowForm();
        $form->submit->setLabel('Add a new workflow stage to the system...');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $workflows = new Workflows();
                $workflows->add($form->getValues());
                $this->redirect($this->_redirectUrl . 'workflows');
                $this->getFlash()->addMessage('New work flow created');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a workflow stage
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editworkflowAction()
    {
        if ($this->getParam('id', false)) {
            $form = new WorkflowForm();
            $form->submit->setLabel(self::UPDATE);
            $this->view->form = $form;

            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $workflows = new Workflows();
                    $where = array();
                    $where[] = $workflows->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
                    $workflows->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Workflow updated');
                    $this->redirect($this->_redirectUrl . 'workflows');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $workflows = new Workflows();
                    $workflow = $workflows->fetchRow('id=' . $id);
                    if (count($workflow)) {
                        $form->populate($workflow->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a workflow stage
     * @access public
     * @return void
     */
    public function deleteworkflowAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $workflows = new Workflows();
                $where = 'id = ' . $id;
                $workflows->delete($where);
            }
            $this->getFlash()->addMessage(self::DELETED);
            $this->redirect($this->_redirectUrl . 'workflows');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $workflows = new Workflows();
                $this->view->workflow = $workflows->fetchRow('id=' . $id);
            }
        }
    }

    /** List preservation states
     * @access public
     * @return void
     */
    public function preservationsAction()
    {
        $preserves = new Preservations();
        $this->view->preserves = $preserves->getPreservationTermsAdmin();
    }

    /** Add a new preservation state
     * @access public
     * @return void
     */
    public function addpreservationAction()
    {
        $form = new PreservationsForm();
        $form->submit->setLabel('Add a new discovery method');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $preserves = new Preservations();
                $preserves->add($form->getValues());
                $this->redirect($this->_redirectUrl . 'preservations');
                $this->getFlash()->addMessage('Preservation state created!');
            } else {
                $this->getFlash()->addMessage('Please correct errors');
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a preservation state
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editpreservationAction()
    {
        if ($this->getParam('id', false)) {
            $form = new PreservationsForm();
            $form->submit->setLabel(self::UPDATE);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $preserves = new Preservations();
                    $where = array();
                    $where[] = $preserves->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
                    $preserves->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Preservation state information updated!');
                    $this->redirect($this->_redirectUrl . 'preservations');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $preserves = new Preservations();
                    $preserve = $preserves->fetchRow('id=' . $id);
                    if (count($preserve)) {
                        $form->populate($preserve->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a preservation state
     * @access public
     * @return void
     */
    public function deletepreservationAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $preserves = new Preservations();
                $where = 'id = ' . $id;
                $preserves->delete($where);
            }
            $this->getFlash()->addMessage(self::DELETED);
            $this->redirect($this->_redirectUrl . 'preservations');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $preserves = new Preservations();
                $this->view->preserve = $preserves->fetchRow('id=' . $id);
            }
        }
    }

    /** List the types of grid reference origins
     * @access public
     * @return void
     */
    public function maporiginsAction()
    {
        $origins = new MapOrigins();
        $this->view->origins = $origins->getOrigins();
    }

    /** Add a map origin statement
     * @access public
     * @return void
     */
    public function addmaporiginAction()
    {
        $form = new OriginForm();
        $form->details->setLegend('Grid reference origin details: ');
        $form->submit->setLabel('Add a grid reference origin term.');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $origins = new MapOrigins();
                $origins->add($form->getValues());
                $this->getFlash()->addMessage('A new grid reference origin has been entered');
                $this->redirect($this->_redirectUrl . 'maporigins');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a map origin statement
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editmaporiginAction()
    {
        if ($this->getParam('id', false)) {
            $form = new OriginForm();
            $form->details->setLegend('Edit an origin term');
            $form->submit->setLabel(self::UPDATE);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $origins = new MapOrigins();
                    $where = array();
                    $where[] = $origins->getAdapter()->quoteInto('id = ?', $this->getParam('id'));
                    $origins->update($form->getValues(), $where);
                    $this->redirect($this->_redirectUrl . 'maporigins');
                    $this->getFlash()->addMessage('Grid reference origin updated!');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $origins = new MapOrigins();
                    $origin = $origins->fetchRow('id = ' . $this->getParam('id'));
                    if (count($origin) != null) {
                        $form->populate($origin->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParamter);
        }
    }

    /** Delete a map origin statement
     * @access public
     * @return void
     */
    public function deletemaporiginAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $origins = new MapOrigins();
                $where = 'id = ' . $id;
                $origins->delete($where);
            }
            $this->redirect($this->_redirectUrl . 'maporigins/');
            $this->getFlash()->addMessage(self::DELETED);
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $origins = new MapOrigins();
                $this->view->origin = $origins->fetchRow('id =' . $id);
            }
        }
    }

    /** List finds of note methods
     * @access public
     * @return void
     */
    public function notesAction()
    {
        $notes = new Findofnotereasons();
        $this->view->notes = $notes->getReasonsListAdmin();
    }

    /** Add a find of note reasoning
     * @access public
     * @return void
     */
    public function addnoteAction()
    {
        $form = new FindNoteReasonForm();
        $form->submit->setLabel('Add a new reason');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $notes = new Findofnotereasons();
                $notes->add($form->getValues());
                $this->redirect($this->_redirectUrl . 'notes');
                $this->getFlash()->addMessage('Preservation state created!');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit find of note statement
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editnoteAction()
    {
        if ($this->getParam('id', false)) {
            $form = new FindNoteReasonForm();
            $form->submit->setLabel(self::UPDATE);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $notes = new Findofnotereasons();
                    $where = array();
                    $where[] = $notes->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
                    $notes->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Find of note reason updated!');
                    $this->redirect($this->_redirectUrl . 'notes');
                } else {
                    $form->populate($this->_request->getPost());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $notes = new Findofnotereasons();
                    $note = $notes->fetchRow('id=' . $id);
                    if (count($note)) {
                        $form->populate($note->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParamter);
        }
    }

    /** Delete a find of note statement
     * @access public
     * @return void
     */
    public function deletenoteAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $notes = new Findofnotereasons();
                $where = 'id = ' . $id;
                $notes->delete($where);
                $this->getFlash()->addMessage(self::DELETED);
            }
            $this->redirect($this->_redirectUrl . 'notes');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $notes = new Findofnotereasons();
                $this->view->note = $notes->fetchRow('id=' . $id);
            }
        }
    }

    /** List primary materials
     * @access public
     * @return void
     */
    public function materialsAction()
    {
        $materials = new Materials();
        $this->view->materials = $materials->getMaterialsAdmin($this->getParam('page'));
    }

    /** Add a new primary material
     * @access public
     * @return void
     */
    public function addmaterialAction()
    {
        $form = new MaterialForm();
        $form->submit->setLabel('Add a new material');
        $this->view->form = $form;
        if ( $this->getRequest()->isPost() ) {
            if ( $form->isValid($this->_request->getPost()) ) {
                $materials = new Materials();
                $materials->add($form->getValues());
                $this->redirect($this->_redirectUrl . 'materials');
                $this->getFlash()->addMessage('A new material has been created on the system!');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a material type
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editmaterialAction()
    {
        if ($this->getParam('id', false)) {
            $form = new MaterialForm();
            $form->submit->setLabel(self::UPDATE);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $materials = new Materials();
                    $where = array();
                    $where[] = $materials->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
                    $materials->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Material information updated!');
                    $this->redirect($this->_redirectUrl . 'materials');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $materials = new Materials();
                    $material = $materials->fetchRow('id=' . $id);
                    if (count($material)) {
                        $form->populate($material->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a material
     * @access public
     * @return void
     */
    public function deletematerialAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $materials = new Materials();
                $where = 'id = ' . $id;
                $materials->delete($where);
            }
            $this->getFlash()->addMessage(self::DELETED);
            $this->redirect($this->_redirectUrl . 'materials');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $materials = new Materials();
                $this->view->material = $materials->fetchRow('id=' . $id);
            }
        }
    }

    /** List decorative styles
     * @access public
     * @return void
     */
    public function decorationstylesAction()
    {
        $decs = new DecStyles();
        $this->view->decs = $decs->getDecStylesAdmin();
    }

    /** Add a decorative style
     * @access public
     * @return void
     */
    public function adddecorationstyleAction()
    {
        $form = new DecStylesForm();
        $form->submit->setLabel('Add a new term');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $decs = new DecStyles();
                $decs->add($form->getValues());
                $this->redirect($this->_redirectUrl . 'decorationstyles');
                $this->getFlash()->addMessage('A new decoration style has been created on the system!');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a decorative style
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     * @throws Exception
     */
    public function editdecorationstyleAction()
    {
        if ($this->getParam('id', false)) {
            $form = new DecStylesForm();
            $form->submit->setLabel(self::UPDATE);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $decs = new DecStyles();
                    $where = array();
                    $where[] = $decs->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
                    $decs->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Decoration style information updated!');
                    $this->redirect($this->_redirectUrl . 'decorationstyles');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $decs = new DecStyles();
                    $dec = $decs->fetchRow('id=' . $id);
                    if (count($dec)) {
                        $form->populate($dec->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Exception($this->_missingParameter);
        }
    }

    /** Delete a decorative style
     * @access public
     * @return void
     */
    public function deletedecorationstyleAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $decs = new DecStyles();
                $where = 'id = ' . $id;
                $decs->delete($where);
            }
            $this->getFlash()->addMessage(self::DELETED);
            $this->redirect($this->_redirectUrl . 'decorationstyles');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $decs = new DecStyles();
                $this->view->dec = $decs->fetchRow('id=' . $id);
            }
        }
    }

    /** List manufacture methods
     * @access public
     * @return void
     */
    public function manufacturesAction()
    {
        $manufactures = new Manufactures();
        $this->view->manufactures = $manufactures->getManufacturesListedAdmin();
    }

    /** Add a manufacture methods
     * @access public
     * @return void
     */
    public function addmanufactureAction()
    {
        $form = new ManufacturesForm();
        $form->submit->setLabel('Add a new method');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $manufactures = new Manufactures();
                $manufactures->add($form->getValues());
                $this->redirect($this->_redirectUrl . 'manufactures');
                $this->getFlash()->addMessage('A new manufacturing method has been created on the system!');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a manufacture method
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     * @throws Exception
     */
    public function editmanufactureAction()
    {
        if ($this->getParam('id', false)) {
            $form = new ManufacturesForm();
            $form->submit->setLabel(self::UPDATE);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $manufactures = new Manufactures();
                    $where = array();
                    $where[] = $manufactures->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
                    $manufactures->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Manufacture information updated!');
                    $this->redirect($this->_redirectUrl . 'manufactures');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $manufactures = new Manufactures();
                    $manufacture = $manufactures->fetchRow('id=' . $id);
                    if (count($manufacture)) {
                        $form->populate($manufacture->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Exception($this->_missingParameter);
        }
    }

    /** Delete a manufacture method
     * @access public
     * @return void
     */
    public function deletemanufactureAction()
    {
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $manufactures = new Manufactures();
                $where = 'id = ' . $id;
                $manufactures->delete($where);
            }
            $this->getFlash()->addMessage(self::DELETED);
            $this->redirect($this->_redirectUrl . 'manufactures');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $manufactures = new Manufactures();
                $this->view->manufacture = $manufactures->fetchRow('id=' . $id);
            }
        }
    }

    /** List landuses
     * @access public
     * @return void
     */
    public function landusesAction()
    {
        $landuses = new Landuses();
        $this->view->landuses = $landuses->getLandusesAdmin();
    }

    /** Add a new landuse
     * @access public
     * @return void
     */
    public function addlanduseAction()
    {
        $form = new LanduseForm();
        $form->details->setLegend('Add landuse');
        $form->submit->setLabel('Add a new landuse');
        $this->view->form = $form;
        if ($this->getRequest()->isPost()) {
            if ($form->isValid($this->_request->getPost())) {
                $landuses = new Landuses();
                $landuses->add($form->getValues());
                $this->redirect($this->_redirectUrl . 'landuses');
                $this->getFlash()->addMessage('A new landuse has been created on the system!');
            } else {
                $form->populate($form->getValues());
            }
        }
    }

    /** Edit a landuse
     * @access public
     * @return void
     * @throws Pas_Exception_Param
     */
    public function editlanduseAction()
    {
        if ($this->getParam('id', false)) {
            $form = new LanduseForm();
            $form->details->setLegend('Edit landuse');
            $form->submit->setLabel(self::UPDATE);
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                if ($form->isValid($this->_request->getPost())) {
                    $landuses = new Landuses();
                    $where = array();
                    $where[] = $landuses->getAdapter()->quoteInto('id = ?', (int)$this->getParam('id'));
                    $landuses->update($form->getValues(), $where);
                    $this->getFlash()->addMessage('Active landuse information updated!');
                    $this->redirect($this->_redirectUrl . 'landuses');
                } else {
                    $form->populate($form->getValues());
                }
            } else {
                $id = (int)$this->_request->getParam('id', 0);
                if ($id > 0) {
                    $landuses = new Landuses();
                    $landuse = $landuses->fetchRow('id=' . $id);
                    if (count($landuse)) {
                        $form->populate($landuse->toArray());
                    } else {
                        throw new Pas_Exception_Param($this->_nothingFound);
                    }
                }
            }
        } else {
            throw new Pas_Exception_Param($this->_missingParameter, 500);
        }
    }

    /** Delete a landuse
     * @access public
     * @return void
     */
    public function deletelanduseAction()
    {
        $this->getFlash()->addMessage($this->_noChange);
        if ($this->_request->isPost()) {
            $id = (int)$this->_request->getPost('id');
            $del = $this->_request->getPost('del');
            if ($del == 'Yes' && $id > 0) {
                $landuses = new Landuses();
                $where = 'id = ' . $id;
                $landuses->delete($where);
            }
            $this->getFlash()->addMessage(self::DELETED);
            $this->redirect($this->_redirectUrl . 'landuses');
        } else {
            $id = (int)$this->_request->getParam('id');
            if ($id > 0) {
                $landuses = new Landuses();
                $this->view->landuse = $landuses->fetchRow('id=' . $id);
            }
        }
    }
}