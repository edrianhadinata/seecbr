<?php
	
	namespace Questioner;
	
	use Questioner\Model\Questioner;
	use Questioner\Model\QuestionerTable;
	use Zend\Db\ResultSet\ResultSet;
	use Zend\Db\TableGateway\TableGateway;
	use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
	use Zend\ModuleManager\Feature\ConfigProviderInterface;
	
	class Module implements AutoloaderProviderInterface, ConfigProviderInterface
	{
		public function getAutoloaderConfig()
		{
			return array(
				'Zend\Loader\ClassMapAutoloader'=>array(
					__DIR__ .'/autoload_classmap.php',
				),
				'Zend\Loader\StandardAutoloader'=>array(
					'namespaces'=>array(
						__NAMESPACE__ =>__DIR__ .'/src/'. __NAMESPACE__,
					),
				),
			);
		}
		
		public function getConfig()
		{
			return include __DIR__ .'/config/module.config.php';
		}
		
		public function getServiceConfig()
		{
			return array(
				'factories'=>array(
					'Questioner\Model\QuestionerTable'=>function($sm){
						$tableGateway=$sm->get('QuestionerTableGateway');
						$table=new QuestionerTable($tableGateway);
						return $table;
					},
					
					'QuestionerTableGateway'=>function($sm){
						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
						$resultSetPrototype=new ResultSet();
						$resultSetPrototype->setArrayObjectPrototype(new Questioner());
						return new TableGateway('answer',$dbAdapter,null,$resultSetPrototype);
					},
					
					'Questioner\Model\QuestiondataTable'=>function($sm){
						$tableGateway=$sm->get('QuestiondataTableGateway');
						$table=new Model\QuestiondataTable($tableGateway);
						return $table;
					},
					
					'QuestiondataTableGateway'=>function($sm){
						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
						$resultSetPrototype=new ResultSet();
						$resultSetPrototype->setArrayObjectPrototype(new Model\Questiondata());
						return new TableGateway('question',$dbAdapter,null,$resultSetPrototype);
					},
					//---
				),			
			);
		
		}
		
	}
	

?>