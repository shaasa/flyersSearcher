<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         3.3.4
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http;

/**
 * Error Handling Controller
 *
 * Controller used by ExceptionRenderer to render error responses.
 */
class ErrorController extends AppController{
    /**
     * Initialization hook method.
     *
     * @return void
     */
    public function initialize() : void{
        $this->loadComponent('RequestHandler');
    }

    /**
     * beforeFilter callback.
     *
     * @param \Cake\Event\EventInterface $event Event.
     *
     * @return \Cake\Http\Response|null|void
     */
    public function beforeFilter(EventInterface $event){
    }

    /**
     * beforeRender callback.
     *
     * @param \Cake\Event\EventInterface $event Event.
     *
     * @return \Cake\Http\Response|null|void
     */
    public function beforeRender(EventInterface $event){
        parent::beforeRender($event);

        $this->viewBuilder()->setTemplatePath('Error');
    }

    /**
     * afterFilter callback.
     *
     * @param \Cake\Event\EventInterface $event Event.
     *
     * @return \Cake\Http\Response|null|void
     */
    public function afterFilter(EventInterface $event){
    }

    public function checkRequestData($allowedColumn) : array{
        $allowedFilters = ['category', 'is_published'];

        $response = [];
        $error = [];
        $debug = '';
        foreach($_GET['filter'] as $f => $value){
            if(!in_array($f, $allowedFilters)){
                $response['success'] = false;
                $response['code'] = '400';
                $error = ['message' => 'Bad Request'];
                $debug .= $f . ',';
            }
        }

        if(count($error) == 0){
            $fieldsRequest = explode(',', $_GET['fields']);

            foreach($fieldsRequest as $f){

                if(!in_array($f, $allowedColumn)){
                    $response['success'] = false;
                    $response['code'] = '400';
                    $error = ['message' => 'Bad Request'];
                    $debug .= $f . ',';
                }
            }
        }
        if(count($error) > 0){
            $response['error'] = ['message' => $error['message'],
                                  'debug'   => trim($debug,',')];

        }

        return $response;
    }

    public function checkResoults($results) : array{
        if(count($results) == 0){
            $response = ['success' => false,
                         'code'    => 404,
                         'error'   => ['message' => 'Not found',
                                       'debug'   => 'No results']];
            $this->viewBuilder()->setOption('serialize', $response);
        }else{
            $response = ['success' => true,
                         'code'    => 200,
                         'results' => $results

            ];
        }

        return $response;
    }
}
