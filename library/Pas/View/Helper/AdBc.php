<?php
/**
 * A view helper for turning dates into AD or BC calenderical dates
 * I think that Zend Date can do this as well....
 *
 * An example of how to use this:
 *
 * <code>
 * <?php
 * echo $this->adBc()->setDate(-200);
 * ?>
 * </code>
 *
 * @category    Pas
 * @package        View
 * @subpackage Helper
 * @copyright    Copyright (c) 2011 dpett @ britishmuseum.org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @see  Zend_View_Helper_Abstract
 * @author  Daniel Pett <dpett@britishmuseum.org>
 * @version 1
 * @since   26 September 2011
 * @uses Zend_Validate_Int
 */
class Pas_View_Helper_AdBc extends Zend_View_Helper_Abstract
{
    /** The prefix for dates
     * @access protected
     * @var string
     */
    protected $_prefix = 'AD';

    /** The suffix for dates
     * @access protected
     * @var string
     */
    protected $_suffix = 'BC';

    /** The validator to use
     * @access protected
     * @var object
     */
    protected $_validator;

    /** The date to check
     * @access protected
     * @var integer
     */
    protected $_date;

    /** Function for returning the correct date format
     * @access public
     * @return \Pas_View_Helper_AdBc
     */
    public function adBc()
    {
        return $this;
    }

    /** Magic method
     *
     * @return string
     */
    public function __toString()
    {
        try {
            return $this->html();
        } catch (Exception $e) {

        }
    }

    /** Function for returning html
     *
     * @return string
     */
    public function html()
    {
        $html = '';
        $date = $this->getDate();
        if ($this->_validate($date)) {
            if ($date < 0) {
                $html .= abs($date) . ' ' . $this->getSuffix();
            } elseif ($date > 0) {
                $html .= $this->getPrefix() . ' ' . abs($date);
            } elseif ($date == 0) {
                $html .= '';
            }
        }
        return $html;
    }

    /** Get the date to use in the class
     * @access public
     * @return string
     */
    public function getDate()
    {
        return $this->_date;
    }

    /** Set the date to use for the class
     * @access public
     * @param  int $date
     * @return \Pas_View_Helper_AdBc
     */
    public function setDate($date)
    {
        $this->_date = $date;
        return $this;
    }

    /** Validate the data
     * @access public
     * @param  int $date
     * @return int
     */
    public function _validate($date)
    {
        if ($this->getValidator()->isValid($date)) {
            return $date;
        }
    }

    /** Get the validator
     * @access public
     * @return object
     */
    public function getValidator()
    {
        $this->_validator = new Zend_Validate_Int();
        return $this->_validator;
    }

    /** Get the suffix
     * @access public
     * @return string
     */
    public function getSuffix()
    {
        return $this->_suffix;
    }

    /** Set a different suffix
     * @access public
     * @param  string $suffix
     * @return \Pas_View_Helper_AdBc
     */
    public function setSuffix($suffix)
    {
        $this->_suffix = $suffix;
        return $this;
    }

    /** Get the prefix
     * @access public
     * @return string
     */
    public function getPrefix()
    {
        return $this->_prefix;
    }

    /** set a different prefix if desired
     * @access public
     * @param  string $prefix
     * @return \Pas_View_Helper_AdBc
     */
    public function setPrefix($prefix)
    {
        $this->_prefix = $prefix;
        return $this;
    }
}
