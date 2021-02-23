<?php

namespace App\Controller\Component;

use App\Model\Entity\Flyer;
use Cake\Controller\Component;

class CsvDatasourceComponent extends Component{
    /**
     * @var resource
     */
    protected $csvHandle;
    protected $columns;

    /**
     * Function to manage eventualy multiple csv file.
     *
     * @param string $typeRequest
     *
     * @return string
     */
    protected function csvPath($typeRequest = '') : string{
        switch($typeRequest){
            case 'type1':
                return 'csv1.csv';
            case 'type2':
                return 'csv2.csv';
            default:
                return 'resources/flyers_data.csv';
        }
    }

    /**
     *
     * Open CSV and save the list of columns
     *
     * @param $typeRequest
     *
     * @return bool
     */
    public function openCsv($typeRequest = '') : bool{
        try{
            $this->csvHandle = fopen($this->csvPath($typeRequest), 'r');
            $row = 1;
            while(($line = fgetcsv($this->csvHandle)) !== false){
                $this->columns = $line;
                $row++;
                if($row > 1){
                    break;
                }
            }
            return true;
        }catch(\Exception $e){
            return false;
        }
    }

    /**
     *
     * Start the search. The file is scrolled until lines matching the search and is in the requested range: only those are saved in memory.
     * The procedure stops once all the results of the required range have been loaded.
     *
     * @param     $filters
     * @param     $strFields
     * @param int $page
     * @param int $limit
     *
     * @return array
     */
    public function csvSearchResoults($filters, $strFields, $page = 1, $limit = 50) : array{
        $results = [];
        $stop = $limit*$page;
        $start = $limit * ($page-1);
        $fields = explode(',',$strFields);
        $i=0;
        var_dump($start.' '.$stop);
        while(($row = fgetcsv($this->csvHandle)) !== false){
           $flyer = new Flyer($this->columns,$row);

           if($flyer->isSearched($filters)){

               if($i< $stop && $i>= $start)
               {   $result = [];
                   foreach($fields as $f){
                       $result[$f] = $flyer->getField($f);
                   }
                   $results[]=$result;

               }
               $i++;

           }
           if($i>$stop){
               break;
           }
        }
        fclose($this->csvHandle);
        return $results;
    }

    /**
     * @return resource
     */
    public function getCsvHandle(){
        return $this->csvHandle;
    }

    /**
     * @return mixed
     */
    public function getAllowedColumns(){
        return $this->columns;
    }
}
