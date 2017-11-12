<?php

class PagesController extends Controller {

    public function __construct($data = [])
    {
        parent::__construct($data);
        $this->model = new Page();
    }

    public function index() {
        $this->data['categories'] = $this->model->getList();
        $this->data['articles_list']  = [];
        $this->data['top_authors'] = $this->model->topAuthors();
        $this->data['top_topics'] = $this->model->topTopics();



        $this->data['articles_list_slider'] = $this->model->getArticlesList();

        foreach ($this->data['categories'] as $item) {
            $this->data['articles_list'][$item['category']] = $this->model->getArticlesList($item['category']);
        }
    }

    public function author() {
        $params = App::getRouter()->getParams();
        $this->data['author'] = $params[0];
        $this->data['comments'] = $this->model->authorComments($this->data['author']);
    }

    public function admin_index() {
        $this->data['articles_list'] = $this->model->getArticlesList();
        $this->data['comments_list'] = $this->model->getCommentsList();
    }

    public function admin_edit() {

        if ($_POST) {
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->edit_article($_POST, $id);
            if ($result) {
                Session::setFlashMessage('Article was updated');
            } else {
                Session::setFlashMessage('Error.');
            }
            Router::redirect('/admin/');
        }


        if (isset($this->params[0])){
            $this->data['article'] = $this->model->getArticle($this->params[0]);
        } else {
            Session::setFlashMessage('Wrong page id.');
            Router::redirect("admin/pages");
        }
    }

    public function admin_add_article() {
        if ($_POST) {
            $photo_destination_dir = '../webroot/img/'.$_FILES['image']['name'];
            move_uploaded_file($_FILES['image']['tmp_name'], $photo_destination_dir);

            $_POST['image'] = $_FILES['image']['name'];

            $result = $this->model->add_article($_POST);

            if ($result) {
                Session::setFlashMessage('Article was saved');
            } else {
                Session::setFlashMessage('Error.');
            }
            Router::redirect('/admin/');
        }
        $this->data['categories'] = $this->model->getList();
        $this->data['tags'] = $this->model->getTags();
    }

    public function admin_add_category() {
        if ($_POST) {
            $result = $this->model->add_category($_POST);
            if ($result) {
                Session::setFlashMessage('Category was saved');
            } else {
                Session::setFlashMessage('Error.');
            }
            Router::redirect('/admin/');
        }
    }

    public function admin_delete() {
        if (isset($this->params[0])) {
            $result = $this->model->delete($this->params[0]);
        }
        if ($result) {
            Session::setFlashMessage('Article was deleted');
        } else {
            Session::setFlashMessage('Error.');
        }
        Router::redirect('/admin/');
    }

    public function admin_moderate() {
        $this->data['comments_to_approve'] = $this->model->getComments();
        $this->data['answers_to_approve'] = $this->model->getAnswers();

        if($_POST) {
            if (isset($_POST['save-changes'])) {
                $result = $this->model->saveCommentChanges($_POST);
                if ($result) Router::redirect('/admin/pages/moderate/');
            }

            if (isset($_POST['comment-approve'])) {
                unset($_POST['comment-approve']);
                unset($_POST['description']);
                $data = array_keys($_POST);
                $result = $this->model->approveComments($data);
                if ($result) {
                    Session::setFlashMessage('Comments approved');
                } else {
                    Session::setFlashMessage('Error.');
                }
                Router::redirect('/admin/');
            } elseif (isset($_POST['answer-approve'])) {
                unset($_POST['answer-approve']);
                $data = array_keys($_POST);
                $result = $this->model->approveAnswers($data);
                if ($result) {
                    Session::setFlashMessage('Answers approved');
                } else {
                    Session::setFlashMessage('Error.');
                }
                Router::redirect('/admin/');
            }
        }
    }

    public function admin_advert(){
        $this->data['advert'] = $this->model->getAdvert();
        if($_POST) {
            $result = $this->model->saveAddChanges($_POST);
        }
    }

    public function  admin_style(){
        $this->data['styles'] = $this->model->getStyle();
        if($_POST) {
            if (isset($_POST['set_color'])) {
                $this->model->setColor($_POST['style']);
            }
            if (isset($_POST['new_color'])) {
                $result = $this->model->newColor($_POST['color']);
                if($result)
                    Router::redirect('/admin/pages/style/');
            }
        }
    }
}