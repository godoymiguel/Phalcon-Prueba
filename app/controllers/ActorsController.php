<?php

use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

/**
 * ActorsController
 *
 * Manage CRUD operations for actors
 */
class ActorsController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Manage your actors');
        parent::initialize();
    }

    /**
     * Shows the index action
     */
    public function indexAction()
    {
        $this->session->conditions = null;
        $this->view->form = new ActorsForm;
    }

    /**
     * Search Actors based on current criteria
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, "Actors", $this->request->getPost());
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $actors = Actors::find($parameters);
        if (count($actors) == 0) {
            $this->flash->notice("The search did not find any actors");

            return $this->dispatcher->forward(
                [
                    "controller" => "actors",
                    "action"     => "index",
                ]
            );
        }

        $paginator = new Paginator(array(
            "data"  => $actors,
            "limit" => 10,
            "page"  => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();
    }

    /**
     * Shows the form to create a new actor
     */
    public function newAction()
    {
        $this->view->form = new ActorsForm(null, array('edit' => true));
    }

    /**
     * Edits a actor based on its id
     */
    public function editAction($id)
    {

        if (!$this->request->isPost()) {

            $actor = Actors::findFirstById($id);
            if (!$actor) {
                $this->flash->error("Actors was not found");

                return $this->dispatcher->forward(
                    [
                        "controller" => "actors",
                        "action"     => "index",
                    ]
                );
            }

            $this->view->form = new ActorsForm($actor, array('edit' => true));
        }
    }

    /**
     * Creates a new actor
     */
    public function createAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "actors",
                    "action"     => "index",
                ]
            );
        }

        $form = new ActorsForm;
        $actor = new Actors();

        $data = $this->request->getPost();
        if (!$form->isValid($data, $actor)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "actors",
                    "action"     => "new",
                ]
            );
        }

        if ($actor->save() == false) {
            foreach ($actor->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "actors",
                    "action"     => "new",
                ]
            );
        }

        $form->clear();

        $this->flash->success("Actor was created successfully");

        return $this->dispatcher->forward(
            [
                "controller" => "actors",
                "action"     => "index",
            ]
        );
    }

    /**
     * Saves current actor in screen
     *
     * @param string $id
     */
    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(
                [
                    "controller" => "actors",
                    "action"     => "index",
                ]
            );
        }

        $id = $this->request->getPost("id", "int");

        $actor = Actors::findFirstById($id);
        if (!$actor) {
            $this->flash->error("Actor does not exist");

            return $this->dispatcher->forward(
                [
                    "controller" => "actors",
                    "action"     => "index",
                ]
            );
        }

        $form = new ActorsForm;
        $this->view->form = $form;

        $data = $this->request->getPost();

        if (!$form->isValid($data, $actor)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "actors",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }

        if ($actor->save() == false) {
            foreach ($actor->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "actors",
                    "action"     => "edit",
                    "params"     => [$id]
                ]
            );
        }

        $form->clear();

        $this->flash->success("Actors was updated successfully");

        return $this->dispatcher->forward(
            [
                "controller" => "actors",
                "action"     => "index",
            ]
        );
    }

    /**
     * Deletes a Actors
     *
     * @param string $id
     */
    public function deleteAction($id)
    {

        $actors = Actors::findFirstById($id);
        if (!$actors) {
            $this->flash->error("Actor was not found");

            return $this->dispatcher->forward(
                [
                    "controller" => "actors",
                    "action"     => "index",
                ]
            );
        }

        if (!$actors->delete()) {
            foreach ($actors->getMessages() as $message) {
                $this->flash->error($message);
            }

            return $this->dispatcher->forward(
                [
                    "controller" => "actors",
                    "action"     => "search",
                ]
            );
        }

        $this->flash->success("Actor was deleted");

            return $this->dispatcher->forward(
                [
                    "controller" => "actors",
                    "action"     => "index",
                ]
            );
    }
}
