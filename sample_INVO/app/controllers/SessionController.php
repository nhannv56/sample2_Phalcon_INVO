<?php
class SessionController extends \Phalcon\Mvc\Controller {
	public function indexAction() {
	}
	/**
	 * register a session for valid user
	 *
	 * @param Users $user        	
	 */
	private function _registerSession($user) {
		$this->session->set ( 'auth', array (
				'id' => $user->id,
				'name' => $user->name 
		) );
	}
	/**
	 * start validate user action
	 */
	public function startAction() {
		if ($this->request->isPost ()) {
			
			// get user and password from form
			$email = $this->request->getPost ( 'email' );
			$password = $this->request->getPost ( 'password' );
			
			// Find user in the database return Users if exist or false if not
			$user = Users::findFirst ( array (
					"(email = :email: ) and password = :password:",
					'bind' => array (
							'email' => $email,
							'password' => $password 
					) 
			) );
			if ($user != false) {
				$this->_registerSession ( $user );
				$this->flash->success ( 'Welcome ' . $user->first_name );
				return $this->dispatcher->forward ( array (
						'controller' => 'index',
						'action' => 'index' 
				) );
			}
			$this->flash->error ( "Wrong email/password" );
			$this->dispatcher->forward ( array (
					'controller' => 'session',
					'action' => 'index' 
			) );
		}
	}
}

