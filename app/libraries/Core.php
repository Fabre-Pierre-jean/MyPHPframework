<?php


/**
 * App Core class
 * Creates URL & loads core controller
 * URL FORMAT - /controller/method/params
 */
class Core
{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params;

    public function __construct()
    {
        //  print_r($this->getUrl());
        $url = $this->getUrl();

        //////////////////////// 1st part of URL ////////////////////////

        if (isset($_GET["url"])){

            //Look if controller exist and set currentController to it
            if (file_exists('../app/controllers/' . ucwords($url[0]) . '.php')){
                $this->currentController = ucwords($url[0]);
                // unset the 0 index for the next time we visit the page
                unset($url[0]);
            }

            //Require the controller
            require_once '../app/controllers/' . $this->currentController . '.php';
            //Instantiate the controller found
            $this->currentController = new $this->currentController;

            //////////////////////// 2nd part of URL ////////////////////////

            if (isset($url[1])){
                if (method_exists($this->currentController, $url[1])){
                    $this->currentMethod = $url[1];
                    unset($url[1]);
                }
            }

            //Get params
            $this->params = $url ? array_values($url) : [];

            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
        }

    }

    public function getUrl()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/'); // skip the / at the end of the url like if someone put post/
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url); // return an array of value of the url argument
            return $url;
        }
    }
}