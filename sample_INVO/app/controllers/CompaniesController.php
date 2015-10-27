<?php
use Phalcon\Mvc\Model\Criteria;
class CompaniesController extends ControllerBase {
	/**
	 * Index action
	 */
	public function indexAction() {
		$this->persistent->parameters = null;
		$auth = $this->session->get ( 'auth', 'yes' );
		// $this->view->user = $auth['name'];
	}
	
	/**
	 * Searches for companies
	 */
	public function searchAction() {
		$numberPage = 1;
		if ($this->request->isPost ()) {
			$query = Criteria::fromInput ( $this->di, "Companies", $_POST );
			$this->persistent->parameters = $query->getParams ();
		} else {
			$numberPage = $this->request->getQuery ( "page", "int" );
		}
		
		$parameters = $this->persistent->parameters;
		if (! is_array ( $parameters )) {
			$parameters = array ();
		}
		$parameters ["order"] = "id";
		
		$companies = Companies::find ( $parameters );
		if (count ( $companies ) == 0) {
			$this->flash->notice ( "The search did not find any companies" );
			
			return $this->dispatcher->forward ( array (
					"controller" => "companies",
					"action" => "index" 
			) );
		}
		
		$paginator = new Paginator ( array (
				"data" => $companies,
				"limit" => 10,
				"page" => $numberPage 
		) );
		
		$this->view->page = $paginator->getPaginate ();
	}
	
	/**
	 * Displays the creation form
	 */
	public function newAction() {
	}
	
	/**
	 * Edits a companie
	 *
	 * @param string $id        	
	 */
	public function editAction($id) {
		if (! $this->request->isPost ()) {
			
			$companie = Companies::findFirstByid ( $id );
			if (! $companie) {
				$this->flash->error ( "companie was not found" );
				
				return $this->dispatcher->forward ( array (
						"controller" => "companies",
						"action" => "index" 
				) );
			}
			
			$this->view->id = $companie->id;
			
			$this->tag->setDefault ( "id", $companie->id );
			$this->tag->setDefault ( "name", $companie->name );
		}
	}
	
	/**
	 * Creates a new companie
	 */
	public function createAction() {
		if (! $this->request->isPost ()) {
			return $this->dispatcher->forward ( array (
					"controller" => "companies",
					"action" => "index" 
			) );
		}
		
		$companie = new Companies ();
		
		$companie->id = $this->request->getPost ( "id" );
		$companie->name = $this->request->getPost ( "name" );
		
		if (! $companie->save ()) {
			foreach ( $companie->getMessages () as $message ) {
				$this->flash->error ( $message );
			}
			
			return $this->dispatcher->forward ( array (
					"controller" => "companies",
					"action" => "new" 
			) );
		}
		
		$this->flash->success ( "companie was created successfully" );
		
		return $this->dispatcher->forward ( array (
				"controller" => "companies",
				"action" => "index" 
		) );
	}
	
	/**
	 * Saves a companie edited
	 */
	public function saveAction() {
		if (! $this->request->isPost ()) {
			return $this->dispatcher->forward ( array (
					"controller" => "companies",
					"action" => "index" 
			) );
		}
		
		$id = $this->request->getPost ( "id" );
		
		$companie = Companies::findFirstByid ( $id );
		if (! $companie) {
			$this->flash->error ( "companie does not exist " . $id );
			
			return $this->dispatcher->forward ( array (
					"controller" => "companies",
					"action" => "index" 
			) );
		}
		
		$companie->id = $this->request->getPost ( "id" );
		$companie->name = $this->request->getPost ( "name" );
		
		if (! $companie->save ()) {
			
			foreach ( $companie->getMessages () as $message ) {
				$this->flash->error ( $message );
			}
			
			return $this->dispatcher->forward ( array (
					"controller" => "companies",
					"action" => "edit",
					"params" => array (
							$companie->id 
					) 
			) );
		}
		
		$this->flash->success ( "companie was updated successfully" );
		
		return $this->dispatcher->forward ( array (
				"controller" => "companies",
				"action" => "index" 
		) );
	}
	
	/**
	 * Deletes a companie
	 *
	 * @param string $id        	
	 */
	public function deleteAction($id) {
		$companie = Companies::findFirstByid ( $id );
		if (! $companie) {
			$this->flash->error ( "companie was not found" );
			
			return $this->dispatcher->forward ( array (
					"controller" => "companies",
					"action" => "index" 
			) );
		}
		
		if (! $companie->delete ()) {
			
			foreach ( $companie->getMessages () as $message ) {
				$this->flash->error ( $message );
			}
			
			return $this->dispatcher->forward ( array (
					"controller" => "companies",
					"action" => "search" 
			) );
		}
		
		$this->flash->success ( "companie was deleted successfully" );
		
		return $this->dispatcher->forward ( array (
				"controller" => "companies",
				"action" => "index" 
		) );
	}
}
