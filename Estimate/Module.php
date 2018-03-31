<?php
	
	//estimate module
	
	namespace Estimate;
	
	use Estimate\Model\AnswerEstimate;
	use Estimate\Model\AnswerEstimateTable;
	use Estimate\Model\DatasetEstimate;
	use Estimate\Model\DatasetEstimateTable;
	use Estimate\Model\QuestionEstimate;
	use Estimate\Model\QuestionEstimateTable;
	use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
	use Zend\ModuleManager\Feature\ConfigProviderInterface;
	use Zend\Db\ResultSet\ResultSet;
	use Zend\Db\TableGateway\TableGateway;
	
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
					'Estimate\Model\AnswerEstimateTable'=>function($sm){
						$tableGateway=$sm->get('AnswerEstimateTableGateway');
						$table=new AnswerEstimateTable($tableGateway);
						return $table;
					},
					
					'AnswerEstimateTableGateway'=>function($sm){
						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
						$resultSetPrototype=new ResultSet();
						$resultSetPrototype->setArrayObjectPrototype(new AnswerEstimate());
						return new TableGateway('answer',$dbAdapter,null,$resultSetPrototype);
					},
					'Estimate\Model\QuestionEstimateTable'=>function($sm){
						$tableGateway=$sm->get('QuestionEstimateTableGateway');
						$table=new QuestionEstimateTable($tableGateway);
						return $table;
					},
					
					'QuestionEstimateTableGateway'=>function($sm){
						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
						$resultSetPrototype=new ResultSet();
						$resultSetPrototype->setArrayObjectPrototype(new QuestionEstimate());
						return new TableGateway('question',$dbAdapter,null,$resultSetPrototype);
					},
					
					'Estimate\Model\DatasetEstimateTable'=>function($sm){
						$tableGateway=$sm->get('DatasetEstimateTableGateway');
						$table=new DatasetEstimateTable($tableGateway);
						return $table;
					},
					
					'DatasetEstimateTableGateway'=>function($sm){
						$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
						$resultSetPrototype=new ResultSet();
						$resultSetPrototype->setArrayObjectPrototype(new DatasetEstimate());
						return new TableGateway('dataset',$dbAdapter,null,$resultSetPrototype);
					},
				),
			);
		}
		
	}
	
	
?>