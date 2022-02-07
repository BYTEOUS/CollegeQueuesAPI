<?php

class InfoMode extends Mode {
    public string $baseRoute = 'info'

    public array $endpoints = [
        array(
            'method' => 'GET',
            'route' => '',
            'handler' => function() {
                
            }
        )
    ]
}