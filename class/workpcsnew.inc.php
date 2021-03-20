<?php

include_once "dbh.inc.php";
include_once "variables.inc.php";
include_once "density.inc.php";

function CheckUnitPrice($mat, $cid, $thick, $length) {

    ##matcode = 12379p
    debug_to_console("line 17, This is the CheckUnitPrice Function");
    debug_to_console($mat);
    debug_to_console($cid);

    $sql = "SELECT * FROM $mat ";
    //echo "line 24, \$sql3 = $sql3<br>";
    $objSql = new SQL($sql);
    $result = $objSql->getResultOneRowArray();

    if (isset($result['maxlength'])) {
        $priceGet = getLooseMaxPrice($result, $thick, $length);
    } else {//!isset($result['maxlength'])
        if (!isset($result['maxprice'])) {
            if (isset($result['price'])) {

                $priceGet = getunitPrice($result, $thick);
            }
        }
    }
    // echo "list array \$result <br>";
    // print_r($result);
    // echo "######################<br>";


    return $priceGet;
}

function getunitPrice($resultArray, $thick) {
    // echo "in Fucntion getunitPrice line 26 <br>";
    //echo "\$thick = $thick <br>";
//        echo "print_r(\$resultArray)";
//        print_r($resultArray);
//        echo "#####################<br>";
//        echo "\$thick = $thick <br>";
    foreach ($resultArray as $row) {
        # code...

        foreach ($row as $key => $value) {
            ${$key} = $value;
            //echo "$key : $value\n"."<br>";
            debug_to_console("$key => $value");
            $thickness = $row['thickness'];

            if ($thick == $thickness) {
                //    print "\$thick = $thick , \$thickness = $thickness <br>";
                $priceGet = floatval($row['price']);
                //echo "\$priceGet = $priceGet <br>";
                return $priceGet;
            }
        }
    }
}

Class WORKPCS2 {

    protected $dimensions;
    protected $density;
    protected $weight;
    protected $volume;
    protected $materialType; // such as 'ts' for tools steel type
    protected $materialcode; // material code or grade special key for linking to orderlist

    public function __construct() {

        $density = new DENSITY();
        $this->dimensions = [];
        //$this->density = 0.00;
        $this->weight = 0.00;
        $this->volume = 0.00;
        $this->materialType = "";
        $this->materialcode = "";
    }

    public function setDimension($array) {

        $this->dimensions = $array;
    }

    public function getDimension() {

        return $this->dimensions;
    }

    public function setDensity($input) {

        $this->density = $input;
    }

    public function getDensity() {

        return $this->density;
    }

    public function setWeight($input) {

        $this->weight = $input;
    }

    public function getWeight() {

        return $this->weight;
    }

    public function setVolume($input) {

        $this->volume = $input;
    }

    public function getVolume() {

        return $this->volume;
    }

    public function setMaterialType($input) {

        $this->materialType = $input;
    }

    public function getMaterialType() {

        return $this->materialType;
    }

    public function setMaterialCode($input) {

        $this->materialcode = $input;
    }

    public function getMaterialCode() {

        return $this->materialcode;
    }

//    public function calDensity($materialType) {
//
//
//        $sql = "SELECT plate from material_density WHERE materialtype = '$materialType' ";
//        $obj = new SQL($sql);
//        $result = $obj->getResultOneRowArray();
//        $density = $result['plate'];
//        $this->density = $density; // set the density into objext
//        return $density;
//    }

    public function grabDensitybyMType($materialType) {
        $densityValue = 0.00;

        switch ($materialType) {
            case 'ts':
                $densityValue = DENSITY::ts;
                break;
            case 'aa':
                $densityValue = DENSITY::aa;
                break;
            case 'ms':
                $densityValue = DENSITY::ms;
                break;
            case 'brass':
                $densityValue = DENSITY::brass;
                break;
            case 'copper':
                $densityValue = DENSITY::copper;
                break;
            case 'sus':
                $densityValue = DENSITY::sus;
                break;
            case 'hardox':
                $densityValue = DENSITY::copper;
                break;
            case 'bronze':
                $densityValue = DENSITY::bronze;
                break;
            case 'other':
                $densityValue = DENSITY::other;
                break;
            case 'graphite':
                $densityValue = DENSITY::graphite;
                break;
            case 'plastic':
                $densityValue = DENSITY::plastic;
                break;
            default:
                $densityValue = DENSITY::other;
                break;
        }
        return $densityValue;
    }

    public function grabDensityByMaterialcode() {

        $materialcode = $this->materialcode;
        $materialtype = $this->materialType;

        $sql = "SELECT * FROM material2020 WHERE materialcode = '$materialcode'";
        $objSql = new SQL($sql);
        $result = $objSql->getResultOneRowArray();
        $materialType = $result['materialtype'];
        $density = $this->grabDensitybyMType($materialType);
        $this->setDensity($density);
    }

    public function calMaterialType($materialcode) {


        $sql = "SELECT materialtype FROM material2020 WHERE materialcode = '{$materialcode}'";
        $obj = new SQL($sql);
        $result = $obj->getResultOneRowArray();
        #print_r($result);
        $materialtype = $result['materialtype'];
        $this->materialType = $materialtype;
        return $materialtype;
    }

}

Class ROD extends WORKPCS2 {

    protected $category;

    public function __construct() {

        $this->category = 'rod';
    }

}

Class O extends ROD {

    protected $Shape_Code;
    protected $PHI; // diameter of the rod
    protected $L; //Length
    protected $materialcode;
    protected $dimension;

    //protected $myDimension;

    function __construct($materialcode, $PHI, $L) {

        $this->Shape_Code = "O"; // shape code O, circular type O cross section area; Shaft
        $this->PHI = $PHI;
        $this->L = $L;
        $this->materialcode = $materialcode;
        $dimension = $this->formDimension();
        $this->dimension = $dimension;
        $this->mainProcess();
    }

    private function mainProcess() {
        $materialcode = $this->getMaterialCode();
        $PHI = $this->getPHI();
        $L = $this->getL();
        $Shape_Code = $this->getShape_Code();
        $isShapeCodeMatch = $this->isShapeCodeMatch($materialcode);
        if ($isShapeCodeMatch !== 'yes') {
            echo "Material with code : $materialcode have different Shape_Code than current process";
        } else {
            //Shape_Code matches, go to next step
            $materialType = $this->getMaterialType();
            $density = $this->grabDensitybyMType($materialType);
            $this->setDensity($density);
            $volume = $this->calVolume();
            $this->setVolume($volume);
            $weight = $this->calWeight();
            echo "\$volume = $volume<br>";
            echo "\$weight = $weight<br>";
            $this->setWeight($weight);
        }
    }

    public function formDimension() {

        $PHI = $this->PHI;
        $L = $this->L;
        $Dimension = array('PHI' => $PHI, 'L' => $L);
        return $Dimension;
    }

    /**
      public function extractDimension() {

      $dimension = $this->myDimension; //pointing local variable $dimension to assign scope variable  $this->myDimension into it.
      $PHI = $dimension['0'];
      $L = $dimension['1'];

      $this->PHI = $PHI;
      $this->L = $L;
      //create new array, with keys
      $newDimension = array('PHI' => $PHI, 'L' => $L);

      return $newDimension;
      }
     * */
    public function isShapeCodeMatch() {

        $materialcode = $this->materialcode;
        $sql = "SELECT Shape_Code FROM material2020 WHERE materialcode = '{$materialcode}'";
        $obj = new SQL($sql);
        $result = $obj->getResultOneRowArray();
//        print_r($result);
        $myShape_Code = $result['Shape_Code'];
        $Shape_Code = $this->Shape_Code;
//        $this->setShape_Code($Shape_Code);

        if ($myShape_Code == $Shape_Code) {
            $answer = 'yes';
        } else {
            $answer = 'no';
        }

        return $answer;
    }

    public function getShape_Code() {
        return $this->Shape_Code;
    }

    public function setShape_Code($input) {
        $this->Shape_Code = $input;
    }

    public function getPHI() {
        return $this->PHI;
    }

    public function setPHI($input) {
        $this->PHI = $input;
    }

    public function getL() {
        return $this->L;
    }

    public function setL($input) {
        $this->L = $input;
    }

    public function calVolume() {

        $PHI = floatval($this->PHI);
        $L = floatval($this->L);
        $radius = $PHI / 2;
        $area = pi() * (pow($radius, 2));
        $vol = $area * $L;

        #echo "in line 267, \$vol = $vol <br>";
        $this->setVolume($vol);

        return $vol;
    }

    public function grabDensityByMaterialcode() {

        $materialcode = $this->materialcode;
        $materialtype = $this->materialType;

        $sql = "SELECT * FROM material2020 WHERE materialcode = '$materialcode'";
        $objSql = new SQL($sql);
        $result = $objSql->getResultOneRowArray();
        $materialType = $result['materialtype'];
        $density = $this->grabDensitybyMType($materialType);
        $this->setDensity($density);
    }

    public function calWeight() {
        $density = $this->density;
        $volume = $this->getVolume();

        #    echo "<br>=================================================<br>";
        #    echo "\$density = $density ,  \$volume = $volume <br> ";

        $weight = $volume * $density;
        $this->setWeight($weight);

        return $weight;
    }

}

