<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element\Select;
use Zend\Form\Element\Submit;

class ExamMultisubmit extends Form
{
	
	public function __construct(array $arrOptions)
	{
		parent::__construct('multisubmit_question');
		
		foreach ($arrOptions as $value=>$description) {
			$this->add(array(
				'type' => 'Zend\Form\Element\Submit',
				'name' => 'submit_'.$value,
				'attributes' => array(
					'id' => $value,
					'value' => strtoupper($description),
					'class' => 'btn btn-primary btn-lg margins',
				)
			));
		}
	}
}