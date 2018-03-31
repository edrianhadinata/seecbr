<?php
	//questioner
	
	namespace Questioner\Model;
	use Zend\InputFilter\InputFilter;
	use Zend\InputFilter\InputFilterAwareInterface;
	use Zend\InputFilter\InputFilterInterface;
	
	class Questioner implements InputFilterAwareInterface
	{
		public $ans_data=array();
		protected $inputFilter;
		
		public function exchangeArray($data)
		{	
			foreach($data as $key=>$value){
				$this->ans_data[$key]=(!empty($data[$key]))?$data[$key]:null;
			}
		}
		
		//add content to these method
		
		public function setInputFilter(InputFilterInterface $inputFilter)
		{
			throw new \Exception("Not Used");
		}
		
		public function getInputFilter()
		{
			if(!$this->inputFilter){
				$inputFilter=new InputFilter();
				$inputFilter->add(array(
					'name'=>'id',
					'required'=>true,
					'filters'=>array(
						array('name'=>'Int'),
					)
				));
				
				for($i=0;$i<9;$i++){
					$inputFilter->add(array(
						'name'=>'question_'.$i.'',
						'required'=>true,
					));
				}
				
				$this->inputFilter=$inputFilter;
			}
			return $this->inputFilter;
		}
		
		
		
	}
	


?>