Class HEX extends ROD {

    protected $Shape_Code;
    protected $HEX; // corss section
    protected $L; //Length
    protected $materialcode;
    protected $dimension;

    //protected $mydimension;

    function __construct($materialcode, $HEX, $L) {

        $this->Shape_Code = "HEX"; // shape code O, circular type O cross section area; Shaft
        $this->HEX = $HEX;
        $this->L = $L;
        $this->materialcode = $materialcode;
        $dimension = $this->formDimension();
        $this->dimension = $dimension;
        $this->mainProcess();
    }

    private function mainProcess() {
        $materialcode = $this->getMaterialCode();
        $HEX = $this->getHEX();
        $L = $this->getL();
        $Shape_Code = $this->getShape_Code();
        $isShapeCodeMatch = $this->isShapeCodeMatch($materialcode);
        if ($isShapeCodeMatch !== 'yes') {
            echo "Material with code : $materialcode have different Shape_Code than current process";
        } else {
            //Shape_Code matches, go to next step
            $materialType = $this->getMaterialType();
            $density = $this->grabDensitybyMType($materialType);
            $this->setDensity($density);
            $volume = $this->calVolume();
            $this->setVolume($volume);
            $weight = $this->calWeight();
            echo "\$volume = $volume<br>";
            echo "\$weight = $weight<br>";
            $this->setWeight($weight);
        }
    }

    public function formDimension() {

        $HEX = $this->HEX;
        $L = $this->L;
        $Dimension = array('HEX' => $HEX, 'L' => $L);
        return $Dimension;
    }

    /**
      public function extractDimension(){
      $dimension = $this->myDimension;

      $HEX = $dimension['0'];
      $L = $dimension['1'];

      $this->HEX = $HEX;
      $this->L = $L;

      $newDimension = array('HEX' => $HEX, 'L' => $L);

      return $newDimension;
      }
     * */
    public function isShapeCodeMatch($materialcode) {

        $sql = "SELECT Shape_Code FROM material2020 WHERE materialcode = '{$materialcode}'";
        $obj = new SQL($sql);
        $result = $obj->getResultOneRowArray();
        print_r($result);
        $myShape_Code = $result['Shape_Code'];
        $Shape_Code = $this->Shape_Code;
//        $this->setShape_Code($Shape_Code);

        if ($myShape_Code == $Shape_Code) {
            $answer = 'yes';
        } else {
            $answer = 'no';
        }

        return $answer;
    }

    public function getShape_Code() {
        return $this->Shape_Code;
    }

    public function setShape_Code($input) {
        $this->Shape_Code = $input;
    }

    public function getHEX() {
        return $this->HEX;
    }

    public function setHEX($input) {
        $this->HEX = $input;
    }

    public function getL() {
        return $this->L;
    }

    public function setL($input) {
        $this->L = $input;
    }

    public function calVolume() {

        $HEX = floatval($this->HEX);
        $L = floatval($this->L);

        $Side = ($HEX * 2) / sqrt(3);
        $area = (pow($Side, 2) * (3 * sqrt(3))) / 2;
        $vol = $area * $L;

        #echo "in line 267, \$vol = $vol <br>";
        $this->setVolume($vol);

        return $vol;
    }

    public function grabDensityByMaterialcode() {

        $materialcode = $this->materialcode;
        $materialtype = $this->materialType;

        $sql = "SELECT * FROM material2020 WHERE materialcode = '$materialcode'";
        $objSql = new SQL($sql);
        $result = $objSql->getResultOneRowArray();
        $materialType = $result['materialtype'];
        $density = $this->grabDensitybyMType($materialType);
        $this->setDensity($density);
    }

    public function calWeight() {
        $density = $this->density;
        $volume = $this->getVolume();

        # echo "<br>=================================================<br>";
        # echo "\$density = $density ,  \$volume = $volume <br> ";

        $weight = $volume * $density;
        $this->setWeight($weight);

        return $weight;
    }

}

