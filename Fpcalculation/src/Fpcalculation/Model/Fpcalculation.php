<?php

	namespace Fpcalculation\Model;
	use Zend\InputFilter\InputFilter;
	use Zend\InputFilter\InputFilterAwareInterface;
	use Zend\InputFilter\InputFilterInterface;
	use Fpcalculation\Form\FpcalculationForm;
	use Fpcalculation\Logic\FpcalculationLogic;
	use Fpcalculation\Model\FpcalculationTable;
	
	class Fpcalculation implements InputFilterAwareInterface
	{
		public $ans_data=array();
		protected $inputFilter;
		public $changeForm;
		public $unadjustedFP;
		public $systemComplexity;
		public $adjustedProcessingComplexity;
		
		public function exchangeArray($data)
		{
			foreach($data as $key=>$value){
				$this->ans_data[$key]=(!empty($data[$key]))?$data[$key]:null;
			}
		}
		
		public function setChangeForm($changeForm)
		{
			$this->changeForm=$changeForm;
		}
		
		public function setFpCalculationLogic()
		{
			if($this->changeForm=='UnadjustedFunctionPoint'){
				$newForm=new FpcalculationForm($this->changeForm);
				$newLogic=new FpcalculationLogic;
				$newLogic->setUnadjustedFPCount($this->ans_data,$newForm->dataMultiplication,$newForm->dataUnadjustedFPDesc,$newForm->dataUnadjustedFPCriteria);
				$this->unadjustedFP=$newLogic->getUnadjustedFPCount();
			}
			if($this->changeForm=='SystemComplexity'){
				$newForm=new FpcalculationForm($this->changeForm);
				$newLogic=new FpcalculationLogic;
				$newLogic->setSystemComplexityCount($this->ans_data);
				$this->systemComplexity=$newLogic->getSystemComplexityCount();
				$newLogic->setSystemComplexity($this->systemComplexity);
				$newLogic->setStandardAdjustment();
				$this->adjustedProcessingComplexity=$newLogic->getAdjustedProcessingComplexity();
			}
			
			
		}
		
		public function setEffortEstimation()
		{
			$newEffortEstimation=new FpcalculationLogic;
			return $newEffortEstimation;
		}
		
		public function getTotalAdjustedFP($unadjustedFP,$getAdjust)
		{
			return $unadjustedFP * $getAdjust;
		}
		
		public function getUnadjustedFP()
		{
			return $this->unadjustedFP;
		}
		
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
				
				$inputFilter->add(array(
					'name'=>'id_answer',
					'filters'=>array(
						array('name'=>'Int'),
					)
				));
				
				if($this->changeForm=='UnadjustedFunctionPoint'){
					$newForm=new FpcalculationForm($this->changeForm);
					foreach($newForm->dataUnadjustedFPDesc as $desc){
						foreach($newForm->dataUnadjustedFPCriteria as $cri){
							$inputFilter->add(array(
								'name'=>$desc.'_'.$cri,
								'required'=>true,
								'filters'=>array(
									array('name'=>'Int'),
								)
							));
						}
					}
				}
				
				if($this->changeForm=='SystemComplexity'){
					$inputFilter->add(array(
						'name'=>'unadjustedFp',
						'required'=>true,
						'filters'=>array(
							array('name'=>'Int'),
						)
					));
					$inputFilter->add(array(
						'name'=>'id_answer',
						'filters'=>array(
							array('name'=>'Int'),
						)
					));
					
					$newForm=new FpcalculationForm($this->changeForm);
					for($i=0;$i<count($newForm->dataCategorySystemComplexity);$i++){
						$inputFilter->add(array(
							'name'=>'sc_'.$i,
							'required'=>true,
							'filter'=>array(
								array('name'=>'Int'),
							),
						));
					}
				}
				
				if($this->changeForm=='EffortEstimation'){
				
				$inputFilter->add(array(
						'name'=>'ID_Dataset',
						'required'=>true,
						'filters'=>array(
							array('name'=>'Int'),
						),
						
					));
					
					$newForm=new FpcalculationForm($this->changeForm);
					for($i=0;$i<count($newForm->spesify);$i++){
						$newForm->spesify[$i]=str_replace(" ","_",$newForm->spesify[$i]);
						$inputFilter->add(array(
							'name'=>$newForm->spesify[$i],
							'required'=>true,
						));
					}
					
					foreach($newForm->dataEstimationCategories as $values){
						$values=str_replace(" ","_",$values);
						$inputFilter->add(array(
							'name'=>$values,
							'required'=>true,
						));
					}
					
					foreach($newForm->dataEffortCategories as $values){
						$values=str_replace(" ","_",$values);
						$inputFilter->add(array(
							'name'=>$values,
							'required'=>true,
						));
					}
					
				}
	
				$this->inputFilter=$inputFilter;
			}
			return $this->inputFilter;
		}
		
		
		
	}

?>