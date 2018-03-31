<?php
	
	namespace Estimate\Form;
	
	use Zend\Form\Form;
	
	class SenddataForm extends Form
	{
		public function __construct($idAnswer,$name=null)
		{
			parent::__construct($idAnswer);
			$this->setIdAnswer($idAnswer);
		}
		
		public function setIdAnswer($idAnswer)
		{
			$this->add(array(
				'name'=>'id_answer',
				'type'=>'hidden',
				'attributes'=>array(
					'value'=>$idAnswer,
				),
			));
			$this->add(array(
				'name'=>'submit',
				'type'=>'submit',
				'attributes'=>array(
					'value'=>'Press to Function Point Analysis',
					'class'=>'btn btn-success btn-md'
					
				),
			));
		}
	}
	
	
?>