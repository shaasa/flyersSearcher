<?php
// src/Model/Entity/Flyer.php
namespace App\Model\Entity;

/**
 * Class Flyer
 *
 * @package App\Model\Entity
 */
class Flyer{
    protected $fields;

    /**
     * Flyer constructor.
     * The EntityInterface, are specified for Database connection, but for me it's useless, so I preferred to redefine a indipendent class
     * for the entity Flyer.
     *
     * @param $fields
     * @param $values
     */
    public function __construct($fields, $values){
        $i = 0;

       //Initializes only the fields passed in the search parameters
        foreach($fields as $f){
            $this->fields[$f] = $values[$i];
            $i++;
        }
    }

    /**
     * Check if the flyer is active
     *
     * @return bool
     */
    public function isActive() : bool{
        return ($this->fields['start_date'] <= date('Y-m-d') && $this->fields['end_date'] >= date('Y-m-d'));
    }

    /**
     * Check that the flyer matches on search parameters
     *
     *
     * @param $filters
     *
     * @return bool
     */
    public function isSearched($filters) : bool{
        $searched = true;
        foreach($filters as $name => $value){
            if($this->fields[$name] != $value){
                $searched = false;
                break;
            }
        }

        return ($this->isActive() && $searched);
    }

    /**
     * @return mixed
     */
    public function getFields(){
        return $this->fields;
    }

    /**
     * @param $key
     *
     * @return mixed
     */
    public function getField($key){
        return $this->fields[$key];
    }
}