Class SS extends ROD {

    protected $Shape_Code;
    protected $W1; // 1st side width
    protected $W2; // 2nd Side Width
    protected $L; //Length
    protected $materialcode;
    protected $dimension;

    //protected $mydimension;

    function __construct($materialcode, $W1, $W2, $L) {

        $this->Shape_Code = "SS"; // shape code O, circular type O cross section area; Shaft
        $this->W1 = $W1;
        $this->W2 = $W2;
        $this->L = $L;
        $this->materialcode = $materialcode;
        $dimension = $this->formDimension();
        $this->dimension = $dimension;
        $this->mainProcess();
    }

    private function mainProcess() {
        $materialcode = $this->getMaterialCode();
        $W1 = $this->getW1();
        $W2 = $this->getW2();
        $L = $this->getL();
        $Shape_Code = $this->getShape_Code();
        $isShapeCodeMatch = $this->isShapeCodeMatch($materialcode);
        if ($isShapeCodeMatch !== 'yes') {
            echo "Material with code : $materialcode have different Shape_Code than current process";
        } else {
            //Shape_Code matches, go to next step
            $materialType = $this->getMaterialType();
            $density = $this->grabDensitybyMType($materialType);
            $this->setDensity($density);
            $volume = $this->calVolume();
            $this->setVolume($volume);
            $weight = $this->calWeight();
            echo "\$volume = $volume<br>";
            echo "\$weight = $weight<br>";
            $this->setWeight($weight);
        }
    }

    public function formDimension() {
        $W1 = $this->W1;
        $W2 = $this->W2;
        $L = $this->L;
        $Dimension = array('W1' => $W1, 'W2' => $W2, 'L' => $L);
        return $Dimension;
    }

    /**
      public function extractDimension(){
      $dimension = $this->myDimension;

      $W1 = $dimension['0'];
      $W2 = $dimension['1'];
      $L = $dimension['2'];

      $this->W1 = $W1;
      $this->W2 = $W2;
      $this->L = $L;

      $newDimension = array('W1' => $ID, 'W2' => $OD, 'L' => $L);

      return $newDimension;
      }
     * */
    public function isShapeCodeMatch($materialcode) {

        $sql = "SELECT Shape_Code FROM material2020 WHERE materialcode = '{$materialcode}'";
        $obj = new SQL($sql);
        $result = $obj->getResultOneRowArray();
        print_r($result);
        $myShape_Code = $result['Shape_Code'];
        $Shape_Code = $this->Shape_Code;
//        $this->setShape_Code($Shape_Code);

        if ($myShape_Code == $Shape_Code) {
            $answer = 'yes';
        } else {
            $answer = 'no';
        }

        return $answer;
    }

    public function getShape_Code() {
        return $this->Shape_Code;
    }

    public function setShape_Code($input) {
        $this->Shape_Code = $input;
    }

    public function getW1() {
        return $this->W1;
    }

    public function setW1($input) {
        $this->W1 = $input;
    }

    public function getW2() {
        return $this->W2;
    }

    public function setW2($input) {
        $this->W2 = $input;
    }

    public function getL() {
        return $this->L;
    }

    public function setL($input) {
        $this->L = $input;
    }

    public function calVolume() {

        $W1 = floatval($this->W1);
        $W2 = floatval($this->W2);
        $L = floatval($this->L);

        $area = $W1 * $W2;

        $vol = $area * $L;

        #echo "in line 267, \$vol = $vol <br>";
        $this->setVolume($vol);

        return $vol;
    }

    public function grabDensityByMaterialcode() {

        $materialcode = $this->materialcode;
        $materialtype = $this->materialType;

        $sql = "SELECT * FROM material2020 WHERE materialcode = '$materialcode'";
        $objSql = new SQL($sql);
        $result = $objSql->getResultOneRowArray();
        $materialType = $result['materialtype'];
        $density = $this->grabDensitybyMType($materialType);
        $this->setDensity($density);
    }

    public function calWeight() {
        $density = $this->density;
        $volume = $this->getVolume();

        #    echo "<br>=================================================<br>";
        #    echo "\$density = $density ,  \$volume = $volume <br> ";

        $weight = $volume * $density;
        $this->setWeight($weight);

        return $weight;
    }

}

Class A extends ROD {

    protected $Shape_Code;
    protected $T; //thickness
    protected $W1; // 1st side width
    protected $W2; // 2nd Side Width
    protected $L; //Length
    protected $materialcode;
    protected $dimension;

    //protected $mydimension;

    function __construct($materialcode, $T, $W1, $W2, $L) {

        $this->Shape_Code = "SS"; // shape code O, circular type O cross section area; Shaft
        $this->T = $T;
        $this->W1 = $W1;
        $this->W2 = $W2;
        $this->L = $L;
        $this->materialcode = $materialcode;
        $dimension = $this->formDimension();
        $this->dimension = $dimension;
        $this->mainProcess();
    }

    private function mainProcess() {
        $materialcode = $this->getMaterialCode();
        $T = $this->getT();
        $W1 = $this->getW1();
        $W2 = $this->getW2();
        $L = $this->getL();
        $Shape_Code = $this->getShape_Code();
        $isShapeCodeMatch = $this->isShapeCodeMatch($materialcode);
        if ($isShapeCodeMatch !== 'yes') {
            echo "Material with code : $materialcode have different Shape_Code than current process";
        } else {
            //Shape_Code matches, go to next step
            $materialType = $this->getMaterialType();
            $density = $this->grabDensitybyMType($materialType);
            $this->setDensity($density);
            $volume = $this->calVolume();
            $this->setVolume($volume);
            $weight = $this->calWeight();
            echo "\$volume = $volume<br>";
            echo "\$weight = $weight<br>";
            $this->setWeight($weight);
        }
    }

    public function formDimension() {
        $T = $this->T;
        $W1 = $this->W1;
        $W2 = $this->W2;
        $L = $this->L;
        $Dimension = array('T' => $T, 'W1' => $W1, 'W2' => $W2, 'L' => $L);
        return $Dimension;
    }

    /**
      public function extractDimension(){
      $dimension = $this->myDimension;

      $W1 = $dimension['0'];
      $W2 = $dimension['1'];
      $L = $dimension['2'];

      $this->W1 = $W1;
      $this->W2 = $W2;
      $this->L = $L;

      $newDimension = array('W1' => $ID, 'W2' => $OD, 'L' => $L);

      return $newDimension;
      }
     * */
    public function isShapeCodeMatch($materialcode) {

        $sql = "SELECT Shape_Code FROM material2020 WHERE materialcode = '{$materialcode}'";
        $obj = new SQL($sql);
        $result = $obj->getResultOneRowArray();
        print_r($result);
        $myShape_Code = $result['Shape_Code'];
        $Shape_Code = $this->Shape_Code;
//        $this->setShape_Code($Shape_Code);

        if ($myShape_Code == $Shape_Code) {
            $answer = 'yes';
        } else {
            $answer = 'no';
        }

        return $answer;
    }

    public function getShape_Code() {
        return $this->Shape_Code;
    }

    public function setShape_Code($input) {
        $this->Shape_Code = $input;
    }

    public function getT() {
        return $this->T;
    }

    public function setT($input) {
        $this->T = $input;
    }

    public function getW1() {
        return $this->W1;
    }

    public function setW1($input) {
        $this->W1 = $input;
    }

    public function getW2() {
        return $this->W2;
    }

    public function setW2($input) {
        $this->W2 = $input;
    }

    public function getL() {
        return $this->L;
    }

    public function setL($input) {
        $this->L = $input;
    }

    public function calVolume() {
        $T = floatval($this->T);
        $W1 = floatval($this->W1);
        $W2 = floatval($this->W2);
        $L = floatval($this->L);

        $oSide = $W1 * $W2;
        $iSide = ($W1 - $T) * ($W2 - $T);

        $area = $oSide - $iSide;
        $vol = $area * $L;

        #echo "in line 267, \$vol = $vol <br>";
        $this->setVolume($vol);

        return $vol;
    }

    public function grabDensityByMaterialcode() {

        $materialcode = $this->materialcode;
        $materialtype = $this->materialType;

        $sql = "SELECT * FROM material2020 WHERE materialcode = '$materialcode'";
        $objSql = new SQL($sql);
        $result = $objSql->getResultOneRowArray();
        $materialType = $result['materialtype'];
        $density = $this->grabDensitybyMType($materialType);
        $this->setDensity($density);
    }

    public function calWeight() {
        $density = $this->density;
        $volume = $this->getVolume();

        #    echo "<br>=================================================<br>";
        #    echo "\$density = $density ,  \$volume = $volume <br> ";

        $weight = $volume * $density;
        $this->setWeight($weight);

        return $weight;
    }

}

Class TUBE extends WORKPCS2 {

    protected $category;

    public function __construct() {

        $this->category = 'tube';
    }

}

