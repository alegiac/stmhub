<?php

namespace Application\Form;

use Zend\Form\Form;

class StudentSignup extends Form
{	
    private $extraFields;
    
    public function __construct($extraFields = false)
    {
        parent::__construct('signup_student');

        $this->extraFields = $extraFields;
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'firstname',
            'options' => array (
                'label' => 'Nome',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
	));
	
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'lastname',
            'required' => true,
            'options' => array (
                'label' => 'Cognome',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
	));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'email',
            'required' => true,
            'options' => array (
                'label' => 'E-Mail',
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
	));
        
        if ($this->extraFields === true) {

            $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'internal',
                'options' => array(
                    'label' => 'Sono interno a Ca\'Foscari',
                    'empty_option' => 'Seleziona',
                    'value_options' => array(
                        '1' => 'Si',
                        '2' => 'No'
                    ),
                ),
                'attributes' => array(
                    'value' => '0',
                    'class' => 'form-control'
                ),
            ));

            $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'role',
                'options' => array(
                    'label' => 'Il mio ruolo',
                    'empty_option' => 'Seleziona',
                    'value_options' => array(
                        '1' => 'Studente',
                        '2' => 'Dottorando',
                        '3' => 'Ricercatore',
                        '4' => 'Assegnista',
                        '5' => 'Prof. associato',
                        '6' => 'Prof. ordinario',
                        '7' => 'Personale tecnico amministrativo',
                        '8' => 'Altro'
                    ),
                ),
                'attributes' => array(
                    'value' => '0',
                    'class' => 'form-control'
                )
            ));
        }

        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'privacy_check',
            'options' => array (
                'use_hidden_element' => true,
                'checked_value' => 1,
                'unchecked_value' => 'no'
            ),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'subm',
            'attributes' => array(
                'value' => 'ACCEDI',
                'class' => "btn btn-primary btn-lg col-xs-8 col-xs-offset-2",
            )
        ));
        
        $this->setInputFilter($this->createInputFilters());
    }
    
    public function createInputFilters()
    {
        $inputFilter = new \Zend\InputFilter\InputFilter();

        $nameFilter = new \Zend\InputFilter\Input('firstname');
        $nameFilter->setRequired(true);
        $nameFilter->setErrorMessage("Questo campo è obbligatorio");
        $inputFilter->add($nameFilter);

        $lastnameFilter = new \Zend\InputFilter\Input('lastname');
        $lastnameFilter->setRequired(true);
        $lastnameFilter->setErrorMessage("Questo campo è obbligatorio");
        $inputFilter->add($lastnameFilter);
        
        $emailFilter = new \Zend\InputFilter\Input('email');
        $emailFilter->setRequired(true);
        $emailFilter->setErrorMessage("Questo campo deve contenere un indirizzo email valido");
        $emailFilter->getValidatorChain()->attach(new \Zend\Validator\EmailAddress());
        $inputFilter->add($emailFilter);
        
        $checkboxFilter = new \Zend\InputFilter\Input('privacy_check');
        $checkboxFilter->setRequired(true);
        $checkboxFilter->setErrorMessage("Devi accettare le condizioni riportate nell'informativa Privacy");
        $checkVal = new \Zend\Validator\Digits();
        $checkVal->setMessages(array(
            \Zend\Validator\Digits::NOT_DIGITS => "Devi accettare le condizioni riportate nell'informativa Privacy"
        ));
        $checkboxFilter->getValidatorChain()->attach($checkVal);
        $inputFilter->add($checkboxFilter);
 
        
        if ($this->extraFields === true) {
            
            $select1Filter = new \Zend\InputFilter\Input('internal');
            $select1Filter->setRequired(true);
            $select1Filter->setErrorMessage("Devi selezionare un valore");
            $inputFilter->add($select1Filter);
            
            $select2Filter = new \Zend\InputFilter\Input('role');
            $select2Filter->setRequired(true);
            $select2Filter->setErrorMessage("Devi selezionare un valore");
            $inputFilter->add($select2Filter);
        }
        
        return $inputFilter;
    }
    
}