<?php

namespace Application\Form;

use Zend\Form\Form;

class ExamEmpty extends Form
{
	
	public function __construct()
	{
		parent::__construct('null_question');

		$this->add(array(
			'type' => 'Zend\Form\Element\Submit',
			'name' => 'subbb',
			'attributes' => array(
				'value' => 'AVANTI',
				'class' => "btn btn-primary btn-lg col-xs-8 col-xs-offset-2",
			)
			
		));
	}
}