Class HP extends TUBE {

    protected $Shape_Code;
    protected $ID; //Inner Diameter
    protected $OD; //Outer Diameter
    protected $L; //Length
    protected $materialcode;
    protected $dimension;

    //protected $mydimension;

    function __construct($materialcode, $ID, $OD, $L) {

        $this->Shape_Code = "HP"; // shape code O, circular type O cross section area; Shaft
        $this->ID = $ID;
        $this->OD = $OD;
        $this->L = $L;
        $this->materialcode = $materialcode;
        $dimension = $this->formDimension();
        $this->dimension = $dimension;
        $this->mainProcess();
    }

    private function mainProcess() {
        $materialcode = $this->getMaterialCode();
        $L = $this->getL();
        $ID = $this->getID();
        $OD = $this->getOD();
        $Shape_Code = $this->getShape_Code();
        $isShapeCodeMatch = $this->isShapeCodeMatch($materialcode);
        if ($isShapeCodeMatch !== 'yes') {
            echo "Material with code : $materialcode have different Shape_Code than current process";
        } else {
            //Shape_Code matches, go to next step
            $materialType = $this->getMaterialType();
            $density = $this->grabDensitybyMType($materialType);
            $this->setDensity($density);
            $volume = $this->calVolume();
            $this->setVolume($volume);
            $weight = $this->calWeight();
            echo "\$volume = $volume<br>";
            echo "\$weight = $weight<br>";
            $this->setWeight($weight);
        }
    }

    public function formDimension() {

        $ID = $this->ID;
        $OD = $this->OD;
        $L = $this->L;
        $Dimension = array('ID' => $ID, 'OD' => $OD, 'L' => $L);
        return $Dimension;
    }

    public function isShapeCodeMatch($materialcode) {

        $sql = "SELECT Shape_Code FROM material2020 WHERE materialcode = '{$materialcode}'";
        $obj = new SQL($sql);
        $result = $obj->getResultOneRowArray();
        print_r($result);
        $myShape_Code = $result['Shape_Code'];
        $Shape_Code = $this->Shape_Code;
//        $this->setShape_Code($Shape_Code);

        if ($myShape_Code == $Shape_Code) {
            $answer = 'yes';
        } else {
            $answer = 'no';
        }

        return $answer;
    }

    /**
      public function extractDimension(){
      $dimension = $this->myDimension;

      $ID = $dimension['0'];
      $OD = $dimension['1'];
      $L = $dimension['2'];

      $this->ID = $ID;
      $this->OD = $OD;
      $this->L = $L;

      $newDimension = array('ID' => $ID, 'OD' => $OD, 'L' => $L);

      return $newDimension;
      }
     * */
    public function getShape_Code() {
        return $this->Shape_Code;
    }

    public function setShape_Code($input) {
        $this->Shape_Code = $input;
    }

    public function getID() {
        return $this->ID;
    }

    public function setID($input) {
        $this->ID = $input;
    }

    public function getOD() {
        return $this->OD;
    }

    public function setOD($input) {
        $this->OD = $input;
    }

    public function getL() {
        return $this->L;
    }

    public function setL($input) {
        $this->L = $input;
    }

    public function calVolume() {

        $ID = floatval($this->ID);
        $OD = floatval($this->OD);
        $L = floatval($this->L);

        $oRadius = $OD / 2;
        $iRadius = $ID / 2;

        $oArea = pi() * pow($oRadius, 2);
        $iArea = pi() * pow($iRadius, 2);

        $vol = ($oArea - $iArea) * $L;

        #echo "in line 267, \$vol = $vol <br>";
        $this->setVolume($vol);

        return $vol;
    }

    public function grabDensityByMaterialcode() {

        $materialcode = $this->materialcode;
        $materialtype = $this->materialType;

        $sql = "SELECT * FROM material2020 WHERE materialcode = '$materialcode'";
        $objSql = new SQL($sql);
        $result = $objSql->getResultOneRowArray();
        $materialType = $result['materialtype'];
        $density = $this->grabDensitybyMType($materialType);
        $this->setDensity($density);
    }

    public function calWeight() {
        $density = $this->density;
        $volume = $this->getVolume();

        #    echo "<br>=================================================<br>";
        #    echo "\$density = $density ,  \$volume = $volume <br> ";

        $weight = $volume * $density;
        $this->setWeight($weight);

        return $weight;
    }

}

Class HS extends TUBE {

    protected $Shape_Code;
    protected $T; //Thickness
    protected $W1; //1st Inner Width
    protected $W2; //2nd Inner Width
    protected $L; //Length
    protected $materialcode;
    protected $dimension;

    //protected $myDimension;

    function __construct($materialcode, $T, $W1, $W2, $L) {
        parent::__construct();
        $this->Shape_Code = "HS"; // shape code O, circular type O cross section area; Shaft
        $this->T = $T;
        $this->W1 = $W1;
        $this->W2 = $W2;
        $this->L = $L;
        $this->materialcode = $materialcode;
        $dimension = $this->formDimension();
        $this->dimension = $dimension;
        $this->mainProcess();
    }

    private function mainProcess() {
        $materialcode = $this->getMaterialCode();
        $T = $this->getT();
        $W1 = $this->getW1();
        $W2 = $this->getW2();
        $L = $this->getL();
        $Shape_Code = $this->getShape_Code();
        $isShapeCodeMatch = $this->isShapeCodeMatch($materialcode);
        if ($isShapeCodeMatch !== 'yes') {
            echo "Material with code : $materialcode have different Shape_Code than current process";
        } else {
            //Shape_Code matches, go to next step
            $materialType = $this->getMaterialType();
            $density = $this->grabDensitybyMType($materialType);
            $this->setDensity($density);
            $volume = $this->calVolume();
            $this->setVolume($volume);
            $weight = $this->calWeight();
            echo "\$volume = $volume<br>";
            echo "\$weight = $weight<br>";
            $this->setWeight($weight);
        }
    }

    public function formDimension() {
        $T = $this->T;
        $W1 = $this->W1;
        $W2 = $this->W2;
        $L = $this->L;
        $Dimension = array('T' => $T, 'W1' => $W1, 'W2' => $W2, 'L' => $L);
        return $Dimension;
    }

    public function extractDimension() {
        $dimension = $this->myDimension;

        $T = $dimension['0'];
        $W1 = $dimension['1'];
        $W2 = $dimension['2'];
        $L = $dimension['3'];

        $this->T = $T;
        $this->W1 = $W1;
        $this->W2 = $W2;
        $this->L = $L;

        $newDimension = array('T' => $T, 'W1' => $W1, 'W2' => $W2, 'L' => $L);

        return $newDimension;
    }

    public function isShapeCodeMatch($materialcode) {

        $sql = "SELECT Shape_Code FROM material2020 WHERE materialcode = '{$materialcode}'";
        $obj = new SQL($sql);
        $result = $obj->getResultOneRowArray();
        print_r($result);
        $myShape_Code = $result['Shape_Code'];
        $Shape_Code = $this->Shape_Code;
//        $this->setShape_Code($Shape_Code);

        if ($myShape_Code == $Shape_Code) {
            $answer = 'yes';
        } else {
            $answer = 'no';
        }

        return $answer;
    }

    public function getShape_Code() {
        return $this->Shape_Code;
    }

    public function setShape_Code($input) {
        $this->Shape_Code = $input;
    }

    public function getT() {
        return $this->T;
    }

    public function setT($input) {
        $this->T = $input;
    }

    public function getW1() {
        return $this->W1;
    }

    public function setW1($input) {
        $this->W1 = $input;
    }

    public function getW2() {
        return $this->W2;
    }

    public function setW2($input) {
        $this->W2 = $input;
    }

    public function getL() {
        return $this->L;
    }

    public function setL($input) {
        $this->L = $input;
    }

    public function calVolume() {
        $T = floatval($this->T);
        $W1 = floatval($this->W1);
        $W2 = floatval($this->W2);
        $L = floatval($this->L);

        $sect1 = $W1 - 2 * $T;
        $sect2 = $W2 - 2 * $T;

        $area = ($W1 * $W2) - ($sect1 * $sect2);

        $vol = $area * $L;

        #echo "in line 267, \$vol = $vol <br>";
        $this->setVolume($vol);

        return $vol;
    }

    public function grabDensityByMaterialcode() {

        $materialcode = $this->materialcode;
        $materialtype = $this->materialType;

        $sql = "SELECT * FROM material2020 WHERE materialcode = '$materialcode'";
        $objSql = new SQL($sql);
        $result = $objSql->getResultOneRowArray();
        $materialType = $result['materialtype'];
        $density = $this->grabDensitybyMType($materialType);
        $this->setDensity($density);
    }

    public function calWeight() {
        $density = $this->density;
        $volume = $this->getVolume();

        #    echo "<br>=================================================<br>";
        #    echo "\$density = $density ,  \$volume = $volume <br> ";

        $weight = $volume * $density;
        $this->setWeight($weight);

        return $weight;
    }

}

