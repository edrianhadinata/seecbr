<?php
	
	namespace Fpcalculation\Model;
	
	use Zend\Db\TableGateway\TableGateway;
	
	class FpcalculationTable
	{
		protected $tableGateway;
		
		public function __construct(TableGateway $tableGateway)
		{
			$this->tableGateway =$tableGateway;
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
		

		public function decodeAnswer($data)
		{
			foreach($data as $key=>$value){
				$dset=$value['answer_data'];
			}
			$retre=\Zend\Json\Json::decode($dset,true);
			return $retre;
		}
		
		public function getDataset($id)
		{
			$data_re=$this->decodeAnswer($this->getAnswer($id));
			return $data_re;
		}
		
		public function setSavetoDataset($arrDatasave)
		{
			$data_form=array();
			foreach($this->datasetFieldRecord as $key=>$val){
				if(isset($arrDatasave[$val])){
					$data_form[$key]=$arrDatasave[$val];
				}
			}
			foreach($this->datasetField as $val){
				if(!isset($data_form[$val])){
					if($val=='Year'){
						$recToDataset[$val]=date('Y');
					}else{
						$recToDataset[$val]='?';
					}
				}else{
					$recToDataset[$val]=$data_form[$val];
				}
			}
			//return $recToDataset;
			
			$id=(int)$recToDataset['ID'];
			if($id!=0){
				$this->tableGateway->insert($recToDataset);
			}else{
				throw new \Exception('Dataset ID doesn\'t not exist');
			}
			
		}
		
	}

?>