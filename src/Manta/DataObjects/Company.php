<?php

namespace Manta\DataObjects;
/**
 * Created by PhpStorm.
 * User: kay
 * Date: 09-02-17
 * Time: 13:02
 */
class Company
{
    public function __construct($data){
        //small stub
        //we will maybe do this using getters and setters

        //tbd do checking of all fields etcetera...
        //not this sprint
        foreach($data as $key => $value) {
            $this->$key = $value;
        }
    }
}