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
		
		$select = new Select('answer');
		$select->setEmptyOption("Seleziona");
		$select->setOptions($arrOptions);
		$select->setAttribute('class', "selectpicker col-xs-12");
		$select->setAttribute('data-live-search', "true");
		
		$this->add($select);
		
		$submit = new Submit('submit');
		$submit->setLabel('INVIA');
		$submit->setAttribute('class', "btn btn-primary btn-lg m-t-5");
		
		$this->add($submit);
	}
}