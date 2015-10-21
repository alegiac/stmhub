<?php

namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element\Submit;

class ExamDragDrop extends Form
{
	private $ulReorderOptions;
	
	public function getUlReorderOptions()
	{
		return $this->ulReorderOptions;
	}
	
	public function __construct($options)
	{
		parent::__construct('dnd_question');
		
		$array = explode("|",$options);
		shuffle(shuffle($array));
		$tag = "<ul class=\"scrambled\">";
		foreach ($array as $option) {
			$tag .= "<li class=\"btn btn-primary btn-lg col-xs-6 col-xs-offset-3\" style=\"white-space:normal !important;
    max-width:200px;margin-bottom:6px;
		\">".$option."</li><br>";
		}
		$tag .= "</ul>";
		$this->ulReorderOptions = $tag;
		
		$this->add(array(
			'type' => 'Zend\Form\Element\Submit',
			'name' => 'subbb',
			'attributes' => array(
				'value' => 'INVIA',
				'class' => "btn btn-primary btn-lg col-xs-8 col-xs-offset-2",
				'style' => 'margin-top: 5px;'
			)
			
		));
	}
}