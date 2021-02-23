<?php
// src/Controller/ArticlesController.php

namespace App\Controller;

use App\Controller\Component\CsvDatasourceComponent;
use Cake\Http;

class FlyersController extends AppController{
    protected $errorHandler;

    public function initialize() : void{
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('CsvDatasource');

        /**
         * @var \App\Controller\Component\CsvDatasourceComponent
         */
        $this->CsvDatasource->openCsv();
    }

    public function index(){
        $this->errorHandler = new ErrorController();
        $response = $this->errorHandler->checkRequestData($this->CsvDatasource->getAllowedColumns());

        if(count($response) == 0){
            $flyers = $this->CsvDatasource->csvSearchResoults($_GET['filter'], $_GET['fields'], $_GET['page'], $_GET['limit']);
            $response = $this->errorHandler->checkResoults($flyers);
        }
        foreach($response as $k => $v){
            $this->set($k, $v);
            $serializeArray[] = $k;
        }
        $this->viewBuilder()->setOption('serialize', ['success','code',$serializeArray[2]]);
    }

    public function view(){
    }
}
