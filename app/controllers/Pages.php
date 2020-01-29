<?php


class Pages
{
    public function index(){

    }

    public function about($id = 0){
        if ($id != 0){
            echo $id;
        }
    }
}