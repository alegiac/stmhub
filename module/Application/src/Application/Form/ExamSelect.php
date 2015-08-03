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
	}
}