<?php
	
	namespace Questioner\Model;
	
	use Zend\Db\TableGateway\TableGateway;
	
	class QuestiondataTable
	{
		protected $tableGateway;
		
		public function __construct(TableGateway $tableGateway)
		{
			$this->tableGateway=$tableGateway;
		}
		
		public function fetchAll()
		{
			$result=$this->tableGateway->select();
			return $result;
		}
		
		public function getQuestion($id)
		{
			$id=(int) $id;
			$rowset=$this->tableGateway->select(array('ID_Question'=>$id));
			$row=$rowset->current();
			if(!$row){
				throw new \Exception("Tidak Menemukan Question yang dicari berdasarkan ID");
			}
			return $row;
		}
		
		public function decodeOptions($data,$ind)
		{
			foreach($data as $key=>$value){
				$dset=$value[$ind];
			}
			$retre=\Zend\Json\Json::decode($dset,true);
			return $retre;
		}
		
		public function convertQuestion($obj_data,$ind)
		{
			$data_qu=array();
			foreach($obj_data as $arr){
				$data_qu=$arr[$ind];
			}
			return $data_qu;
		}
		
		
	}
	

?>