Class PLATE extends WORKPCS2 {

    protected $category;

    public function __construct() {
        parent::__construct();

        $this->category = 'plate';
    }

}

Class PLATEN extends PLATE {

    protected $T; // THICK
    protected $W; //WIDTH
    protected $L; //length
    protected $Shape_Code;
//    protected $myDimension;
    protected $materialcode;
    protected $dimension;

    public function __construct($materialcode, $T, $W, $L) {
        parent::__construct();
        $this->T = $T;
        $this->W = $W;
        $this->L = $L;
        $dimension = $this->formDimension();
        $this->dimension = $dimension;
        // $this->myDimension = $dimension;
        //    $this->grabDimension();
        $this->materialcode = $materialcode;
        $Shape_Code = "PLATEN"; // shape code is Plate , normal
        $this->setShape_Code($Shape_Code);
        $this->Shape_Code = "PLATEN"; // shape code is Plate , normal
        $this->mainProcess();
    }

    private function mainProcess() {
        $materialcode = $this->getMaterialCode();
        $T = $this->getT();
        $W = $this->getW();
        $L = $this->getL();
        $Shape_Code = $this->getShape_Code();
        $isShapeCodeMatch = $this->isShapeCodeMatch($materialcode);
        if ($isShapeCodeMatch !== 'yes') {
            echo "Material with code : $materialcode have different Shape_Code than current process";
        } else {
            //Shape_Code matches, go to next step
            $materialType = $this->getMaterialType();
            $density = $this->grabDensitybyMType($materialType);
            $this->setDensity($density);
            $volume = $this->calVolume();
            $this->setVolume($volume);
            $weight = $this->calWeight();
            echo "\$volume = $volume<br>";
            echo "\$weight = $weight<br>";
            $this->setWeight($weight);
        }
    }

    public function formDimension() {
        $T = $this->T;
        $W = $this->W;
        $L = $this->L;
        $Dimension = array('T' => $T, 'W' => $W, 'L' => $L);
        return $Dimension;
    }

//    public function grabDimension() {
//        $dimension = $this->myDimension; //pointing local variable $dimension to assign scope variable  $this->myDimension into it.
//        $T = $dimension['0']; // extracts Thickness
//        $W = $dimension['1']; // extracts Width
//        $L = $dimension['2']; // extracts Length
//
//        $this->T = $T; //set Class attribute $T
//        $this->W = $W; //set Class attribute $W
//        $this->L = $L; ///set Class attribute $L
//        //create new array, with keys
//        $newDimension = array('T' => $T, 'W' => $W, 'L' => $L);
//
//        return $newDimension;
//    }

    /**    public function extractDimension() {
      // for import the dimension = [$T, $W, $L] format
      $dimension = $this->myDimension; //pointing local variable $dimension to assign scope variable  $this->myDimension into it.
      $T = $dimension['0']; // extracts Thickness
      $W = $dimension['1']; // extracts Width
      $L = $dimension['2']; // extracts Length

      $this->T = $T; //set Class attribute $T
      $this->W = $W; //set Class attribute $W
      $this->L = $L; ///set Class attribute $L
      //create new array, with keys
      $newDimension = array('T' => $T, 'W' => $W, 'L' => $L);

      return $newDimension;
      }
     * */
    public function calVolume() {

        $T = floatval($this->T);
        $W = floatval($this->W);
        $L = floatval($this->L);

        #volume = Thick * width * Length
        $vol = $T * $W * $L;
        #echo "in line 267, \$vol = $vol <br>";
        $this->setVolume($vol);

        return $vol;
    }

    public function grabDensityByMaterialcode() {

        $materialcode = $this->materialcode;
        $materialtype = $this->materialType;

        $sql = "SELECT * FROM material2020 WHERE materialcode = '$materialcode'";
        $objSql = new SQL($sql);
        $result = $objSql->getResultOneRowArray();
        $materialType = $result['materialtype'];
        $density = $this->grabDensitybyMType($materialType);
        $this->setDensity($density);
    }

    public function isShapeCodeMatch($materialcode) {

        $sql = "SELECT Shape_Code FROM material2020 WHERE materialcode = '{$materialcode}'";
        $obj = new SQL($sql);
        $result = $obj->getResultOneRowArray();
        print_r($result);
        $myShape_Code = $result['Shape_Code'];
        $Shape_Code = $this->Shape_Code;
//        $this->setShape_Code($Shape_Code);

        if ($myShape_Code == $Shape_Code) {
            $answer = 'yes';
        } else {
            $answer = 'no';
        }

        return $answer;
    }

    public function calWeight() {
        $density = (float) $this->getDensity();
        $volume = (float) $this->getVolume();

        #    echo "<br>=================================================<br>";
        #    echo "\$density = $density ,  \$volume = $volume <br> ";

        $weight = $volume * $density;
        $this->setWeight($weight);

        return $weight;
    }

    public function getShape_Code() {
        return $this->Shape_Code;
    }

    public function setShape_Code($input) {
        $this->Shape_Code = $input;
    }

    public function getT() {
        return $this->T;
    }

    public function setT($input) {
        $this->T = $input;
    }

    public function getW() {
        return $this->W;
    }

    public function setW($input) {
        $this->W = $input;
    }

    public function getL() {
        return $this->L;
    }

    public function setL($input) {
        $this->L = $input;
    }

}

