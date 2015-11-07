<?php
use Phalcon\Mvc\User\Component;
/**
 *
 * @author nhannv-pc
 *         this component will set menu bar and tab bar
 *         view of component in layout. main layout
 */
class Elements extends Component {
	private $_headerMenu = array (
			'navbar-left' => array (
					'index' => array (
							'caption' => 'Home',
							'action' => 'index' 
					),
					'invoices' => array (
							'caption' => 'Invoices',
							'action' => 'index' 
					),
					'about' => array (
							'caption' => 'About',
							'action' => 'index' 
					),
					'contact' => array (
							'caption' => 'Contact',
							'action' => 'index' 
					) 
			),
			'navbar-right' => array (
					'session' => array (
							'caption' => 'Login/Sign up',
							'action' => 'index' 
					) 
			) 
	);
	private $_tab = array (
			'Invoices' => array (
					'controller' => 'invoices',
					'action' => 'index',
					'any' => FALSE 
			),
			'Companies' => array (
					'controller' => 'companies',
					'action' => 'index',
					'any' => FALSE 
			),
			'Products' => array (
					'controller' => 'products',
					'action' => 'index',
					'any' => true 
			),
			'Your profile' => array (
					'controller' => 'invoices',
					'action' => 'profile',
					'any' => FALSE 
			) 
	);
	public function getTabs() {
		$controllerName = $this->view->getControllerName ();
		$actionName = $this->view->getActionName ();
		echo '<ul class="nav nav-tabs">';
		foreach ( $this->_tab as $caption => $option ) {
			if ($option ['controller'] == $controllerName && ($option ['action'] == $actionName || $option ['any'])) {
				echo '<li class="active">';
			} else {
				echo '<li>';
			}
			echo $this->tag->linkTo ( $option ['controller'] . '/' . $option ['action'] . '/', $caption ), '</li>';
		}
		echo '</ul>';
	}
	public function getMenu() {
		$auth = $this->session->get ( 'auth' );
		if ($auth) { // if user login to website
			$this->_headerMenu ['navbar-right'] ['session'] = array (
					'caption' => 'Log Out',
					'action' => 'end' 
			);
		} else { // not login
			unset ( $this->_headerMenu ['navbar-left'] ['invoices'] );
		}
		$controllerName = $this->view->getControllerName ();
		
		foreach ( $this->_headerMenu as $position => $menu ) {
			echo '<div class="nav-collapse">';
			echo '<ul class="nav navbar-nav ', $position, ' ">';
			foreach ( $menu as $controller => $option ) {
				if ($controllerName == $controller) {
					echo '<li class="active">';
				} else {
					echo '<li>';
				}
				echo $this->tag->linkTo ( $controller . '/' . $option ['action'], $option ['caption'] );
				echo '</li>';
			}
			echo '</ul>';
			echo '</div>';
		}
	}
}