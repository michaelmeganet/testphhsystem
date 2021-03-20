<?php
class QRCode_Generate {

    private $data_array = array();
    private $dataType; //staff or machine\
    private $dataKey; //staffid or machineid
    private $imageContentURL;
    private $baseurl;
    private $currURL;

    function __construct($dataType, $data_array, $dataKey) {
        #echo "instantiate QRCode Generate class<br>";
        $this->data_array = $data_array;
        #echo "data_array in class = <br>";
        #print_r($data_array);
        #echo "<br>";
        $this->dataType = $dataType;
        $this->dataKey = $dataKey;
        $this->imageContentURL = "img/qrcodes/$dataType/";
        $this->currURL = "http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        $baseurl = $this->generateBaseName();
        $this->baseurl = $baseurl;
        #echo "end instantiation of QRCode Gneerate class<br>";
    }

    function generateQRCode() {
        $data_array = $this->data_array;
        $dataKey = $this->dataKey;
        $baseurl = $this->baseurl;
        $imageContentURL = $this->imageContentURL;
        $cnt=0;
        foreach ($data_array as $data_row) {
            $qrURL = "qrcodeimage.php?code=" . $data_row[$dataKey];
            #echo "qrURL= $qrURL<br>";
            $curr = $baseurl . $qrURL;
            #echo "currr = $curr\n";
            $outputData = file_get_contents($curr);
            $outputURL = $imageContentURL . "qr_" . $data_row[$dataKey] . ".png";
            #echo "outputURL = $outputURL\n";
            file_put_contents(''.$outputURL, $outputData);
            $this->insertImage($data_row[$dataKey], $outputURL );
            $cnt++;
        }
        echo "Found and generated $cnt QRCodes for $dataKey\n";
        return ;
    }

    function generateBaseName() {
        $currURL = $this->currURL;
        $query = $_SERVER['PHP_SELF'];
        $path = pathinfo($query);
        $basename = $path['basename'];
        $baseurl = str_replace($basename, '', $currURL);
        return $baseurl;
    }

    function insertImage($qrData, $imageURL) {
        $dataType = $this->dataType;
        switch ($dataType) {
            case 'staff':
                $qr = "UPDATE admin_staff SET qrcode = '$imageURL' WHERE staffid = '$qrData'";
                break;
            case 'machine':
                $qr = "UPDATE machine SET qrcode = '$imageURL' WHERE machineid = '$qrData'";
                break;
        }
        $objSQL = new SQL($qr);
        $results = $objSQL->getUpdate();
        return;
    }

}
?>