Class PLATEC extends PLATEN {

    protected $T; // THICK
    protected $DIA; //DIAMETER
    private $DIAInput;
    private $LocalShape_Code;
//    protected $myDimension;
    protected $materialcode;
    protected $dimension;

    public function __construct($materialcode, $T, $W, $L, $DIA) {
        parent::__construct($materialcode, $T, $W, $L);
        $this->DIAinput = $DIA;
        $this->T = $T;
        $this->DIA = $W - 20;
        ## implement cross check with $DIAinpt vs $DIA
        ## if $DIAinpt != $DIA echo message to alert user
        $dimension = $this->formDimension();
        $this->dimension = $dimension;
        // $this->myDimension = $dimension;
        //    $this->grabDimension();
        $this->materialcode = $materialcode;
        $Shape_Code = "PLATEC"; // shape code is PLATEC
        //$this->setShape_Code($Shape_Code);
        $this->LocalShape_Code = "PLATEC"; // shape code is PlateC , normal
        $this->mainProcess();
//        $this->W = ($DIA + 20);
//        $this->L = $this->W;
    }

    private function mainProcess() {
        $materialcode = $this->getMaterialCode();
        $T = $this->getT();
        $DIA = $this->getDIA();
        //$Shape_Code = $this->getShape_Code();
        $Shape_Code = parent::getShape_Code();
        echo "parent Shape_Code = $Shape_Code <br>";
        $isShapeCodeMatch = $this->isShapeCodeMatch($materialcode);
        if ($isShapeCodeMatch !== 'yes') {
            echo "Material with code : $materialcode have different Shape_Code than current process";
        } else {
            //Shape_Code matches, go to next step
            $materialType = $this->getMaterialType();
            $density = $this->grabDensitybyMType($materialType);
            $this->setDensity($density);
            $volume = parent::calVolume();
            $this->setVolume($volume);
            $weight = parent::calWeight();
            echo "\$volume = $volume<br>";
            echo "\$weight = $weight<br>";
            $this->setWeight($weight);
        }
    }

    public function formDimension() {
        $T = $this->T;
        $DIA = $this->DIA;
        $Dimension = array('T' => $T, 'DIA' => $DIA);
        return $Dimension;
    }

    public function setLocalShape_Code($input) {
        $this->LocalShape_Code = $input;
    }

    public function getLocalShape_Code() {
        return $this->LocalShape_Code;
    }

//    public function grabDimension() {
//        $dimension = $this->myDimension; //pointing local variable $dimension to assign scope variable  $this->myDimension into it.
//        $T = $dimension['0']; // extracts Thickness
//        $W = $dimension['1']; // extracts Width
//        $L = $dimension['2']; // extracts Length
//
//        $this->T = $T; //set Class attribute $T
//        $this->W = $W; //set Class attribute $W
//        $this->L = $L; ///set Class attribute $L
//        //create new array, with keys
//        $newDimension = array('T' => $T, 'W' => $W, 'L' => $L);
//
//        return $newDimension;
//    }

    /**    public function extractDimension() {
      // for import the dimension = [$T, $W, $L] format
      $dimension = $this->myDimension; //pointing local variable $dimension to assign scope variable  $this->myDimension into it.
      $T = $dimension['0']; // extracts Thickness
      $W = $dimension['1']; // extracts Width
      $L = $dimension['2']; // extracts Length

      $this->T = $T; //set Class attribute $T
      $this->W = $W; //set Class attribute $W
      $this->L = $L; ///set Class attribute $L
      //create new array, with keys
      $newDimension = array('T' => $T, 'W' => $W, 'L' => $L);

      return $newDimension;
      }
     * */
    public function calVolume() {
        ## calculate follow PLATEN size
        $T = floatval($this->T);
        $DIA = floatval($this->DIA);

        #volume = area*T
        $radius = $DIA / 2;
        $area = pi() * pow($radius, 2);
        $vol = $area * $T;
        #echo "in line 267, \$vol = $vol <br>";
        $this->setVolume($vol);

        return $vol;
    }

    public function grabDensityByMaterialcode() {

        $materialcode = $this->materialcode;
        $materialtype = $this->materialType;

        $sql = "SELECT * FROM material2020 WHERE materialcode = '$materialcode'";
        $objSql = new SQL($sql);
        $result = $objSql->getResultOneRowArray();
        $materialType = $result['materialtype'];
        $density = $this->grabDensitybyMType($materialType);
        $this->setDensity($density);
    }

    public function isShapeCodeMatch($materialcode) {

        $sql = "SELECT Shape_Code FROM material2020 WHERE materialcode = '{$materialcode}'";
        $obj = new SQL($sql);
        $result = $obj->getResultOneRowArray();
        print_r($result);
        $myShape_Code = $result['Shape_Code'];
        $Shape_Code = parent::getShape_Code(); // get from parent shape Code, not from local
//        $Shape_Code = $this->Shape_Code;
//        $this->setShape_Code($Shape_Code);

        if ($myShape_Code == $Shape_Code) {
            $answer = 'yes';
        } else {
            $answer = 'no';
        }

        return $answer;
    }

    public function calWeight() {
        $density = (float) $this->getDensity();
        $volume = (float) $this->getVolume();

        #    echo "<br>=================================================<br>";
        #    echo "\$density = $density ,  \$volume = $volume <br> ";

        $weight = $volume * $density;
        $this->setWeight($weight);

        return $weight;
    }

    public function getShape_Code() {
        return $this->Shape_Code;
    }

    public function setShape_Code($input) {
        $this->Shape_Code = $input;
    }

    public function getT() {
        return $this->T;
    }

    public function setT($input) {
        $this->T = $input;
    }

    public function getDIA() {
        return $this->DIA;
    }

    public function setDIA($input) {
        $this->DIA = $input;
    }

}

Class PLATECO extends PLATEN {

    protected $T; // THICK
    private $ID; //Inner Diameter
    private $DIA; //Outer Diameter
    private $LocalShape_Code;
//    protected $myDimension;
    protected $materialcode;
    protected $dimension;

    public function __construct($materialcode, $T, $W, $L, $DIA, $ID) {
        parent::__construct($materialcode, $T, $W, $L);
        echo "\$T = $T, \$W = $W, \$L = $L, \$DIA = $DIA, \$ID = $ID";
        $this->T = $T;
        $this->ID = $ID;
        $this->DIA = $DIA;
        $dimension = $this->formDimension();
        $this->dimension = $dimension;
        // $this->myDimension = $dimension;
        //    $this->grabDimension();
        $this->materialcode = $materialcode;
        $Shape_Code = "PLATECO"; // shape code is Plate , normal
        $this->setLocalShape_Code($Shape_Code);
        $this->LocalShape_Code = $Shape_Code; // shape code is PlateCO
        $this->mainProcess();

        #$this->W = ($DIA + 20);
        #$this->L = $this->W;
    }

    private function mainProcess() {
        $materialcode = $this->getMaterialCode();
        $T = $this->getT();
        $ID = $this->getID();
        $DIA = $this->getDIA();
//        $Shape_Code = $this->getShape_Code();
        $Shape_Code = parent::getShape_Code();
        $isShapeCodeMatch = $this->isShapeCodeMatch($materialcode);
        if ($isShapeCodeMatch !== 'yes') {
            echo "Material with code : $materialcode have different Shape_Code than current process";
        } else {
            //Shape_Code matches, go to next step
            $materialType = $this->getMaterialType();
            $density = $this->grabDensitybyMType($materialType);
            $this->setDensity($density);
            $volume = parent::calVolume();
            #$volume = $this->calVolume();
            $this->setVolume($volume);
            $weight = parent::calWeight();
            #$weight = $this->calWeight();
            echo "\$volume = $volume<br>";
            echo "\$weight = $weight<br>";
            $this->setWeight($weight);
        }
    }

    public function setLocalShape_Code($input) {
        $this->LocalShape_Code = $input;
    }

    public function getLocalShape_Code() {
        return $this->LocalShape_Code;
    }

    public function formDimension() {
        $T = $this->T;
        $ID = $this->ID;
        $DIA = $this->DIA;
        $Dimension = array('T' => $T, 'ID' => $ID, 'DIA' => $DIA);
        return $Dimension;
    }

//    public function grabDimension() {
//        $dimension = $this->myDimension; //pointing local variable $dimension to assign scope variable  $this->myDimension into it.
//        $T = $dimension['0']; // extracts Thickness
//        $W = $dimension['1']; // extracts Width
//        $L = $dimension['2']; // extracts Length
//
//        $this->T = $T; //set Class attribute $T
//        $this->W = $W; //set Class attribute $W
//        $this->L = $L; ///set Class attribute $L
//        //create new array, with keys
//        $newDimension = array('T' => $T, 'W' => $W, 'L' => $L);
//
//        return $newDimension;
//    }

    /**    public function extractDimension() {
      // for import the dimension = [$T, $W, $L] format
      $dimension = $this->myDimension; //pointing local variable $dimension to assign scope variable  $this->myDimension into it.
      $T = $dimension['0']; // extracts Thickness
      $W = $dimension['1']; // extracts Width
      $L = $dimension['2']; // extracts Length

      $this->T = $T; //set Class attribute $T
      $this->W = $W; //set Class attribute $W
      $this->L = $L; ///set Class attribute $L
      //create new array, with keys
      $newDimension = array('T' => $T, 'W' => $W, 'L' => $L);

      return $newDimension;
      }
     * */
    public function calVolume() {

        $T = floatval($this->T);
        $ID = floatval($this->ID);
        $DIA = floatval($this->DIA);

        #volume = area * T
        $iRadius = $ID / 2;
        $oRadius = $DIA / 2;
        $area = (pi() * pow($oRadius, 2)) - (pi() * pow($iRadius, 2));
        $vol = $area * $T;
        #echo "in line 267, \$vol = $vol <br>";
        $this->setVolume($vol);

        return $vol;
    }

    public function grabDensityByMaterialcode() {

        $materialcode = $this->materialcode;
        $materialtype = $this->materialType;

        $sql = "SELECT * FROM material2020 WHERE materialcode = '$materialcode'";
        $objSql = new SQL($sql);
        $result = $objSql->getResultOneRowArray();
        $materialType = $result['materialtype'];
        $density = $this->grabDensitybyMType($materialType);
        $this->setDensity($density);
    }

    public function isShapeCodeMatch($materialcode) {

        $sql = "SELECT Shape_Code FROM material2020 WHERE materialcode = '{$materialcode}'";
        $obj = new SQL($sql);
        $result = $obj->getResultOneRowArray();
        print_r($result);
        $myShape_Code = $result['Shape_Code'];
        $Shape_Code = $this->Shape_Code;
//        $this->setShape_Code($Shape_Code);

        if ($myShape_Code == $Shape_Code) {
            $answer = 'yes';
        } else {
            $answer = 'no';
        }

        return $answer;
    }

    public function calWeight() {
        $density = (float) $this->getDensity();
        $volume = (float) $this->getVolume();

        #    echo "<br>=================================================<br>";
        #    echo "\$density = $density ,  \$volume = $volume <br> ";

        $weight = $volume * $density;
        $this->setWeight($weight);

        return $weight;
    }

    public function getShape_Code() {
        return $this->Shape_Code;
    }

    public function setShape_Code($input) {
        $this->Shape_Code = $input;
    }

    public function getT() {
        return $this->T;
    }

    public function setT($input) {
        $this->T = $input;
    }

    public function getID() {
        return $this->ID;
    }

    public function setID($input) {
        $this->ID = $input;
    }

    public function getDIA() {
        return $this->DIA;
    }

    public function setDIA($input) {
        $this->DIA = $input;
    }

}

