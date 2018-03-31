<?php
	
	//Fpcalculation.php
	
	namespace Fpcalculation\Controller;
	
	use Zend\View\Model\ViewModel;
	use Zend\Mvc\Controller\AbstractActionController;
	use Fpcalculation\Form\FpcalculationForm;
	use Fpcalculation\Model\Fpcalculation;

	
	class FpcalculationController extends AbstractActionController
	{
		public $unadjustedFp;
		protected $answerTable;
		protected $datasetTable;
		public function indexAction()
		{	
			$user = \Zend\Json\Json::decode( $this->getServiceLocator()->get('AuthService')->getStorage()->getSessionManager()->getSaveHandler()->read($this->getServiceLocator()->get('AuthService')->getStorage()->getSessionId()), true);
			$nama=$user['username'];
			return new ViewModel(array('nama'=>$nama));
		}
		
		public function unadjustedfunctionpointAction()
		{
			
			$form=new FpcalculationForm('UnadjustedFunctionPoint');
			$request=$this->getRequest();
			$unadjustedFp=null;
			//print_r($request->getPost());
			
			foreach($request->getPost() as $key=>$ed){
				if($key=='submit'){$da=$ed;}
				if($key=='id_answer'){$id_answer=$ed;}
			}
			if(!isset($id_answer)){$id_answer=null;}
			if(($request->isPost())&&($da=='Calculate')){
				$Fpcalculation=new Fpcalculation;
				$Fpcalculation->setChangeForm('UnadjustedFunctionPoint');
				$form->setInputFilter($Fpcalculation->getInputFilter());
				$form->setData($request->getPost());
				if($form->isValid()){
					$Fpcalculation->exchangeArray($form->getData());
					$Fpcalculation->setFpCalculationLogic();
					
					$this->unadjustedFp=$Fpcalculation->unadjustedFP;
					//print_r($Fpcalculation->ans_data['id_answer']);
					//redirect to list of processing complexity
					return $this->forward()->dispatch('Fpcalculation\Controller\Fpcalculation',array('action'=>'processingcomplexity','unadjustedFp'=>$this->unadjustedFp,'id_answer'=>$Fpcalculation->ans_data['id_answer']));
				}
			}
			
			return  array('form'=>$form,'unadjustedFp'=>$this->unadjustedFp,'id_answer'=>$id_answer);
		}
	
		public function processingcomplexityAction()
		{
			$unadjustedFp=$this->params()->fromRoute('unadjustedFp');
			$id_answer=$this->params()->fromRoute('id_answer');
			if(!isset($id_answer)){$id_answer=null;}
			/*if(!isset($unadjustedFp)){
				return $this->redirect()->toRoute('fpcalculation',array('action'=>'unadjustedfunctionpoint'));
			}*/
			$form=new FpcalculationForm('SystemComplexity');
			$request=$this->getRequest();
			foreach($request->getPost() as $key=>$ed){
				if($key=='submit'){$da=$ed;}
			}
			if(($request->isPost())&&($da=='Process')){
				
				$Fpcalculation=new Fpcalculation;
				$Fpcalculation->setChangeForm('SystemComplexity');
				$form->setInputFilter($Fpcalculation->getInputFilter());
				$form->setData($request->getPost());
				if($form->isValid()){
					$Fpcalculation->exchangeArray($form->getData());
					$Fpcalculation->setFpCalculationLogic();
					$totalAdjustedFP=$Fpcalculation->getTotalAdjustedFP($Fpcalculation->ans_data['unadjustedFp'],$Fpcalculation->adjustedProcessingComplexity);
					//print_r(array($Fpcalculation->ans_data['unadjustedFp'],$Fpcalculation->systemComplexity,$Fpcalculation->adjustedProcessingComplexity,$totalAdjustedFP));
					return $this->forward()->dispatch('Fpcalculation\Controller\Fpcalculation',array('action'=>'estimationeffort','unadjustedFp'=>$Fpcalculation->ans_data['unadjustedFp'],'systemComplexity'=>$Fpcalculation->systemComplexity,'adjustedProcessingComplexity'=>$Fpcalculation->adjustedProcessingComplexity,'totalAdjustedFP'=>$totalAdjustedFP,'id_answer'=>$Fpcalculation->ans_data['id_answer']));
				}
			}
			return array('form'=>$form,'unadjustedFp'=>$unadjustedFp,'id_answer'=>$id_answer);
		}
		
		public function estimationeffortAction()
		{
						
			$unadjustedFp=$this->params()->fromRoute('unadjustedFp');
			$id_answer=$this->params()->fromRoute('id_answer');
			if(!isset($id_answer)){$id_answer=null;$specify=null;}
			
			$systemComplexity=$this->params()->fromRoute('systemComplexity');
			$adjustedProcessingComplexity=$this->params()->fromRoute('adjustedProcessingComplexity');
			$totalAdjustedFP=$this->params()->fromRoute('totalAdjustedFP');
			$arrEstimateResult=array($unadjustedFp,$systemComplexity,$adjustedProcessingComplexity,$totalAdjustedFP);
			$Fpcalculation=new Fpcalculation;
			$workEffort=$Fpcalculation->setEffortEstimation()->getProjectWorkEffort($totalAdjustedFP);
			$projectDuration=$Fpcalculation->setEffortEstimation()->getProjectDuration($totalAdjustedFP);
			$speedofDelivery=$Fpcalculation->setEffortEstimation()->getSpeedofDelivery($totalAdjustedFP);
			if($id_answer!=null){
				$dataans=$this->getFpcalculationTable()->getAnswer($id_answer);
				$ans=$this->decodeAnswer($dataans);
				$specify=$this->getDatasetTable()->spesify;
				$ID=$this->getDatasetTable()->getMaximumId();
			}else{
				$ans=null;
				$ID=null;
			}
			$form=new FpcalculationForm('EffortEstimation');
			$effort=array($workEffort,$projectDuration,$speedofDelivery);
			
			return array('form'=>$form,'estimateResult'=>$arrEstimateResult,'effort'=>$effort,'answer'=>$ans,'specify'=>$specify,'id_answer'=>$id_answer,'ID'=>$ID);
		}
		
		public function getFpcalculationTable()
		{
			if(!$this->answerTable){
				$sm=$this->getServiceLocator();
				$this->answerTable=$sm->get('Fpcalculation\Model\FpcalculationTable');
			}
			return $this->answerTable;
		}
		
		public function decodeAnswer($data)
		{
			$retre=\Zend\Json\Json::decode($data->ans_data['answer_data'],true);
			return $retre;
		}
		
		public function getDatasetTable()
		{
			if(!$this->datasetTable){
				$sm=$this->getServiceLocator();
				$this->datasetTable=$sm->get('Estimate\Model\DatasetEstimateTable');
			}
			return $this->datasetTable;
		}
		
		public function saveeffortAction()
		{
			$request=$this->getRequest();
			$form=new FpcalculationForm('EffortEstimation');
			if($request->isPost()){

				$Fpcalculation=new Fpcalculation;
				$Fpcalculation->setChangeForm('EffortEstimation');
				$form->setInputFilter($Fpcalculation->getInputFilter());
				$form->setData($request->getPost());
				
				if($form->isValid()){
					$Fpcalculation->exchangeArray($form->getData());
					$this->getDatasetTable()->setSavetoDataset($Fpcalculation->ans_data);
					//print_r($this->getFpcalculationTable()->setSavetoDataset($Fpcalculation->ans_data));
					
					$good='Your effort estimate calculation has been save into dataset';
					return new ViewModel(array('good'=>$good,'id_good'=>1));
				}
				$err='Your request may be not valid for this session. Would you like to go to the answer list page?';
				return new ViewModel(array('err'=>$err,'id_err'=>1));
				
			}else{
				$err='Your request may be not allow for this session. Would you like to go to the answer list page?';
				return new ViewModel(array('err'=>$err,'id_err'=>1));
			}
			
		}
		
	}
	

?>