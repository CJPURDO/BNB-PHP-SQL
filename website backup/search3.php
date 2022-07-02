<!--Search reservations-->
<?php



// Optional if you do not have an autoloader
include 'QuantumPHP.php';

/**
 * Optional debugging mode
 * mode = 1 for Chrome and Firefox
 * mode 2 for just Firefox
 * mode 3 for just Chrome
 * mode 0 when you have a HUGE-MONGOUS log, and
 *    HTTP headers break the server or browser...
 *    WARNING: mode 0 will echo the log in an HTML comment, so
 *    no more http headers can be sent once you call QuantumPHP::send()
 *    (unless you use output buffering)
 * defaults to mode 2
 */
QuantumPHP::$MODE = 3;

// Optional debug size. Defaults to 5kB
QuantumPHP::$HEADER_LIMIT = 16000;

// Logging strings
QuantumPHP::log('Regular log');
QuantumPHP::warn('Regular warn');
QuantumPHP::error('Regular error');

// Logging strings, objects, or arrays
QuantumPHP::add('Hello console table!');
QuantumPHP::add('Something Bad','error');
QuantumPHP::add('Something Really Bad','critical');
// QuantumPHP::add($_SERVER); // you will need mode 0 for this!
try
{
	throw new Exception('Something Bad!!');
}
catch(Exception $e)
{
	\QuantumPHP::add('test','warning',$e);
}

// Logging data in a table
// objects can be expanded in Firefox console table, but not Chrome:
$obj = new stdClass();
$obj->name = 'test class';
$obj->items = [1,2,3];
$lines = [];

$lines[] = [
	 'Time' =>round(microtime(true),8)
	,'Level' => 'status'
	,'Comment' => $obj // Chrome just shows {...}
	,'Function' => debug_backtrace()[0]['function']
	,'File' => __LINE__.' - '.__FILE__
];
$lines[] = [
	 'Time' =>round(microtime(true),8)
	,'Level' => 'status'
	,'Comment' => 'Strings are ok in Chrome'
	,'Function' => debug_backtrace()[0]['function']
	,'File' => __LINE__.' - '.__FILE__
];

QuantumPHP::table($lines);

QuantumPHP::send();







//function to clean input but not validate type and content
function cleanInput($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}

if(isset($_GET["inDate2"], $_GET["outDate2"])) {
//if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test
    include "config.php"; //load in any variables
    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);
    $output2 = '';

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; //stop processing the page further
    };

      $query2 = "
      SELECT * FROM room
      WHERE roomID NOT IN (SELECT roomID FROM booking WHERE inDate >= '".$_GET["inDate2"]."' AND outDate <= '".$_GET["outDate2"]."'
      )";
      $result2 = mysqli_query($DBC, $query2);
      $output2 .= '
           <table class="table table-bordered">
                <tr>
               <th width="10%">Room Name</th>
               <th width="70%">Description</th>
               <th width="10%">Room Type</th>
               <th width="10%">Beds</th>
                </tr>
      ';
      if(mysqli_num_rows($result2) > 0)
      {
      while($row = mysqli_fetch_array($result2))
           {
                $output2 .= '
                     <tr>
                    <td>'. $row["roomname"] .'</td>
                    <td>'. $row["description"] .'</td>
                    <td>'. $row["roomtype"] .'</td>
                    <td>'. $row["beds"] .'</td>

                     </tr>
                ';
           }
      }
      else
      {
           $output2 .= '
                <tr>
                     <td colspan="5">No Rooms Available</td>
                </tr>
           ';
      }
      $output2 .= '</table>';
      echo $output2;
 }
 
 
  QuantumPHP::add($_GET['inDate2']);            

             QuantumPHP::send();
             
QuantumPHP::add($_GET['outDate2']);            

             QuantumPHP::send();
 

QuantumPHP::table($output2);

QuantumPHP::send();
 
 
 
 
 ?>