Class LIP extends ROD {

    protected $H; // Long Side
    protected $A; // Short Side
    protected $C; // Lip Side
    protected $t; // thickness
    protected $L; // Length
    protected $Shape_Code;
//    protected $myDimension;
    protected $materialcode;
    protected $dimension;

    public function __construct($materialcode, $H, $A, $C, $t, $L) {
        parent::__construct();
        $this->H = $H;
        $this->A = $A;
        $this->C = $C;
        $this->t = $t;
        $this->L = $L;
        $dimension = $this->formDimension();
        $this->dimension = $dimension;
        // $this->myDimension = $dimension;
        //    $this->grabDimension();
        $this->materialcode = $materialcode;
        $Shape_Code = "LIP"; // shape code is Plate , normal
        $this->setShape_Code($Shape_Code);
        $this->Shape_Code = "LIP"; // shape code is Plate , normal
        $this->mainProcess();
    }

    private function mainProcess() {
        $materialcode = $this->getMaterialCode();
        $H = $this->getH();
        $A = $this->getA();
        $C = $this->getC();
        $t = $this->getT();
        $L = $this->getL();
        $Shape_Code = $this->getShape_Code();
        $isShapeCodeMatch = $this->isShapeCodeMatch($materialcode);
        if ($isShapeCodeMatch !== 'yes') {
            echo "Material with code : $materialcode have different Shape_Code than current process";
        } else {
            //Shape_Code matches, go to next step
            $materialType = $this->getMaterialType();
            $density = $this->grabDensitybyMType($materialType);
            $this->setDensity($density);
            $volume = $this->calVolume();
            $this->setVolume($volume);
            $weight = $this->calWeight();
            echo "\$volume = $volume<br>";
            echo "\$weight = $weight<br>";
            $this->setWeight($weight);
        }
    }

    public function formDimension() {
        $H = $this->H;
        $A = $this->A;
        $C = $this->C;
        $t = $this->t;
        $L = $this->L;
        $Dimension = array('H' => $H, 'A' => $A, 'C' => $C, 't' => $t, 'L' => $L);
        return $Dimension;
    }

//    public function grabDimension() {
//        $dimension = $this->myDimension; //pointing local variable $dimension to assign scope variable  $this->myDimension into it.
//        $T = $dimension['0']; // extracts Thickness
//        $W = $dimension['1']; // extracts Width
//        $L = $dimension['2']; // extracts Length
//
//        $this->T = $T; //set Class attribute $T
//        $this->W = $W; //set Class attribute $W
//        $this->L = $L; ///set Class attribute $L
//        //create new array, with keys
//        $newDimension = array('T' => $T, 'W' => $W, 'L' => $L);
//
//        return $newDimension;
//    }

    /**    public function extractDimension() {
      // for import the dimension = [$T, $W, $L] format
      $dimension = $this->myDimension; //pointing local variable $dimension to assign scope variable  $this->myDimension into it.
      $T = $dimension['0']; // extracts Thickness
      $W = $dimension['1']; // extracts Width
      $L = $dimension['2']; // extracts Length

      $this->T = $T; //set Class attribute $T
      $this->W = $W; //set Class attribute $W
      $this->L = $L; ///set Class attribute $L
      //create new array, with keys
      $newDimension = array('T' => $T, 'W' => $W, 'L' => $L);

      return $newDimension;
      }
     * */
    public function calVolume() {

        $H = floatval($this->H);
        $A = floatval($this->A);
        $C = floatval($this->C);
        $t = floatval($this->t);
        $L = floatval($this->L);
        #echo "\$H = $H<br>";
        ##echo "\$A = $A<br>";
        #echo "\$C = $C<br>";
        #echo "\$t = $t<br>";
        #echo "\$L = $L<br>";
        $area1 = 2 * ($A * $t);
        $area2 = $t * ($H - (2 * $t));
        $area3 = 2 * $t * ($C - $t);

        #echo "\$area1 = $area1<br>";
        #echo "\$area2 = $area2<br>";
        #echo "\$area3 = $area3<br>";

        $vol = ($area1 + $area2 + $area3) * $L;

        #echo "in line 267, \$vol = $vol <br>";
        $this->setVolume($vol);

        return $vol;
    }

    public function grabDensityByMaterialcode() {

        $materialcode = $this->materialcode;
        $materialtype = $this->materialType;

        $sql = "SELECT * FROM material2020 WHERE materialcode = '$materialcode'";
        $objSql = new SQL($sql);
        $result = $objSql->getResultOneRowArray();
        $materialType = $result['materialtype'];
        $density = $this->grabDensitybyMType($materialType);
        $this->setDensity($density);
    }

    public function isShapeCodeMatch($materialcode) {

        $sql = "SELECT Shape_Code FROM material2020 WHERE materialcode = '{$materialcode}'";
        $obj = new SQL($sql);
        $result = $obj->getResultOneRowArray();
        print_r($result);
        $myShape_Code = $result['Shape_Code'];
        $Shape_Code = $this->Shape_Code;
//        $this->setShape_Code($Shape_Code);

        if ($myShape_Code == $Shape_Code) {
            $answer = 'yes';
        } else {
            $answer = 'no';
        }

        return $answer;
    }

    public function calWeight() {
        $density = (float) $this->getDensity();
        $volume = (float) $this->getVolume();

        #    echo "<br>=================================================<br>";
        #    echo "\$density = $density ,  \$volume = $volume <br> ";

        $weight = $volume * $density;
        $this->setWeight($weight);

        return $weight;
    }

    public function getShape_Code() {
        return $this->Shape_Code;
    }

    public function setShape_Code($input) {
        $this->Shape_Code = $input;
    }

    public function getT() {
        return $this->t;
    }

    public function setT($input) {
        $this->t = $input;
    }

    public function getH() {
        return $this->H;
    }

    public function setH($input) {
        $this->H = $input;
    }

    public function getC() {
        return $this->C;
    }

    public function setC($input) {
        $this->C = $input;
    }

    public function getA() {
        return $this->A;
    }

    public function setA($input) {
        $this->A = $input;
    }

    public function getL() {
        return $this->L;
    }

    public function setL($input) {
        $this->L = $input;
    }

}

