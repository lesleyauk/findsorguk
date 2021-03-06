<?php

/**
 * A view helper for rendering coin data
 *
 * This view helper is used for displaying coin data on a page based on the
 * numismatic or object type.
 *
 * To use this:
 * <code>
 * <?php
 * echo $this->coinDataDisplay()
 * ->setObjectType($this->finds['0']['objecttype'])
 * ->setBroadperiod($this->finds['0']['broadperiod'])
 * ->setCoins($this->coins)
 * ->setFinds($this->finds);
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @uses Zend_View_Helper_PartialLoop
 * @uses Pas_View_Helper_AddCoinLink
 * @uses Pas_View_Helper_AddJettonLink
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @since 1
 * @copyright (c) 2014, Daniel Pett
 * @category Pas
 * @package View
 * @subpackage Helper
 */
class Pas_View_Helper_CoinDataDisplay extends Zend_View_Helper_Abstract
{

    /** The array of numismatic types
     * @access protected
     * @var array
     */
    protected $_numismatics = array('COIN', 'MEDALLION');

    /** The array of object types
     * @access protected
     * @var array
     */
    protected $_objects = array('JETTON', 'TOKEN');

    /** The array of broadperiods
     * @access protected
     * @var array
     */
    protected $_broadperiods = array(
        'IRON AGE', 'ROMAN', 'BYZANTINE',
        'EARLY MEDIEVAL', 'GREEK AND ROMAN PROVINCIAL', 'MEDIEVAL',
        'POST MEDIEVAL', 'MODERN', 'UNKNOWN'
    );

    /** The object type
     * @access protected
     * @var string
     */
    protected $_objectType;

    /** The broadperiod
     * @access protected
     * @var string
     */
    protected $_broadperiod;

    /** The coins array drawn from the model
     * @access protected
     * @var array
     */
    protected $_coins;

    /** The finds array drawn from the model
     * @access protected
     * @var array
     */
    protected $_finds;

    /** The types array
     * @access protected
     * @var array
     */
    protected $_types;

    /** Get the numismatic array
     * @access public
     * @return array
     */
    public function getNumismatics()
    {
        return $this->_numismatics;
    }

    /** Get the objects array
     * @access public
     * @return array
     */
    public function getObjects()
    {
        return $this->_objects;
    }

    /** Get the broadperiods allowed
     * @access public
     * @return array
     */
    public function getBroadperiods()
    {
        return $this->_broadperiods;
    }

    /** Get the object type
     * @access public
     * @return string
     */
    public function getObjectType()
    {
        return $this->_objectType;
    }

    /** Get the broadperiod
     * @access public
     * @return string
     */
    public function getBroadperiod()
    {
        return $this->_broadperiod;
    }

    /** Get the coin array
     * @access public
     * @return array
     */
    public function getCoins()
    {
        return $this->_coins;
    }

    /** Get the finds array
     * @access public
     * @return array
     */
    public function getFinds()
    {
        return $this->_finds;
    }

    /** Set the numismatics array
     * @access public
     * @param array $numismatics
     * @return \Pas_View_Helper_CoinDataDisplay
     */
    public function setNumismatics($numismatics = array())
    {
        $this->_numismatics = $numismatics;
        return $this;
    }

    /** Set the objects array
     * @access public
     * @param array $objects
     * @return \Pas_View_Helper_CoinDataDisplay
     */
    public function setObjects($objects = array())
    {
        $this->_objects = $objects;
        return $this;
    }

    /** Set the array of broadperiods
     * @access public
     * @param type $broadperiods
     * @return \Pas_View_Helper_CoinDataDisplay
     */
    public function setBroadperiods($broadperiods = array())
    {
        $this->_broadperiods = $broadperiods;
        return $this;
    }

    /** Set the object type
     * @access public
     * @param string $objectType
     * @return \Pas_View_Helper_CoinDataDisplay
     */
    public function setObjectType($objectType)
    {
        $this->_objectType = $objectType;
        return $this;
    }

