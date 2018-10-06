<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

class DirectorsController extends ControllerBase
{
	public function initialize()
    {
        $this->tag->setTitle('Directors');
        parent::initialize();
    }

    /**
     * Shows the index action
     */
    public function indexAction()
    {
        //$this->session->conditions = null;
        $this->view->form = new DirectorsForm;
    }

    /**
     * Search directors based on current criteria
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Directors", $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = [];
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $directors = Directors::find($parameters);
        if (count($directors) == 0) {
            $this->flash->notice("The search did not find any directors");

            return $this->dispatcher->forward(
                [
                    "controller" => "directors",
                    "action"     => "index",
                ]
            );
        }

        $paginator = new Paginator([
            "data"  => $directors,
            "limit" => 10,
            "page"  => $numberPage
        ]);

        $this->view->page = $paginator->getPaginate();
        $this->view->directors = $directors;
    }

    /**
     * Shows the form to create a new director
     */
    public function newAction()
    {
        $this->view->form = new DirectorsForm(null, ['edit' => true]);
    }

    /**
     * Edits a director based on its id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $director = Directors::findFirstById($id);
            if (!$director) {
                $this->flash->error("Director was not found");

                return $this->dispatcher->forward(
                    [
                        "controller" => "directors",
                        "action"     => "index",
                    ]
                );
            }

            $this->view->form = new DirectorsForm($director, ['edit' => true]);
        }
    }

    /**
     * Creates a new director
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "directors",
                    "action"     => "index",
                ]
            );
        }

        $form = new DirectorsForm;
        $director = new Directors();

        $data = $this->request->getPost();
        if (!$form->isValid($data, $director)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "directors",
                    "action"     => "new",
                ]
            );
        }

        if ($director->save() == false) {
            foreach ($director->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "directors",
                    "action"     => "new",
                ]
            );
        }

        $form->clear();

        $this->flash->success("Director was created successfully");

        return $this->dispatcher->forward(
            [
                "controller" => "directors",
                "action"     => "index",
            ]
        );
    }

    /**
     * Saves current director in screen
     *
     * @param string $id
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "directors",
                    "action"     => "index",
                ]
            );
        }

        $id = $this->request->getPost("id", "int");
        $director = Directors::findFirstById($id);
        if (!$director) {
            $this->flash->error("director does not exist");

            return $this->dispatcher->forward(
                [
                    "controller" => "directors",
                    "action"     => "index",
                ]
            );
        }

        $form = new DirectorsForm;

        $data = $this->request->getPost();
        if (!$form->isValid($data, $director)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "directors",
                    "action"     => "new",
                ]
            );
        }

        if ($director->save() == false) {
            foreach ($director->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "directors",
                    "action"     => "new",
                ]
            );
        }

        $form->clear();

        $this->flash->success("Director was updated successfully");

        return $this->dispatcher->forward(
            [
                "controller" => "directors",
                "action"     => "index",
            ]
        );
    }

    /**
     * Deletes a director
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $directors = Directors::findFirstById($id);
        if (!$directors) {
            $this->flash->error("Director was not found");

            return $this->dispatcher->forward(
                [
                    "controller" => "directors",
                    "action"     => "index",
                ]
            );
        }

        if (!$directors->delete()) {
            foreach ($directors->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "directors",
                    "action"     => "search",
                ]
            );
        }

        $this->flash->success("director was deleted");

        return $this->dispatcher->forward(
            [
                "controller" => "directors",
                "action"     => "index",
            ]
        );
    }
}