Class C extends ROD { //C-Channel

    protected $H; // Vertical Rib
    protected $A; // Horizontal Rib (Flange)
    protected $T; // Flange Thickness
    protected $ribT; // Rib Thickness
    protected $L; // Length
    protected $Shape_Code;
//    protected $myDimension;
    protected $materialcode;
    protected $dimension;

    public function __construct($materialcode, $H, $A, $T, $ribT, $L) {
        parent::__construct();
        $this->H = $H;
        $this->A = $A;
        $this->T = $T;
        $this->ribT = $ribT;
        $this->L = $L;
        $dimension = $this->formDimension();
        $this->dimension = $dimension;
        // $this->myDimension = $dimension;
        //    $this->grabDimension();
        $this->materialcode = $materialcode;
        $Shape_Code = "C"; // shape code is Plate , normal
        $this->setShape_Code($Shape_Code);
        $this->Shape_Code = "C"; // shape code is Plate , normal
        $this->mainProcess();
    }

    private function mainProcess() {
        $materialcode = $this->getMaterialCode();
        $H = $this->getH();
        $A = $this->getA();
        $T = $this->getT();
        $ribT = $this->getribT();
        $L = $this->getL();
        $Shape_Code = $this->getShape_Code();
        $isShapeCodeMatch = $this->isShapeCodeMatch($materialcode);
        if ($isShapeCodeMatch !== 'yes') {
            echo "Material with code : $materialcode have different Shape_Code than current process";
        } else {
            //Shape_Code matches, go to next step
            $materialType = $this->getMaterialType();
            $density = $this->grabDensitybyMType($materialType);
            $this->setDensity($density);
            $volume = $this->calVolume();
            $this->setVolume($volume);
            $weight = $this->calWeight();
            echo "\$volume = $volume<br>";
            echo "\$weight = $weight<br>";
            $this->setWeight($weight);
        }
    }

    public function formDimension() {
        $H = $this->H;
        $A = $this->A;
        $T = $this->T;
        $ribT = $this->ribT;
        $L = $this->L;
        $Dimension = array('H' => $H, 'A' => $A, 'T' => $T, 'ribT' => $ribT, 'L' => $L);
        return $Dimension;
    }

//    public function grabDimension() {
//        $dimension = $this->myDimension; //pointing local variable $dimension to assign scope variable  $this->myDimension into it.
//        $T = $dimension['0']; // extracts Thickness
//        $W = $dimension['1']; // extracts Width
//        $L = $dimension['2']; // extracts Length
//
//        $this->T = $T; //set Class attribute $T
//        $this->W = $W; //set Class attribute $W
//        $this->L = $L; ///set Class attribute $L
//        //create new array, with keys
//        $newDimension = array('T' => $T, 'W' => $W, 'L' => $L);
//
//        return $newDimension;
//    }

    /**    public function extractDimension() {
      // for import the dimension = [$T, $W, $L] format
      $dimension = $this->myDimension; //pointing local variable $dimension to assign scope variable  $this->myDimension into it.
      $T = $dimension['0']; // extracts Thickness
      $W = $dimension['1']; // extracts Width
      $L = $dimension['2']; // extracts Length

      $this->T = $T; //set Class attribute $T
      $this->W = $W; //set Class attribute $W
      $this->L = $L; ///set Class attribute $L
      //create new array, with keys
      $newDimension = array('T' => $T, 'W' => $W, 'L' => $L);

      return $newDimension;
      }
     * */
    public function calVolume() {

        $H = floatval($this->H);
        $A = floatval($this->A);
        $T = floatval($this->T);
        $ribT = floatval($this->ribT);
        $L = floatval($this->L);

        //volume = 2*(A*T) + t*(H-(2T))   * L
        $area1 = 2 * ($A * $T);
        $area2 = $ribT * ($H - (2 * $T));

        $vol = ($area1 + $area2) * $L;

        #echo "in line 267, \$vol = $vol <br>";
        $this->setVolume($vol);

        return $vol;
    }

    public function grabDensityByMaterialcode() {

        $materialcode = $this->materialcode;
        $materialtype = $this->materialType;

        $sql = "SELECT * FROM material2020 WHERE materialcode = '$materialcode'";
        $objSql = new SQL($sql);
        $result = $objSql->getResultOneRowArray();
        $materialType = $result['materialtype'];
        $density = $this->grabDensitybyMType($materialType);
        $this->setDensity($density);
    }

    public function isShapeCodeMatch($materialcode) {

        $sql = "SELECT Shape_Code FROM material2020 WHERE materialcode = '{$materialcode}'";
        $obj = new SQL($sql);
        $result = $obj->getResultOneRowArray();
        print_r($result);
        $myShape_Code = $result['Shape_Code'];
        $Shape_Code = $this->Shape_Code;
//        $this->setShape_Code($Shape_Code);

        if ($myShape_Code == $Shape_Code) {
            $answer = 'yes';
        } else {
            $answer = 'no';
        }

        return $answer;
    }

    public function calWeight() {
        $density = (float) $this->getDensity();
        $volume = (float) $this->getVolume();

        #    echo "<br>=================================================<br>";
        #    echo "\$density = $density ,  \$volume = $volume <br> ";

        $weight = $volume * $density;
        $this->setWeight($weight);

        return $weight;
    }

    public function getShape_Code() {
        return $this->Shape_Code;
    }

    public function setShape_Code($input) {
        $this->Shape_Code = $input;
    }

    public function getT() {
        return $this->T;
    }

    public function setT($input) {
        $this->T = $input;
    }

    public function getribT() {
        return $this->ribT;
    }

    public function setribT($input) {
        $this->ribT = $input;
    }

    public function getH() {
        return $this->H;
    }

    public function setH($input) {
        $this->H = $input;
    }

    public function getA() {
        return $this->A;
    }

    public function setA($input) {
        $this->A = $input;
    }

    public function getL() {
        return $this->L;
    }

    public function setL($input) {
        $this->L = $input;
    }

}