    /** Get the types
     * This function merges the two arrays of numismatica and objects
     *
     * @access public
     * @return array
     */
    public function getTypes()
    {
        $this->_types = array_merge($this->getNumismatics(),
            $this->getObjects());
        return $this->_types;
    }

    /** Set the types
     * @access public
     * @param array $types
     * @return \Pas_View_Helper_CoinDataDisplay
     */
    public function setTypes($types)
    {
        $this->_types = $types;
        return $this;
    }

    /** Set the broadperiod to query
     * @access public
     * @param string $broadperiod
     * @return \Pas_View_Helper_CoinDataDisplay
     */
    public function setBroadperiod($broadperiod)
    {
        $this->_broadperiod = $broadperiod;
        return $this;
    }

    /** Set the coins array
     * @access public
     * @param array $coins
     * @return \Pas_View_Helper_CoinDataDisplay
     */
    public function setCoins($coins = array())
    {
        $this->_coins = $coins;
        return $this;
    }

    /** Set the finds array
     * @access public
     * @param array $finds
     * @return \Pas_View_Helper_CoinDataDisplay
     */
    public function setFinds($finds = array())
    {
        $this->_finds = $finds;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_CoinDataDisplay
     */
    public function coinDataDisplay()
    {
        return $this;
    }

    /** To string function
     * @access public
     * @return string
     */
    public function __toString()
    {
        return $this->buildHtml();
    }

    /** Coin data
     * @var
     */
    protected $_data;

    /** Set the coin data
     * @access public
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->_data = $data;
        return $this;
    }

    /** Get the coin data
     * @access public
     */
    public function getData()
    {
        return $this->_data;
    }
    /** Build the html
     * @access public
     * @return string
     */
    public function buildHtml()
    {
        $html = '';
        $finds = $this->getFinds();
        $data = $this->getData();
        if (in_array(strtoupper($this->getObjectType()), $this->getTypes())) {
            if (!empty($data)) {
                if (in_array(strtoupper($this->getBroadperiod()), $this->getBroadperiods())) {
                    if (in_array(strtoupper($this->getObjectType()), $this->getNumismatics())) {
                        $template = str_replace(' ', '', $this->getBroadperiod());
                        $partial = 'partials/database/numismatics/' . strtolower($template) . 'Data.phtml';
                        $html .= $this->view->partialLoop($partial, $this->getCoins());
                    } elseif (in_array(strtoupper($this->getObjectType()),
                        $this->getObjects())) {
                        $partial = 'partials/database/numismatics/jettonData.phtml';
                        $html .= $this->view->partialLoop($partial, $this->getCoins());
                    } else {
                        $html .= '';
                    }

                } else {
                    $html .= '<h4 class="lead">An error has been detected</h4>';
                    $html .= 'You can either not have a coin of that period, or we are';
                    $html .= ' not set up for that period.';
                }
            } else {
                $html .= '<div>';
                $html .= '<h4 class="lead">Numismatic data</h4>';
                $html .= '<p>No numismatic data has been recorded for this coin yet.</p>';
                $html .= '<div class="noprint">';
                if (in_array(strtoupper($this->getObjectType()), $this->getNumismatics())) {
                    $html .= $this->view->addCoinLink()
                        ->setFindID((int)$finds[0]['id'])
                        ->setSecUid($finds[0]['secuid'])
                        ->setCreatedBy((int)$finds[0]['createdBy'])
                        ->setBroadperiod($finds[0]['broadperiod'])
                        ->setInstitution($finds[0]['institution']);
                } elseif (in_array(strtoupper($this->getObjectType()), $this->getObjects())) {
                    $html .= $this->view->addJettonLink()
                        ->setFindID((int)$finds[0]['id'])
                        ->setSecUid($finds[0]['secuid'])
                        ->setCreatedBy((int)$finds[0]['createdBy'])
                        ->setBroadperiod($finds[0]['broadperiod'])
                        ->setInstitution($finds[0]['institution']);
                }

                $html .= '</div></div>';
            }
        }
        return $html;
    }
}
