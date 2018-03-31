<?php
	
	namespace Questioner\Model;
	
	use Zend\Db\TableGateway\TableGateway;
	
	class QuestionerTable
	{
		protected $tableGateway;
		
		public function __construct(TableGateway $tableGateway)
		{
			$this->tableGateway=$tableGateway;
		}
		
		public function fetchAll()
		{
			$resultSet=$this->tableGateway->select();
			return $resultSet;
		}
		
		public function getAnswer($id)
		{
			$id=(int) $id;
			$rowset=$this->tableGateway->select(array('ID_Answer'=>$id));
			$row=$rowset->current();
			if(!$row){
				throw new \Exception("Tidak Menemukan Data yang dicari berdasarkan ID");
			}
			return $row;
		}
		
		public function decodeAnswer($data,$ind)
		{
			foreach($data as $key=>$value){
				$dset=$value[$ind];
			}
			$retre=\Zend\Json\Json::decode($dset,true);
			return $retre;
		}
		
		public function saveAnswer(Questioner $answer,$username)
		{
			$dset=$this->normalizationObjectToArray($answer);
			$data=array(
				'username'=>$username,
				'answer_data'=>$dset,
			);
			
			$id=(int)$answer->id;
			if($id==0){
				$this->tableGateway->insert($data);
			}else{
				if($this->getAnswer($id)){
					$this->tableGateway->update($data,array('id'=>$id));
				}else{
					throw new \Exception('Answer id doesn\'t not exist');
				}
			}
		}
		
		
		public function deleteAnswer($id)
		{
			$this->tableGateway->delete(array('ID_Answer'=>(int)$id));
		}
		
		public function normalizationObjectToArray($object)
		{
			$add=array();$i=0;
			foreach($object as $data){
				foreach($data as $key=>$val){
					if(($val!=null)&&($val!='Save')){
						$add[$i]="\"".$i."\":\"".$this->clean($val)."\"";
						$i++;
					}
				}
			}
			$imArr="{".implode(",",$add)."}";
			return $imArr;
		}
		
		public function clean($string){
		   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
		   $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
		   return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
		}
		
	}
	

?>