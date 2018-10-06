<?php

use Phalcon\Mvc\Model;

class Directors extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $telephone;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $city;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("invo");
        $this->setSource("directors");
        $this->hasOne(['id'], Directors::class, ['director_id'], [
            'foreignKey' => [
                'message' => 'film cannot be deleted because it\'s used in Director'
            ]
        ]);
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'directors';
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Directors[]|Directors|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Directors|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

}
