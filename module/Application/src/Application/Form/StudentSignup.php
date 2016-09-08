<?php

namespace Application\Form;

use Zend\Form\Form;

class StudentSignup extends Form
{	
    public function __construct($extraFields = false)
    {
        parent::__construct('signup_student');

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'firstname',
            'required' => true,
            'options' => array (
                'label' => 'Nome',
            ),
            'validators' => array(
		array(
                    'name'=>'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\NotEmpty::IS_EMPTY => 'Questo campo &egrave; obbligatorio',
                        ),
                    ),
		),
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
            'type' => 'Zend\Form\Element\Email',
            'name' => 'email',
            'required' => true,
            'options' => array (
                'label' => 'E-Mail',
            ),
            'validators' => array(
		array(
                    'name'=>'Mail',
                    'options' => array(
                        'messages' => array(
                            \Zend\Validator\EmailAddress::INVALID => 'Questo campo deve contenere un indirizzo email valido',
                        ),
                    ),
		),
            ),
            'attributes' => array(
                'class' => 'form-control',
            ),
	));
        
        if ($extraFields === true) {

            $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'internal',
                'options' => array(
                    'label' => 'Sono interno a Ca\'Foscari',
                    'value_options' => array(
                        '0' => 'Seleziona',
                        '1' => 'Si',
                        '2' => 'No'
                    ),
                ),
                'attributes' => array(
                    'value' => '0',
                    'class' => 'form-control'
                )
            ));

            $this->add(array(
                'type' => 'Zend\Form\Element\Select',
                'name' => 'role',
                'options' => array(
                    'label' => 'Il mio ruolo',
                    'value_options' => array(
                        '0' => 'Seleziona',
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
            'name' => 'privacy',
            'required' => true,
            'options' => array (),
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'subm',
            'attributes' => array(
                'value' => 'ACCEDI',
                'class' => "btn btn-primary btn-lg col-xs-8 col-xs-offset-2",
            )
        ));
    }
}