<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

/**
 * FilmsController
 *
 * Manage CRUD operations for films
 */
class FilmsController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Manage your films');
        parent::initialize();
    }

    /**
     * Shows the index action
     */
    public function indexAction()
    {
        $this->session->conditions = null;
        $this->view->form = new FilmsForm;
    }

    /**
     * Search Films based on current criteria
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Films", $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $films = Films::find($parameters);
        if (count($films) == 0) {
            $this->flash->notice("The search did not find any films");

            return $this->dispatcher->forward(
                [
                    "controller" => "films",
                    "action"     => "index",
                ]
            );
        }

        $paginator = new Paginator(array(
            "data"  => $films,
            "limit" => 10,
            "page"  => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Shows the form to create a new film
     */
    public function newAction()
    {
        $this->view->form = new FilmsForm(null, array('edit' => true));
    }

    /**
     * Edits a film based on its id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $film = Films::findFirstById($id);
            if (!$film) {
                $this->flash->error("Films was not found");

                return $this->dispatcher->forward(
                    [
                        "controller" => "films",
                        "action"     => "index",
                    ]
                );
            }

            $this->view->form = new FilmsForm($film, array('edit' => true));
        }
    }

    /**
     * Creates a new film
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "films",
                    "action"     => "index",
                ]
            );
        }

        $form = new FilmsForm;
        $film = new Films();

        $data = $this->request->getPost();
        if (!$form->isValid($data, $film)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "films",
                    "action"     => "new",
                ]
            );
        }

        if ($film->save() == false) {
            foreach ($film->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "films",
                    "action"     => "new",
                ]
            );
        }

        $form->clear();

        $this->flash->success("Film was created successfully");

        return $this->dispatcher->forward(
            [
                "controller" => "films",
                "action"     => "index",
            ]
        );
    }

    /**
     * Saves current film in screen
     *
     * @param string $id
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "films",
                    "action"     => "index",
                ]
            );
        }

        $id = $this->request->getPost("id", "int");

        $film = Films::findFirstById($id);
        if (!$film) {
            $this->flash->error("Film does not exist");

            return $this->dispatcher->forward(
                [
                    "controller" => "films",
                    "action"     => "index",
                ]
            );
        }

        $form = new FilmsForm;
        $this->view->form = $form;

        $data = $this->request->getPost();

        if (!$form->isValid($data, $film)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "films",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }

        if ($film->save() == false) {
            foreach ($film->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "films",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }

        $form->clear();

        $this->flash->success("Films was updated successfully");

        return $this->dispatcher->forward(
            [
                "controller" => "films",
                "action"     => "index",
            ]
        );
    }

    /**
     * Deletes a Films
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $films = Films::findFirstById($id);
        if (!$films) {
            $this->flash->error("film was not found");

            return $this->dispatcher->forward(
                [
                    "controller" => "films",
                    "action"     => "index",
                ]
            );
        }

        if (!$films->delete()) {
            foreach ($films->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "films",
                    "action"     => "search",
                ]
            );
        }

        $this->flash->success("Film was deleted");

            return $this->dispatcher->forward(
                [
                    "controller" => "films",
                    "action"     => "index",
                ]
            );
    }
}
