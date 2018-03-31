<?php
	
	//file : SuccessController.php
	
	namespace Login\Controller;
	
	use Zend\Mvc\Controller\AbstractActionController;
	use Zend\Authentication\AuthenticationService;
	use Zend\View\Model\ViewModel;
	
	class SuccessController extends AbstractActionController
	{
		public function indexAction()
		{
			$user = \Zend\Json\Json::decode( $this->getServiceLocator()->get('AuthService')->getStorage()->getSessionManager()->getSaveHandler()->read($this->getServiceLocator()->get('AuthService')->getStorage()->getSessionId()), true);
			$viewModel=new ViewModel;
			$viewModel->setVariable('user_data',$user);
			return $viewModel;
		}
		
		public function helpAction()
		{
			
		}
		
		public function infoAction()
		{
			
		}
	}


?>