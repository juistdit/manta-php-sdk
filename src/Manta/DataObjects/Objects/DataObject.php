<?php
//declare(strict_types=1);

namespace Manta\DataObjects\Objects;


abstract class DataObject
{
    public function __construct ($data) {
        //small stub
        //we will maybe do this using getters and setters

        //tbd do checking of all fields etcetera...
        //not this sprint
        foreach($data as $key => $value) {
            $this->$key = $value;
        }
    }
}