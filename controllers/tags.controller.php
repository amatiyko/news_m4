<?php

class TagsController extends Controller {
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->model = new Tag();
    }

    public function index() {
        $params = App::getRouter()->getParams();
        $this->data['tag'] = $params[0];
        $this->data['articles'] = $this->model->getTagArticles($this->data['tag']);
    }
}