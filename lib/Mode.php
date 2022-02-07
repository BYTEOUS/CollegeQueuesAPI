<?php

// Routing for each mode
abstract class Mode {
    // Base route for the mode
    abstract public string $baseRoute;

    // Endpoints of the mode
    abstract public array $endpoints;
}