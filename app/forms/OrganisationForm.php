<?php

/** Form for creating and editing organisational data
 *
 * An example of code use:
 *
 * <code>
 * <?php
 * $form = new OrganisationForm();
 * $form->submit->setLabel('Update organisation');
 * ?>
 * </code>
 * @author Daniel Pett <dpett at britishmuseum.org>
 * @category   Pas
 * @package    Pas_Form
 * @copyright  Copyright (c) 2011 DEJ Pett dpett @ britishmuseum . org
 * @license http://www.gnu.org/licenses/agpl-3.0.txt GNU Affero GPL v3.0
 * @version 1
 * @example /app/modules/database/controllers/OrganisationsController.php
 * @uses Countries
 * @uses OsCounties
 * @uses People
 */
class OrganisationForm extends Pas_Form
{

    /** The constructor
     * @access public
     * @param array $options
     * @return void
     */
    public function __construct(array $options = null)
    {

        $countries = new Countries();
        $countries_options = $countries->getOptions();

        $counties = new OsCounties();
        $counties_options = $counties->getCountiesID();

        parent::__construct($options);

        $this->setName('organisation');

        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Organisation name: ')
            ->setRequired(true)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('size', 60)
            ->addErrorMessage('Please enter an organisation name')
            ->addValidator('Alnum', false, array('allowWhiteSpace' => true));

        $website = new Zend_Form_Element_Text('website');
        $website->setLabel('Organisation website: ')
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addValidator(new Pas_Validate_Url())
            ->addErrorMessage('Please enter a valid URL')
            ->setAttrib('size', 60);

        $address1 = new Zend_Form_Element_Text('address1');
        $address1->setLabel('Address line one: ')
            ->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
            ->setAttrib('size', 200);

        $address2 = new Zend_Form_Element_Text('address2');
        $address2->setLabel('Address line two: ')
            ->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
            ->setAttrib('size', 200);

        $address3 = new Zend_Form_Element_Text('address3');
        $address3->setLabel('Address line three: ')
            ->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
            ->setAttrib('size', 200);

        $address = new Zend_Form_Element_Text('address');
        $address->setLabel('Full address: ')
            ->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
            ->setAttrib('size', 200);

        $town_city = new Zend_Form_Element_Text('town_city');
        $town_city->setLabel('Town or city: ')
            ->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
            ->setAttrib('size', 60);

        $county = new Zend_Form_Element_Select('county');
        $county->setLabel('County: ')
            ->setRequired(true)
            ->addFilters(array('StripTags', 'StringTrim'))
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->addMultiOptions(array(null => 'Please choose a county',
                'Valid counties' => $counties_options))
            ->addValidator('InArray', false, array(array_keys($counties_options)));

        $country = new Zend_Form_Element_Select('country');
        $country->SetLabel('Country: ')
            ->setRequired(true)
            ->setAttrib('class', 'input-xxlarge selectpicker show-menu-arrow')
            ->setValue('GB')
            ->addFilters(array('StripTags', 'StringTrim'))
            ->addMultiOptions(array(null => 'Please choose a country',
                'Valid countries' => $countries_options))
            ->addValidator('InArray', false, array(array_keys($countries_options)));

        $postcode = new Zend_Form_Element_Text('postcode');
        $postcode->setLabel('Postcode: ')
            ->setRequired(true)
            ->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
            ->addValidator('StringLength', false, array(1, 10))
            ->addValidator('ValidPostCode')
            ->addErrorMessage('Please enter a valid postcode')
            ->setAttrib('size', 10);

        $contactperson = new Zend_Form_Element_Text('contact');
        $contactperson->setLabel('Lead contact: ')
            ->addFilters(array('StripTags', 'StringTrim', 'Purifier'))
            ->addValidator('StringLength', false, array(1, 200))
            ->setAttrib('size', 50);

        $contactpersonID = new Zend_Form_Element_Hidden('contactpersonID');
        $contactpersonID->addFilters(array('StripTags', 'StringTrim'));

        $submit = $this->addElement('submit', 'submit', array('label' => 'Login...'));

        $this->addElements(array(
            $name, $website, $address1,
            $address2, $address3, $address,
            $town_city, $county, $country,
            $postcode, $contactperson, $contactpersonID,
            $submit
        ));

        $this->addDisplayGroup(array(
                'name', 'website', 'address1',
                'address2', 'address3', 'address',
                'town_city', 'county', 'country',
                'postcode', 'contact', 'contactpersonID'),
            'details');
        $this->details->setLegend('Organisation details: ');

        $this->addDisplayGroup(array('submit'), 'buttons');

        parent::init();

    }
}
