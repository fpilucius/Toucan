<?php

namespace Toucan\Component\EventListener;

use Toucan\Component\EventListener\Event;
use Toucan\Component\EventListener\SubscriberEventInterface;

class EventListener
{

    private $listeners = array();

    public function attach($listenerName, $listener=null)
    {
        if ($listenerName instanceof SubscriberEventInterface) {
            $this->attachSubscriber($listenerName);
        } else {
            if (!isset($this->listeners[$listenerName])) {
                $this->listeners[$listenerName] = array();
            }
            $this->listeners[$listenerName][] = $listener;
        }
    }

    public function detach($listenerName, $listener=null)
    {
        if ($listenerName instanceof SubscriberEventInterface) {
            $this->detachSubscriber($listenerName);
        } else {
            $cpt = count($this->listeners[$listenerName]);
            for ($i = 0; $i < $cpt; $i++) {
                if ($listener === $this->listeners[$listenerName][$i]) {
                    unset($this->listeners[$listenerName][$i]);
                }
            }
        }
    }

    protected function attachSubscriber(SubscriberEventInterface $subscriber)
    {
        foreach ($subscriber->getEvents() as $listenerName => $method) {
            if (is_string($method)) {
                $this->attach($listenerName, array($subscriber, $method));
            } else {
                foreach ($method as $listener) {
                    $this->attach($listenerName, array($subscriber, $listener));
                }
            }
        }
    }

    protected function detachSubscriber(SubscriberEventInterface $subscriber)
    {
        foreach ($subscriber->getEvents() as $listenerName => $method) {
            if (is_string($method)) {
                $this->detach($listenerName, array($subscriber, $method));
            } else {
                foreach ($method as $listerner) {
                    $this->detach($listenerName, array($subscriber, $listener));
                }
            }
        }
    }

    public function dispatch($event, $subject = null, $data = null)
    {
        if (!$event instanceof Event) {
            $event = new Event($event, $subject, $data);
        }
        $eventListener = $event->getName();
        if (!isset($this->listeners[$eventListener])) {
            return $event;
        }
        foreach ($this->listeners[$eventListener] as $handler) {
            call_user_func($handler, $event);
            if ($event->isPropagationStopped()) {
                break;
            }
        }
        return $event;
    }

}
?>