<script src="QuantumPHP.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


<?php
$title = "Add Booking";
include "header.php";
include "menu.php";
checkUser();
loginStatus();


echo '<div id="site_content">';
include "sidebar.php";

echo '<div id="content">';



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

//the data was sent using a form therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Book')) {
//if ($_SERVER["REQUEST_METHOD"] == "POST") { //alternative simpler POST test
    include "config.php"; //load in any variables
    $DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; //stop processing the page further
    };


       $error = 0; //clear our error flag
       $msg = 'Error: ';
       if (isset($_POST['rooID']) and !empty($_POST['rooID']) and is_string($_POST['rooID'])) {
          $rid = cleanInput($_POST['rooID']);
          $rooID = (strlen($rid)>2)?substr($rid,1,2):$rid; //check length and clip if too big
          //we would also do context checking here for contents, etc
       } else {
          $error++; //bump the error flag
          $msg .= 'Invalid roomID '; //append eror message
          $rooID = '';
       }

       $error = 0; //clear our error flag
       $msg = 'Error: ';
       if (isset($_POST['inDate']) and !empty($_POST['inDate']) and is_string($_POST['inDate'])) {
          $inD = cleanInput($_POST['inDate']);
          $inDate = (strlen($inD)>50)?substr($inD,1,10):$inD; //check length and clip if too big
          //we would also do context checking here for contents, etc
       } else {
          $error++; //bump the error flag
          $msg .= 'Invalid inDate '; //append eror message
          $inDate = '';
       }

       $error = 0; //clear our error flag
       $msg = 'Error: ';
       if (isset($_POST['outDate']) and !empty($_POST['outDate']) and is_string($_POST['outDate'])) {
          $ouD = cleanInput($_POST['outDate']);
          $outDate = (strlen($ouD)>50)?substr($ouD,1,10):$ouD; //check length and clip if too big
          //we would also do context checking here for contents, etc
       } else {
          $error++; //bump the error flag
          $msg .= 'Invalid outDate '; //append eror message
          $outDate = '';
       }



       $error = 0; //clear our error flag
       $msg = 'Error: ';
       if (isset($_POST['extras']) and !empty($_POST['extras']) and is_string($_POST['extras'])) {
          $ex = cleanInput($_POST['extras']);
          $extras = (strlen($ex)>50)?substr($ex,1,50):$ex; //check length and clip if too big
          //we would also do context checking here for contents, etc
       } else {
          $error++; //bump the error flag
          $msg .= 'Invalid extras '; //append eror message
          $extras = '';
       }



       $error = 0; //clear our error flag
       $msg = 'Error: ';
       if (isset($_POST['cNum']) and !empty($_POST['cNum']) and is_string($_POST['cNum'])) {
          $cn = cleanInput($_POST['cNum']);
          $cNum = (strlen($cn)>50)?substr($cn,1,15):$cn; //check length and clip if too big
          //we would also do context checking here for contents, etc
       } else {
          $error++; //bump the error flag
          $msg .= 'Invalid cNum '; //append eror message
          $cNum = '';
       }










//save the customer data if the error flag is still clear
    if ($error == 0) {
        $query = "INSERT INTO booking (roomID,inDate,outDate,contactNUM,extras,customerID) VALUES (?,?,?,?,?,?)";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'issssi', $rid, $inD, $ouD, $cn, $ex, $_SESSION['userid']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<h2>Booking saved</h2>";
    } else {
      echo "<h2>$msg</h2>".PHP_EOL;
    }

    mysqli_close($DBC); //close the connection once done
    
    
    
    QuantumPHP::add($row['rooID'].$rid);            

             QuantumPHP::send();
             
    QuantumPHP::add($row['inDate'].$inD);            

             QuantumPHP::send();
             
    QuantumPHP::add($row['outDate'].$ouD);            

             QuantumPHP::send();
    
     QuantumPHP::add($row['cNum'].$cn);            

             QuantumPHP::send();
             
     QuantumPHP::add($row['extras'].$ex);            

             QuantumPHP::send();
             
             
}
?>

<h1>Make a Booking</h1>
<h2><a href='listbookings.php'>[Browse Bookings]</a><a href='index.php'>[Return to the main page]</a></h2>

<section class="form1">
  <div class=:"form2">
<form method="POST" action="makebooking.php" autocomplete="off">
    <label for="rooID">Room (name, type, beds):<Label>
        <select id="rooID" name="rooID" required>
      <option name="" value="" disabled selected>Select</option>
      <option name="1" value="1">Kellie, S, 5</option>
      <option name="2" value="2">Herman, D, 5</option>
      <option name="3" value="3">Scarlett, D, 2</option>
      <option name="4" value="4">Jelani, S, 2</option>
      <option name="5" value="5">Sonya, S, 5</option>
      <option name="6" value="6">Miranda, S, 4</option>
      <option name="7" value="7">Helen, S, 4</option>
      <option name="8" value="8">Octavia, D, 3</option>
      <option name="9" value="9">Gretchen, D, 3</option>
      <option name="10" value="10">Bernard, S, 5</option>
      <option name="11" value="11">Dacey, D, 3</option>
      <option name="12" value="12">Preston, D, 2</option>
      <option name="13" value="13">Dane, S, 4</option>
      <option name="14" value="14">Cole, S, 1</option>
    </select><br><br>

        <label for="inDate">Checkin Date: </label>
        <input type="text" id="inDate" name="inDate" required><br><br>

        <label for="outDate">CheckOut Date: </label>
        <input type="text" id="outDate" name="outDate" required><br><br>

        <label for="cNum">Contact number: <Label>
        <input type="text" id="cNum" name="cNum" pattern="[(][0-9]{3}[)] [0-9]{3}-[0-9]{4}" maxlength="14" required><br>

        <small>Format: (123) 456-7891</small><br><br>

        <label for="extras">Booking Extras: <Label>
        <textarea name="extras" id="extras" cols="40" rows="5"></textarea><br><br>

         <input type="submit" name="submit" value="Book">
         <a href="index.php">Cancel</a>
</section><br>

   <hr>

   <section class="form1">
              <div class="container">
                 <h2 align="center">Search Room Availability</h2>
                <input type="text" name="inDate2" id="inDate2" placeholder="Checkin Date" />
                <input type="text" name="outDate2" id="outDate2" placeholder="Checkout Date" />
                <input type="button" name="search" id="search" value="Search" />
      </section><br>
                 <div id="reserv_table">
                      <table class="table table-bordered">
                           <tr>
                            <th width="10%">Room Name</th>
                            <th width="70%">Description</th>
                            <th width="10%">Room Type</th>
                            <th width="10%">Beds</th>
                           </tr>
                      </table>
                 </div>
 </form>
</div>

 <script>
      $(document).ready(function(){
           $.datepicker.setDefaults({
                dateFormat: 'yy-mm-dd'
           });
           $(function(){
                $("#inDate, #inDate2").datepicker();
                $("#outDate, #outDate2").datepicker();
           });
           $('#search').click(function(){
                var inDate2 = $('#inDate2').val();
                var outDate2 = $('#outDate2').val();
                if(inDate2 != '' && outDate2 != '')
                {
                 $.ajax({
                  url:"search3.php",
                  method:"GET",
                  data:{inDate2:inDate2, outDate2:outDate2},
                  success:function(data)
                  {
                  $('#reserv_table').html(data);
                  }
                 });
                }
                else
                {
                     alert("Please Select Date");
                }
           });
      });
 </script>

 <?php
 echo '</div></div>';
require_once "footer.php";
 ?>
