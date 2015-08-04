<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;

class ExamInput extends Form
{
	
	public function __construct()
	{
		parent::__construct('input_question');
		
		$this->add(array(
			'type' => 'Zend\Form\Element\Text',
			'name' => 'inpu',
			'options' => array (
				'label' => '',
			),
			'attributes' => array(
				'class' => 'form-control input-lg col-xs-12',
				'placeholder' => 'Inserisci risposta',
			),
		));
		
		$this->add(array(
			'type' => 'Zend\Form\Element\Submit',
			'name' => 'subbb',
			'attributes' => array(
				'value' => 'INVIA',
				'class' => "btn btn-primary btn-lg col-xs-8 col-xs-offset-2",
			)
			
		));
	}
}