<?php
class RegisterController extends \Phalcon\Mvc\Controller {
	public function indexAction() {
	}
	public function regisAction() {
		if ($this->request->isPost ()) {
			
			$newUser = new Users ();
			$newUser->first_name = $this->request->getPost ( "first_name" );
			$newUser->last_name = $this->request->getPost ( "last_name" );
			$newUser->bithday = $this->request->getPost ( "bithday" );
			$newUser->password = $this->request->getPost ( "password" );
			$newUser->email = $this->request->getPost ( "email" );
			if (! $newUser->save ()) {
				foreach ( $newUser->getMessages () as $message ) {
					$this->flash->error ( $message );
				}
				
				return $this->dispatcher->forward ( array (
						"controller" => "register",
						"action" => "index" 
				) );
			}
			
			$this->flash->success ( "user was created successfully" );
			
			return $this->dispatcher->forward ( array (
					"controller" => "session",
					"action" => "index" 
			) );
		}
	}
}

