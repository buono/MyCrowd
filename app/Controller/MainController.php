<?php

class MainController extends AppController
{
    public $name = "Main";
    public $uses = null;
    public $autoLayout = true;
    public $autoRender = true;

    public function index(){
    }

    public function fetchRecord(){
        $type = $this->request->query['type'];
        $category = $this->request->query['category'];
        $keyword = $this->request->query['keyword'];
        $this->set("type",$type);
        $this->set("category",$category);
        $this->set("keyword",$keyword);

        if(!empty($this->data)){
            $this->Work->save($this->data);
        }
        $this->redirect('index');
    }

    public function fetch(){
    }
}