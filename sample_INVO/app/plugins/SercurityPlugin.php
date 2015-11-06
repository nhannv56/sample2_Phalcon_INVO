<?php
use Phalcon\Acl;
use Phalcon\Events\Event;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl\Role;
use Phalcon\Acl\Adapter\Memory as AclList;
use Phalcon\Acl\Resource;
class SercurityPlugin extends Plugin {
	/**
	 *
	 * @param Event $event        	
	 * @param Dispatcher $dispatcher        	
	 * @return boolean false when have not permission
	 */
	public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher) {
		$auth = $this->session->get ( 'auth' );
		if (! $auth) {
			$role = 'Guests';
		} else {
			$role = 'Users';
		}
		// get action and controller from dispatcher
		$controller = $dispatcher->getControllerName ();
		$action = $dispatcher->getActionName ();
		
		// get acl list
		$acl = $this->getAcl ();
		$allowed = $acl->isAllowed ( $role, $controller, $action );
		
		if ($allowed != Acl::ALLOW) {
			$this->flashSession->error("You haven't permission to access this");
			return $dispatcher->forward ( array (
					'controller' => 'index',
					'action' => 'index' 
			) );
			
			return false;
		}
	}
	/**
	 *
	 * @return \Phalcon\Acl\Adapter\Memory
	 */
	public function getAcl() {
		// setup acl at first time
		if (! isset ( $this->persistent->acl )) {
			// create acl list for type of user
			$acl = new AclList ();
			// deny is default acl
			$acl->setDefaultAction ( Acl::DENY );
			// Create 2 roler for two user type: guest and user
			$roles = array (
					'users' => new Role ( 'Users' ),
					'guests' => new Role ( 'Guests' ) 
			);
			foreach ( $roles as $role ) {
				$acl->addRole ( $role );
			}
			// private resource area
			$privateResources = array (
					'users' => array (
							'index',
							'search',
							'edit',
							'delete' 
					),
					'companies' => array (
							'index',
							'search',
							'new',
							'edit',
							'create',
							'delete' 
					),
					'products' => array (
							'index',
							'search',
							'new',
							'edit',
							'create',
							'delete' 
					),
					'producttypes' => array (
							'index',
							'search',
							'new',
							'edit',
							'save',
							'create',
							'delete' 
					),
					'invoices' => array (
							'index',
							'profile' 
					) 
			);
			// add private area
			foreach ( $privateResources as $resource => $actions ) {
				
				$acl->addResource ( new Resource ( $resource ), $actions );
			}
			// public area
			$publicResource = array (
					'index' => array (
							'index' 
					),
					'about' => array (
							'index' 
					),
					'register' => array (
							'index',
							'regis' 
					),
					'session' => array (
							'index',
							'register',
							'start',
							'end' 
					),
					'users' => array (
							'create',
							'new' 
					) 
			);
			// add public area
			foreach ( $publicResource as $resource => $actions ) {
				$acl->addResource ( new Resource ( $resource ), $actions );
			}
			
			// grant all user have access to get public area
			foreach ( $roles as $role ) {
				foreach ( $publicResource as $resource => $actions ) {
					foreach ( $actions as $action ) {
						$acl->allow ( $role->getName (), $resource, $action );
					}
				}
			}
			// grant for only user have access to private area
			foreach ( $privateResources as $resource => $actions ) {
				foreach ( $actions as $action ) {
					$acl->allow ( 'Users', $resource, $action );
				}
			}
			$this->persistent->acl = $acl;
		}
		return $this->persistent->acl;
	}
}