<?php
// src/Model/Entity/Flyer.php
namespace App\Model\Entity;

/**
 * @method bool[] getAccessible()
 */
class Flyer{
    protected $fields;

    public function __construct($fields, $values){
        $i = 0;
        foreach($fields as $f){
            $this->fields[$f] = $values[$i];
            $i++;
        }
    }

    public function isActive() : bool{
        return ($this->fields['start_date'] <= date('Y-m-d') && $this->fields['end_date'] >= date('Y-m-d'));
    }

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

    public function getField($key){
        return $this->fields[$key];
    }
}
