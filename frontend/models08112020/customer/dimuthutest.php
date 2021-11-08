<?php
$url = "http://xxx.xxx.xxx.xxx/file.csv";
function curl($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    return curl_exec($ch);
    curl_close ($ch);
}


$csv = "http://xxx.xxx.xxx.xxx/file.csv";


echo $filenameb  = curl($csv);



    if (file_exists($filenameb)){
       
        //mysql_select_db("transfer",$sqlconnect);
       
     
        echo "$filenameb</br>";
        ///$handle = fopen("$filenameb", "r") or die("no file");
        while (($data = fgetcsv($filenameb, 50000, "," )) !== FALSE ){    //echo "<pre>"; print_r($data);die;
           
        $id = $data[0];
        //$id = $data[0];
        //goes off off of no as number number is set to 0
            //$id = $data[0];
            //echo $id;die;
            // $existSql = "Select * from push_it where no='".$id."'";
            // $result = mysql_query($existSql);
            // if(@mysql_num_rows($result)>0) {
                // $updateSql = "UPDATE push_it set
                // `key` = '".mysql_real_escape_string($data[1])."' ,
                // `Int1` = '".mysql_real_escape_string($data[2])."' ,
                // `Int2` = '".mysql_real_escape_string($data[3])."' ,
                // `Int3` = '".mysql_real_escape_string($data[4])."' ,
                // `string` = '".mysql_real_escape_string($data[5])."' ,
                // `Date1` = '".mysql_real_escape_string($data[6])."'  WHERE NO='$id'";
                // mysql_query($updateSql) or die("insert" . mysql_error());
            // }
            // else {
   
                // $import="INSERT INTO push_it (`no` , `key` , `Int1` , `Int2` , `Int3` , `string` , `Date1`)
                        // values(
                        // '".mysql_real_escape_string($data[0])."' ,
                        // '".mysql_real_escape_string($data[1])."',
                        // '".mysql_real_escape_string($data[2])."',
                        // '".mysql_real_escape_string($data[3])."',
                        // '".mysql_real_escape_string($data[4])."',
                        // '".mysql_real_escape_string($data[5])."',
                        // '".mysql_real_escape_string($data[6])."')";     
       
                  // mysql_query($import) or die("insert" . mysql_error());
          // }
    }
    fclose($handle);
    echo "super";
}
else{
    echo "terrible";
}