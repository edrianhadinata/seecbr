<?php
	//questioner controller
	
	namespace Questioner\Controller;
	
	use Zend\Mvc\Controller\AbstractActionController;
	use Zend\View\Model\ViewModel;
	use Questioner\Model\Questioner;
	use Questioner\Form\QuestionerForm;
	
	class QuestionerController extends AbstractActionController
	{
		protected $answerTable;
		protected $questionTable;
		
		public function indexAction()
		{
			return new ViewModel(array(
				'questioner'=>$this->getAnswerTable()->fetchAll(),
			));
		}
		
		public function myAnswerDataAction()
		{
			$id=(int)$this->params()->fromRoute('id',0);
			$data_answer=$this->getAnswerTable()->decodeAnswer($this->getAnswerTable()->getAnswer($id),'answer_data');
			$data_question=$this->getQuestionTable()->fetchAll();
			return new ViewModel(array(
				'answer_detail'=>$data_answer,
				'question_detail'=>$data_question,
				'id'=>$id,
			));
		}
		
		public function getAnswerTable()
		{
			if(!$this->answerTable){
				$sm=$this->getServiceLocator();
				$this->answerTable=$sm->get('Questioner\Model\QuestionerTable');
			}
			return $this->answerTable;
		}
		
		public function getQuestionTable()
		{
			if(!$this->questionTable){
				$sm=$this->getServiceLocator();
				$this->questionTable=$sm->get('Questioner\Model\QuestiondataTable');
			}
			return $this->questionTable;
		}
		
		public function addAction()
		{
			$dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
			$form=new QuestionerForm($dbAdapter);
			$request=$this->getRequest();
			$user = \Zend\Json\Json::decode( $this->getServiceLocator()->get('AuthService')->getStorage()->getSessionManager()->getSaveHandler()->read($this->getServiceLocator()->get('AuthService')->getStorage()->getSessionId()), true);
			if($request->isPost()){
				$questioner=new Questioner();
				$form->setInputFilter($questioner->getInputFilter());
				$form->setData($request->getPost());
				if($form->isValid()){
					$questioner->exchangeArray($form->getData());
					$this->getAnswerTable()->saveAnswer($questioner,$user['username']);
					//redirect to list of questioner
					return $this->redirect()->toRoute('questioner');
				}
				
			}
			return  array('form'=>$form);
		}
		
		public function editAction()
		{
			
		}
		
		public function deleteAction()
		{
			$id=(int)$this->params()->fromRoute('id',0);
			$data_answer=$this->getAnswerTable()->decodeAnswer($this->getAnswerTable()->getAnswer($id),'answer_data');
			$data_question=$this->getQuestionTable()->fetchAll();
			if(!$id){
				return $this->redirect()->toRoute('questioner');
			}
			$request=$this->getRequest();
			if($request->isPost()){
				$del=$request->getPost('del','No');
				if($del=='Yes'){
					$del=(int)$request->getPost('id');
					$this->getAnswerTable()->deleteAnswer($id);
				}
				//redirect to list of answer
				return $this->redirect()->toRoute('questioner');
			}
			return array(
				'id'=>$id,
				'answer_detail'=>$data_answer,
				'question_detail'=>$data_question,
			);
		}
	}


?>