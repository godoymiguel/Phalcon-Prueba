<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

class ActorsForm extends Form
{
    /**
     * Initialize the Actors form
     */
    public function initialize($entity = null, $options = [])
    {
        if (!isset($options['edit'])) {
            $element = new Text("id");
            $this->add($element->setLabel("Id"));
        } else {
            $this->add(new Hidden("id"));
        }

        $name = new Text("name");
        $name->setLabel("Name");
        $name->setFilters(['striptags', 'string']);
        $name->addValidators([
            new PresenceOf([
                'message' => 'Name is required'
            ])
        ]);
        $this->add($name);

        $film = new Select('film_id', Films::find(), [
            'using'      => ['id', 'name'],
            'useEmpty'   => true,
            'emptyText'  => '...',
            'emptyValue' => ''
        ]);
        $film->setLabel('Film');
        $this->add($film);

        $telephone = new Text("telephone");
        $telephone->setLabel("Telephone");
        $telephone->setFilters(['striptags', 'string']);
        $telephone->addValidators([
            new PresenceOf([
                'message' => 'Telephone is required'
            ])
        ]);
        $this->add($telephone);

        $address = new Text("address");
        $address->setLabel("address");
        $address->setFilters(['striptags', 'string']);
        $address->addValidators([
            new PresenceOf([
                'message' => 'Address is required'
            ])
        ]);
        $this->add($address);

        $city = new Text("city");
        $city->setLabel("city");
        $city->setFilters(['striptags', 'string']);
        $city->addValidators([
            new PresenceOf([
                'message' => 'City is required'
            ])
        ]);
        $this->add($city);
    }
}