<?php

namespace Application\Form;

use Zend\Form\Form;

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
			$tag .= "<li>".$option."</li>";
		}
		$tag .= "</ul><br><br>";
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