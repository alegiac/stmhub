<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;

class ExamSelect extends Form
{
	
	public function __construct(array $arrOptions)
	{
		parent::__construct('select_question');
		
		$this->add(array(
			'type' => 'Zend\Form\Element\Select',
			'required' => true,
			'name' => 'input',
			'options' => array (
				'label' => '',
				'empty_option' => 'Seleziona',
				'value_options' => $arrOptions,
			),
			'attributes' => array(
				'class' => 'selectpicker col-xs-12',
				'data-live-search' => 'true'
			),
		));
		
		$this->add(array(
			'type' => 'Zend\Form\Element\Submit',
			'name' => 'subbb',
			'attributes' => array(
				'id' => 'subbb',
				'value' => 'INVIA',
				'class' => "btn btn-primary btn-lg col-xs-8 col-xs-offset-2",
			)
			
		));
	}
}