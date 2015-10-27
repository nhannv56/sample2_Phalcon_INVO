<?php
use Phalcon\Mvc\User\Plugin;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
class NotFoundPlugin extends Plugin{
	public function beforeException(Event $event,Dispatcher $dispatcher) {
		
	}
}