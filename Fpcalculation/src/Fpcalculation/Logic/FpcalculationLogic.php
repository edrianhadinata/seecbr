<?php
	
	namespace Fpcalculation\Logic;
	
	
	class FpcalculationLogic
	{
		protected $arrPostData;
		protected $arrWeight;
		protected $arrCri;
		protected $arrDesc;
		protected $arrPostWeightComplexity;
		protected $systemComplexity;
		protected $unadjustedFP;
		protected $countNumSystemComplexity;
		protected $arrAdjustedProcessingComplexity=array(1=>0.65,2=>1.0,3=>1.35);
		protected $valStandardAdjustment;
		protected $constantEffort=array('projectworkeffort'=>array('C'=>23.25,'E1'=>0.814),'projectduration'=>array('C'=>0.543,'E1'=>0.408),'speedofdelivery'=>array('C'=>1.842,'E1'=>0.592));
		
		public function __construct()
		{
			
		}
		
		public function setUnadjustedFPCount($arrPostData,$arrWeight,$arrDesc,$arrCri)
		{
			$this->arrPostData=$arrPostData;
			$this->arrWeight=$arrWeight;
			$this->arrCri=$arrCri;
			$this->arrDesc=$arrDesc;
		}
		
		public function setSystemComplexityCount($arrPostWeightComplexity)
		{
			$this->arrPostWeightComplexity=$arrPostWeightComplexity;
		}
		
		public function getUnadjustedFPCount()
		{
			$mul=0;
			foreach($this->arrPostData as $key=>$arrPostVal){
				$i=0;
				foreach($this->arrDesc as $desc){
					$j=0;
					foreach($this->arrCri as $cri){
						$mul+=($key==$desc.'_'.$cri)?$arrPostVal*$this->arrWeight[$i][$j]:0;
						$j++;
					}
					$i++;
				}
			}
			return $mul;
		}
		
		public function getSystemComplexityCount()
		{
			$tot=0;$this->countNumSystemComplexity=0;
			foreach($this->arrPostWeightComplexity as $key=>$valPostWeightComplexity){
				if(($key!='unadjustedFp')and($key!='id_answer')){
				$valPostWeightComplexity=(int)$valPostWeightComplexity;
				$tot+=$valPostWeightComplexity;
				$this->countNumSystemComplexity++;
				}
			}
			return $tot;
		}
		
		public function setSystemComplexity($systemComplexity)
		{
			$this->systemComplexity=$systemComplexity;
		}
		
		public function setUnadjustedFP($unadjustedFP)
		{
			$this->unadjustedFP=$unadjustedFP;
		}
		
		public function setStandardAdjustment()
		{
			$mean=$this->systemComplexity/$this->countNumSystemComplexity;
			$st=array(1=>abs(1-$mean),2=>abs(2-$mean),3=>abs(3-$mean));
			asort($st);
			foreach($st as $key=>$val){
				$data[]=$key;
			}
			$this->valStandardAdjustment=$this->arrAdjustedProcessingComplexity[$data[0]];
		}
		
		public function getAdjustedProcessingComplexity()
		{
			$getAdjust=$this->valStandardAdjustment + (0.01 * $this->systemComplexity);
			return $getAdjust;
		}
		
		public function getProjectWorkEffort($totalAdjustedFP)
		{
			return round($this->constantEffort['projectworkeffort']['C'] * pow($totalAdjustedFP,$this->constantEffort['projectworkeffort']['E1']),3);
		}
		
		public function getProjectDuration($totalAdjustedFP)
		{
			return round($this->constantEffort['projectduration']['C'] * pow($totalAdjustedFP,$this->constantEffort['projectduration']['E1']),3);
		}
		
		public function getSpeedofDelivery($totalAdjustedFP)
		{
			return round($this->constantEffort['speedofdelivery']['C'] * pow($totalAdjustedFP,$this->constantEffort['speedofdelivery']['E1']),3);
		}
		
	}
	

?>