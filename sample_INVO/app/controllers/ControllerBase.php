<?php
use Phalcon\Mvc\Controller;

/**
 *
 * @author nhannv-pc
 *        
 */
class ControllerBase extends Controller {
	/**
	 * init base controller
	 */
	protected function initialize() {
		$this->tag->prependTitle ( 'INVO| ' );
		
		// set up a layout
		 $this->view->setTemplateAfter ( 'main' );
	}
	/**
	 * forward user to a controller/action/
	 * 
	 * @param unknown $uri        	
	 */
	protected function forward($uri) {
		$uriParts = explode ( '/', $uri );
		$params = array_slice ( $uriParts, 2 );
		return $this->dispatcher->forward ( array (
				'controller' => $uriParts [0],
				'action' => $uriParts [1],
				'param' => $params 
		) );
	}
}
