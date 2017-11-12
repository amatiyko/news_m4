<?php

class FindController extends Controller
{
    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->model = new Findi();
    }
    public function index()
    {
        $this->data['tags'] = $this->model->getTagsList();
        $this->data['category'] = $this->model->getCategoryList();
        if (!empty($_POST)) {
            $this->data['filter'] = $this->model->getNewsByFilter($_POST);
        }
    }
}