<?php
	//namefile : LoginForm
	
	namespace Login\Form;
	
	use Zend\Form\Form;
	use Zend\InputFilter;
	
	class LoginForm extends Form
	{
		public function __construct(){
		
		parent::__construct();
		
		$this->setAttribute('method','post');
		$this->add(
			array(
				'name'=>'username',
				'type'=>'Text',
				'options'=>array(
					'label'=>'Username :'
				),
				'attributes'=>array(
					'class'=>'form-control',
					'placeholder'=>'username',
				),
			)
		);
		
		$this->add(
			array(
				'name'=>'password',
				'type'=>'password',
				'options'=>array(
					'label'=>'Password :'
				),
				'attributes'=>array(
					'class'=>'form-control',
					'placeholder'=>'password',
				),
			)
		);
	
		$this->add(
			array(
				'name'=>'Loginsubmit',
				'type'=>'Submit',
				'attributes'=>array(
					'value'=>'Login',
					'id'=>'Loginsubmit',
					'class'=>'btn btn-success',
					
				)
			)
		);
		
		$this->setInputFilter($this->createInputFilter());
	}
	
	public function createInputFilter()
	{
		$inputFilter = new InputFilter\InputFilter();
		
		//username
		$username = new InputFilter\Input('username');
		$username->setRequired(true);
		$inputFilter->add($username);
		
		//password
		$password = new InputFilter\Input('password');
		$password->setRequired(true);
		$inputFilter->add($password);
		
		return $inputFilter;

	}
	
}
	
?>