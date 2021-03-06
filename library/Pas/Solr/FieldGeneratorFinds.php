<?php

/** A solr class for generating which fields to return for different responses
 *
 * An example of use:
 * <code>
 * <?php
 *  $fields = new Pas_Solr_FieldGeneratorFinds($context);
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @copyright (c) 2014 Daniel Pett
 * @category Pas
 * @package Pas_Solr
 * @version 1
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @example /app/modules/api/controllers/ObjectsController.php
 *
 */
class Pas_Solr_FieldGeneratorFinds
{

    /** The cintext to query
     * @access protected
     * @var string
     */
    protected $_context;

    /** Get the context to serve
     * @access public
     * @return string
     */
    public function getContext()
    {
        return $this->_context;
    }

    /** Set the context to use
     * @access public
     * @param string $context
     * @return \Pas_Solr_FieldGeneratorFinds
     */
    public function setContext($context)
    {
        $this->_context = $context;
        return $this;
    }

    /** The contexts to send lots of fields to
     * @access protected
     * @var array
     */
    protected $_contexts = array('json', 'xml', 'kml', 'geojson');

    /** Set the contexts
     * @access public
     * @param array $contexts
     * @return \Pas_Solr_FieldGeneratorFinds
     */
    public function setContexts(array $contexts)
    {
        $this->_contexts = $contexts;
        return $this;
    }

    /** Get the contexts to use
     * @access public
     * @return type
     */
    public function getContexts()
    {
        return $this->_contexts;
    }

    /** Get the fields based on the context
     * @access public
     * @return string
     */
    public function getFields()
    {
        if (!in_array($this->getContext(), $this->getContexts())) {
            $fields = array(
                'id', 'identifier', 'objecttype',
                'title', 'broadperiod', 'description',
                'old_findID', 'thumbnail', 'county',
                'imagedir', 'filename', 'workflow',
                'fourFigure', 'knownas', 'created',
                'updated', 'creator', 'fourFigureLat',
                'fourFigureLon', 'findIdentifier');
        } else {
            $fields = array(
                'id', 'identifier', 'objecttype',
                'creator', 'broadperiod', 'fromdate',
                'todate', 'description', 'notes',
                'inscription', 'completenessTerm', 'discoveryMethod',
                'materialTerm', 'secondaryMaterialTerm', 'cultureName',
                'classification', 'subClassification', 'manufactureTerm',
                'old_findID', 'regionName', 'county',
                'district', 'parish', 'fourFigure',
                'knownas', 'imagedir', 'filename',
                'thumbnail', 'denominationName', 'mintName',
                'obverseDescription', 'reverseDescription', 'obverseLegend',
                'reverseLegend', 'rulerName', 'tribeName',
                'cciNumber', 'mintmark', 'categoryTerm',
                'typeTerm', ' axis', 'moneyerName', 'reverseType',
                'workflow', 'smrref', 'otherref',
                'TID', 'musaccno', 'created',
                'updated', 'weight', 'height',
                'width', 'diameter', 'thickness',
                'quantity', 'length', 'created',
                'fourFigureLat', 'fourFigureLon', 'datefound1',
                'datefound2', 'decstyleTerm', 'elevation',
                'precision', 'updatedBy', 'institution',
                'quantity', 'discovered', 'preservationTerm',
                'subsequentActionTerm', 'currentLocation', 'ruler',
                'ruler2', 'cciNumber', 'moneyerName',
                'moneyer', 'reeceID', 'identifier',
                'secondaryIdentifier', 'recorder', 'denomination',
                'pleiadesID', 'nomismaMintID', 'rulerNomisma',
                'rulerDbpedia', 'secuid', 'rulerViaf',
                'axis', 'denominationDbpedia', 'mint',
                'fourFigureLat', 'fourFigureLon', 'material',
                'abcType', 'vaType', 'woeid',
                'osmNode', 'accuracy', 'reverse',
                'allenType', 'tribe', 'mint',
                'periodFromName', 'periodFrom', 'periodTo',
                'periodToName', 'decstyleTerm', 'manufacture',
                'identifierID', 'regionID', 'parishID',
                'countyID', 'districtID', 'broadperiodEH',
                'broadperiodBM', 'periodFromEH', 'periodFromBM',
                'periodToEH', 'periodToBM', 'districtType',
                'parishType', 'countyType', 'mintBM', 'bmTribeID',
                'rulerDbpedia', 'rulerViaf', 'nomismaDenominationID',
                'bmCultureID', 'primaryMaterialBM', 'secondaryMaterialBM',
                'bmManufacture', 'bmTreatment', 'bmPreservation',
                'rulerBM', 'denominationBM', 'objectType',
                'findIdentifier'
            );
        }

        return $fields;
    }
}