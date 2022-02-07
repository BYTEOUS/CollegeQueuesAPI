<?php

class InfoMode extends Mode {
    public function getName() { return 'info'; }
    public function getBaseRoute() { return 'info'; }

    public function getEndpoints() {
       return array(
            'method' => 'GET',
            'route' => '',
            'handler' => function() {
                
            }
        );
    }
}