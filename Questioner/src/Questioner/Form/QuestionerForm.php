<?php
	
	//questioner form
	
	namespace Questioner\Form;
	
	use Zend\Form\Form;
	use Questioner\Model\Questioner;
	use Zend\Db\Adapter\AdapterInterface;
	use Zend\Db\Adapter\Adapter;
	
	class QuestionerForm extends Form
	{
		protected $adapter;
		public function __construct(AdapterInterface $dbAdapter,$name=null)
		{
			$this->adapter =$dbAdapter;
			parent::__construct('questioner');
			
			$this->add(array(
				'name'=>'id',
				'type'=>'hidden',
			));
			$this->getDataForSelect();
			
			$this->add(array(
				'name'=>'submit',
				'type'=>'submit',
				'attributes'=>array(
					'value'=>'Save',
					'id'=>'SubmitButton',
					'class'=>'btn btn-success btn-md',
				),
			));
			
		}
		
		
		 public function getOptionsForSelect()
		 {
			$dbAdapter = $this->adapter;
			$sql       = 'SELECT question FROM question';
			$statement = $dbAdapter->query($sql);
			$result    = $statement->execute();

			$selectData = array();

			foreach ($result as $res) {
				$selectData[$res['question']] = $res['question'];
			}
			
			return $selectData;
		}
		
		public function getDataForSelect()
		 {
			$dbAdapter = $this->adapter;
			$sql       = 'SELECT question,options FROM question';
			$statement = $dbAdapter->query($sql);
			$result    = $statement->execute();

			
			$i=0;
			foreach ($result as $res) {
				if($res['options']=="NUM"){
					$this->add(array(
						'name'=>'question_'.$i.'',
						'type'=>'text',
						'options'=>array(
							'label'=>$res['question']." ? in month",
						),
						'attributes'=>array(
							'class'=>'form-control',
						),
					));
				}else{
				
					$field=explode(',',$res['options']);
					
					$selectData = array();
					foreach ($field as $value_data) {
						$selectData[$value_data] = $value_data;
					}
					$this->add(array(
						'name'=>'question_'.$i.'',
						'type'=>'select',
						'attributes'=>array(
							'id'=>'question_'.$i.'',
							'class'=>'form-control',
						),
						'options'=>array(
							'label'=>$res['question']." ?",
							'empty_option'=>'-Choose Options-',
							'value_options'=>$selectData,
						),
					));
				}
				$i++;
			}
		}
	
	}
	
	

?>