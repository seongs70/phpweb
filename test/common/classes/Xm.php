<?php

class Xm {

    private $name;
    public function __construct($name) {
        $this->name = $name;
    }
    public function setName($name) {
        $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }
}
?>
