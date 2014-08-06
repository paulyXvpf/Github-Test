<?php
        set_time_limit(15000);
        $con=mysqli_connect("localhost","argomark_ALead","ALead@12#","argomark_autolead");
        mysqli_set_charset($con, "utf8");

// Make the Phone number list.

        $phonenumberlist = "0000000000";
        $result1 = mysqli_query($con,"SELECT * FROM Leads");
        while($row = mysqli_fetch_array($result1))      {

                $sl = strlen($row['PhoneNumber']);
                if($sl != 10)   {
                echo "";
                                }
                else    {
                        $phonenumberlist = $phonenumberlist . "," . $row['PhoneNumber'];
                        }
                                                        }
//echo $phonenumberlist;
//echo ".";

// Scrub the Phone number with DNC.com

        $link = mysql_connect('localhost', 'argomark_ALead', 'ALead@12#');
        mysql_select_db('argomark_autolead', $link);
        if (!$link)     {
                die('Could not connect: ' . mysql_error());
                        }
        $url = "http://www.dncscrub.com/app/main/rpc/scrub?loginId=0156CDA9C9A51592F1BF1D576785A63C6210004FFD75&phoneList=";
        $data = $phonenumberlist;
        $cap = "&output=xml";
        $full = $url.$data.$cap;

        if (!function_exists('_sendRequest'))   {
                function _sendRequest($full)    {
                        $output = array();

// Open the cURL session
                        $curlSession = curl_init();
                        curl_setopt($curlSession, CURLOPT_URL, $full);
                        curl_setopt($curlSession, CURLOPT_HEADER, 0);
                        curl_setopt($curlSession, CURLOPT_POST, 1);
                        curl_setopt($curlSession, CURLOPT_RETURNTRANSFER,1);
                        curl_setopt($curlSession, CURLOPT_TIMEOUT,30);
                        curl_setopt($curlSession, CURLOPT_SSL_VERIFYPEER, FALSE);
                        curl_setopt($curlSession, CURLOPT_SSL_VERIFYHOST, 2);

                        $rawresponse = curl_exec($curlSession);
// Check that a connection was made
                        if (curl_error($curlSession))   {
// If it wasn't...

                                $output['Status'] = "FAIL";
                                $output['StatusDetail'] = curl_error($curlSession);
                                                        }
// Close the cURL session
                        curl_close ($curlSession);
                        return $rawresponse;
                                                }
                                                }
        $rsp = _sendRequest($full);
        $scrubbed = explode(",,,,",$rsp);

//var_dump($scrubbed);


// Update Database with results.

        $result1 = mysqli_query($con,"SELECT * FROM LeadListData");
        while($row2 = mysqli_fetch_array($result1))     {

                $id = $row2['ListID'];

                $db = new mysqli("localhost", "argomark_ALead", "ALead@12#", "argomark_autolead");
                if ($db->connect_errno > 0)     {
                        die('Unable to connect to database [' . $db->connect_error . ']');
                                                }
                $sql = "SELECT * FROM `LeadListData` WHERE ListID='".$id."';";
                if (!$result = $db->query($sql))        {
                        die('There was an error running the query [' . $db->error . ']');
                                                }
                $c = 0;
                while ($row = $result->fetch_object())  {

                        if ($c == 0)    {

                                        }
                        $cellularid = $row->CellID;
                        $landlineid = $row->LandID;
                        $c++;
                                                }

                foreach ($scrubbed as $num)     {
                        $updatel = "UPDATE Leads SET Scrubbed='Land' Where PhoneNumber='".$phn."'";
                        $updatew = "UPDATE Leads SET Scrubbed='Wireless' Where PhoneNumber='".$phn."'";
//                      $setwid = "UPDATE Leads SET ListID='".$cellularid."' Where PhoneNumber='".$phn."'";
//                      $setlid = "UPDATE Leads SET ListID='".$landlineid."' Where PhoneNumber='".$phn."'";
                        $setwid = "UPDATE Leads SET ListID='".$cellularid."' Where PhoneNumber='".$phn."' && ListID='".$id."'";
                        $setlid = "UPDATE Leads SET ListID='".$landlineid."' Where PhoneNumber='".$phn."' && ListID='".$id."'";
                        $updatei = "UPDATE Leads SET Scrubbed='Invalid Lead Entry' Where PhoneNumber='".$phn."'";
                        $wirel = "Wireless";
                        $landp = "Land";
                        $phn = substr($num, 1, 10);

//echo "Number = " . $num;
//echo "Cell = " . $cellularid;
//echo "Land = " . $landlineid;
//echo "<br>";
//echo $phn ." => ";
//echo ".";
                        if($num[12] == "W")     {
//echo "Wireless<br>";

                                mysql_query($updatew, $link);
                                mysql_query($setwid, $link);
                                                }

                        elseif($num[12] == "B") {
//echo "Land<br>";
                                mysql_query($updatel, $link);
                                mysql_query($setlid, $link);
                                                }

                        elseif($num[12] == "O") {
//echo "Land<br>";
                                mysql_query($updatel, $link);
                                mysql_query($setlid, $link);
                                                }

                        elseif($num[12] == "G") {
//echo "Wireless<br>";
                                mysql_query($updatew, $link);
                                mysql_query($setwid, $link);
                                                }

                        elseif($num[12] == "H") {
//echo "Wireless<br>";
                                mysql_query($updatew, $link);
                                mysql_query($setwid, $link);
                                                }

                                                }
                                                                }

mysqli_close($con);

//$to = "vturner@argomarketinggroup.com";
//$subject = "Scrub.php";
//$from = "Automatic Lead";
//$headers = "From:" . $from;
//$message = "ran";
//mail($to,$subject,$message,$from);
//echo "Confirmation E-Mail Sent!";

?>










