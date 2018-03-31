<?php
	//questioner
	
	namespace Questioner\Model;
	
	class Questiondata
	{
		public $quest_data=array();
		
		public function exchangeArray($data)
		{	
			foreach($data as $key=>$value){
				$this->quest_data[$key]=(!empty($data[$key]))?$data[$key]:null;
			}
		}
	}
	


?>