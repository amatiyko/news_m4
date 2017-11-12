<?php

class PopupController extends Controller {

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->model = new Popupi();
    }

    public function index() {
        if($_POST) {
            var_dump('hello from popup index');
            $result = $this->model->saveSubscriber($_POST);
            if ($result) {
                Session::setFlashMessage('Answer added');
            } else {
                Session::setFlashMessage('Error.');
            }
            Router::redirect('pages');
        }
    }
}