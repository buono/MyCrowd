<?php
/**
 * Created by PhpStorm.
 * User: yakko_tofu
 * Date: 15/08/03
 * Time: 21:57
 */

App::uses('WorksController', 'Controller');

class WorksShell extends AppShell
{

    public function startup()
    {
        parent::startup();
        $this->WorksController = new WorksController();
    }

    public function fetch()
    {
        $this->WorksController->setData();
        //$this->out($this->WorksController->setData());
    }

}