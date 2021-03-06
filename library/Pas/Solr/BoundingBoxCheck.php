<?php

/**
 * BoundingBoxCheck takes parameters from a bounding box query string and
 * checks for validity, then returns the correct solr formatted query string.
 * Thanks for putting me on the right track to: James Grimster (OrangeLeaf),
 * Eric Kansa (UCLA - opencontext), Patrick Plaatje (Lovefilm), Jeremy Ottevanger
 * (Imperial War Museum)
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $coords = new Pas_Solr_BoundingBoxCheck($params['bbox']);
 * $bbox = $coords->checkCoordinates();
 * ?>
 * </code>
 *
 * @author Daniel Pett <dpett@britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Solr
 * @see http://code.google.com/p/opencontext/source/browse/trunk/library/OpenContext/FacetQuery.php
 * @see http://doofercall.blogspot.com/2012/01/solr-to-google-earth.html
 * @uses Pas_Solr_Exception
 * @version 1
 * @since 27/1/2011
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /library/Pas/Solr/ExportHandler.php
 */
class Pas_Solr_BoundingBoxCheck
{

    /** Number of elements needed in array
     *
     */
    const CORNERS = 4;

    /** Coordinate string to parse
     *
     * @var string
     */
    protected $_coordString;

    /** Construct the string to use
     *
     * @param string $string
     */
    public function __construct($string)
    {
        $this->_coordString = $string;
    }

    /** Check the comma delimited string for validity
     * @access public
     * @param string $string
     * @throws Pas_Solr_Exception
     */
    public function checkCoordinates()
    {
        if (!is_null($this->_coordString)) {
            return $this->_createArray($this->_coordString);
        } else {
            throw new Pas_Solr_Exception('No corner coordinates provided');
        }

    }

    /** Create the array for searching solr bounding box
     * @access protected
     * @param string $string
     * @return array $corner 4 key values for searching
     * @throws Pas_Solr_Exception
     */
    protected function _createArray()
    {
        //Explode the comma separated string into an array for checking
        $bbox = explode(',', $this->_coordString);
        //Count vertices
        if (count($bbox) === self::CORNERS) {
            //Validate the points
            foreach ($bbox as $corner) {
                if (!is_numeric($corner)) {
                    throw new Pas_Solr_Exception('Coordinate provided not numeric');
                } elseif ((abs($corner) > 180)) {
                    throw new Pas_Solr_Exception('Coordinate greater than 180 &deg;');
                }
            }
            //Check mathematics
            if ($bbox['0'] > $bbox['2']) {
                //This checks that the minimum latitude is smaller than maximum
                //latitude, if not throw exception
                throw new Pas_Solr_Exception('Minimum latitude greater than maximum');
            }

            if ($bbox['1'] > $bbox['3']) {
                //This checks that the minimum latitude is smaller than maximum
                ////latitude, if not throw exception
                throw new Pas_Solr_Exception('Minimum longitude greater than maximum');
            }

            // Return the string or throw exception
            //$bbox elements - 0 = minLat, 1 = minLon, 2 = maxLat, 3 = maxLon
            $solrQuery = 'coordinates:[' . $bbox['0'] . ',' . $bbox['1'] . ' TO '
                . $bbox['2'] . ',' . $bbox['3'] . ']';
            return $solrQuery;
        } else {
            throw new Pas_Solr_Exception('Invalid count of corners');
        }
    }
}