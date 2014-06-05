<?php
/**
 * A view helper for truncating text and adding an ellipsis at the end
 * 
 * Example of use:
 * 
 * <code>
 * <?php
 * echo $this->ellipsisString()->setString($string)->setMax(200);
 * ?>
 * </code>
 * 
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_View_Helper
 * @subpackage Abstract
 * @copyright  Copyright (c) 2011 dpett @ britishmuseum.org
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @see Zend_View_Helper_Abstract
 * @todo Replace all uses of this with the truncate helper
 */
class Pas_View_Helper_EllipsisString extends Zend_View_Helper_Abstract {
    
    /** The String to truncate
     *@access protected
     * @var string
     */
    protected $_string;
    
    /** The maximum number of characters
     * @access protected
     * @var int
     */
    protected $_max = 300;
    
    /** Replace end of string with this character
     * @access protected
     * @var string
     */
    protected $_replace = '&hellip;';
    
    /** Get the string
     * @access public
     * @return string
     */
    public function getString() {
        return $this->_string;
    }

    /** Get the max length
     * @access public
     * @return int
     */
    public function getMax() {
        return $this->_max;
    }

    /** Get the replace character
     * @access public
     * @return string
     */
    public function getReplace() {
        return $this->_replace;
    }

    /** Set the string
     * @access public
     * @param string $string
     * @return \Pas_View_Helper_Ellipsisstring
     */
    public function setString($string) {
        $this->_string = $string;
        return $this;
    }

    /** Set the max length
     * @access public
     * @param int $max
     * @return \Pas_View_Helper_Ellipsisstring
     */
    public function setMax($max) {
        $this->_max = $max;
        return $this;
    }

    /** Set the replace string
     * @access public
     * @param string $replace
     * @return \Pas_View_Helper_Ellipsisstring
     */
    public function setReplace($replace) {
        $this->_replace = $replace;
        return $this;
    }

    /** The function to return
     * @access public
     * @return \Pas_View_Helper_Ellipsisstring
     */
    public function ellipsisString() {
        return $this;
    }    
    
    /** The to string function
     * @access public
     * @return string
     */
    public function __toString() {
        $string = '';
        if (strlen($this->getString()) < $this->getMax()) {
            $string .= $this->getString();
        } else {
            $leave = $this->getMax() - strlen ($this->getReplace());
            $string .= strip_tags(substr_replace($this->getString(), 
                    $this->getReplace(), $leave),'<br><a><em>');
        }
        return $string;
    }
}