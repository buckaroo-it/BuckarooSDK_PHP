<?php

namespace Buckaroo\Handlers\Logging;

use Buckaroo\Handlers\Logging\Observers\Monolog;

class DefaultLogger implements Subject
{
    protected array $observers  = [];

    public function __construct() {
        $this->attach(new Monolog());
    }

    public function attach($observer)
    {
        if(is_array($observer)) {
            foreach($observer as $singleObserver) {
                $this->attach($singleObserver);
            }

            return;
        }

        if($observer instanceof Observer) {
            $this->observers[] = $observer;
        }

        return;
    }

    public function detach(Observer $observer)
    {
        // TODO: Implement detach() method.
    }

    public function notify()
    {
        // TODO: Implement notify() method.
    }
}