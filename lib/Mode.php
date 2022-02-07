<?php

// Routing for each mode
abstract class Mode {
    // Name of the mode
    abstract public function getName();

    // Base route for the mode
    abstract public function getBaseRoute();

    // Endpoints of the mode
    abstract public function getEndpoints();
}