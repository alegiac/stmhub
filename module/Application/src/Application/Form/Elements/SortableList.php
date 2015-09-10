<?php

use Zend\Form\Element;
use Zend\InputFilter\InputProviderInterface;
class SortableList extends Element implements InputProviderInterface
{

	public function getInputSpecification()
	{
		return array (
			'name' => $this->getName(),
			'required' => true,
			'filters' => array(),
			'validators' => array()
		) ;
	}
}

    