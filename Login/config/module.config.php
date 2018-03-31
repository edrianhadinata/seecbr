<?php
	//module.config.php
	
	namespace Login;
	
	return array(
		'controllers'=>array(
			'factories'=>array(
				'Login\Controller\Auth'=>'Login\Factory\Controller\AuthControllerServiceFactory'
			),
			
			'invokables'=>array(
				'Login\Controller\Success'=>'Login\Controller\SuccessController',
				//'Login\Controller\Auth'=>'Login\Controller\FailedController',
			),
		),
		
		//register auth service
		
		'service_manager'=>array(
			'factories'=>array(
				'AuthStorage'=>'Login\Factory\Storage\AuthStorageFactory',
				'AuthService'=>'Login\Factory\Storage\AuthenticationServiceFactory'
				
			),
		),
		
		//routing configuration
		
		'router'=>array(
			'routes'=>array(
				'auth'=>array(
					'type'=>'segment',
					'options'=>array(
						'route'=>'/auth[/:action]',
						'defaults'=>array(
							'controller'=>'Login\Controller\Auth',
							'action'=>'index',
						),
					),
				),
				
				'success'=>array(
					'type'=>'segment',
					'options'=>array(
						'route'=>'/success[/:action]',
						'defaults'=>array(
							'controller'=>'Login\Controller\Success',
							'action'=>'index',
						),
					),
				),

			),
		),

		//setting up view manager
		
		'view_manager'=>array(
			'template_path_stack'=>array(
				'login'=>__DIR__ .'/../view',
			),
		),
		
		
		
	);
?>