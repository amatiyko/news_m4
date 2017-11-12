<?php

class CategoryController extends Controller {

    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->model = new Cat();
    }

    public function index() {
        $params = App::getRouter()->getParams();

        $this->data['category'] = $params[0];
        $this->data['articles_list'] = $this->model->getArticleList($this->data['category']);
    }

    public function article() {
        $params = App::getRouter()->getParams();
        $id = $params[1];

        $this->data['article'] = $this->model->getArticle($id);
        $this->data['tags'] = $this->model->getArticleTags($id);
        $this->data['comments'] = $this->model->getComments($id);

        $this->data['reads_now'] = rand(1,5);

        $new_amount = $this->data['reads_now'] + $this->data['article']['read_amount'];
        $this->model->saveReads($new_amount, $id);


        foreach ($this->data['comments'] as $item) {
            $this->data['answers'][$item['id']] = $this->model->getAnswers($item['id']);
        }

        if ($_POST) {
            if (isset($_POST['like'])) {
                $result = $this->model->plusRaiting($_POST);
                if ($result)
                    Router::redirect("/category/article/{$this->data['article']['category']}/{$id}");
            }
            if (isset($_POST['dislike'])) {
                $result = $this->model->minusRaiting($_POST);
                if ($result)
                    Router::redirect("/category/article/{$this->data['article']['category']}/{$id}");
            }

            if (isset($_POST['answer-btn'])) {
                if ( $this->data['article']['category'] == 'politics')
                    $_POST['published'] = FALSE;
                $result = $this->model->saveAnswer($_POST);
                if ($result) {
                    Session::setFlashMessage('Answer added');
                } else {
                    Session::setFlashMessage('Error.');
                }
                Router::redirect("/category/article/{$this->data['article']['category']}/{$id}");
            }
            if (isset($_POST['comment-btn'])) {
                if ( $this->data['article']['category'] == 'politics')
                    $_POST['published'] = FALSE;
                $result = $this->model->saveComment($_POST);
                if ($result) {
                    Session::setFlashMessage('Comment added');
                } else {
                    Session::setFlashMessage('Error.');
                }
                Router::redirect("/category/article/{$this->data['article']['category']}/{$id}");

            }

        }
    }
}