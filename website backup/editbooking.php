
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>

<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">




<?php
$title = "Edit Booking";
include "header.php";
include "menu.php";
checkUser();
loginStatus();

echo '<div id="site_content">';
include "sidebar.php";
echo '<div id="content">';


include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

if (mysqli_connect_errno()) {
  echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
  exit; //stop processing the page further
};

//function to clean input but not validate type and content
function cleanInput($data) {
  return htmlspecialchars(stripslashes(trim($data)));
}

//retrieve the roomid from the URL
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    if (empty($id) or !is_numeric($id)) {
        echo "<h2>Invalid room ID</h2>"; //simple error feedback
        exit;
    }
}
//the data was sent using a form therefore we use the $_POST instead of $_GET
//check if we are saving data first by checking if the submit button exists in the array
if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Update')) {
//validate incoming data - only the first field is done for you in this example - rest is up to you do

//roomID (sent via a form it is a string not a number so we try a type conversion!)
    if (isset($_POST['id']) and !empty($_POST['id']) and is_integer(intval($_POST['id']))) {
       $id = cleanInput($_POST['id']);
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid Book ID '; //append error message
       $id = 0;
    }


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
       $inDate = (strlen($inD)>50)?substr($inD,1,50):$inD; //check length and clip if too big
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
    if (isset($_POST['cNum']) and !empty($_POST['cNum']) and is_string($_POST['cNum'])) {
       $cn = cleanInput($_POST['cNum']);
       $cNum = (strlen($cn)>50)?substr($cn,1,10):$cn; //check length and clip if too big
       //we would also do context checking here for contents, etc
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid contactnumber '; //append eror message
       $cNum = '';
    }

    $error = 0; //clear our error flag
    $msg = 'Error: ';
    if (isset($_POST['bExtras']) and !empty($_POST['bExtras']) and is_string($_POST['bExtras'])) {
       $bEX = cleanInput($_POST['bExtras']);
       $bExtras = (strlen($bEX)>250)?substr($bEX,1,250):$bEX; //check length and clip if too big
       //we would also do context checking here for contents, etc
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid bExtras '; //append eror message
       $bExtras = '';
    }

    $error = 0; //clear our error flag
    $msg = 'Error: ';
    if (isset($_POST['review']) and !empty($_POST['review']) and is_string($_POST['review'])) {
       $rw = cleanInput($_POST['review']);
       $review = (strlen($rw)>250)?substr($rw,1,250):$rw; //check length and clip if too big
       //we would also do context checking here for contents, etc
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid Review '; //append eror message
       $review = '';
    }



//save the room data if the error flag is still clear and room id is > 0
    if ($error == 0 and $id > 0) {
        $query = "UPDATE booking SET roomID=?,inDate=?,outDate=?,contactNUM=?,extras=?,review=? WHERE bookID=?";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'isssssi', $rid, $inD, $ouD, $cn, $bEX, $rw, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<h2>Booking details updated.</h2>";
        header('Location: http://ongaongabb.net/listbookings.php', true, 303);
    } else {
      echo "<h2>$msg</h2>".PHP_EOL;
    }
}
//locate the room to edit by using the roomID
//we also include the room ID in our form for sending it back for saving the data
$query = 'SELECT booking.bookID,booking.inDate,booking.outDate,booking.contactNUM,booking.extras,room.roomname,room.beds,room.roomtype,booking.review FROM booking
INNER JOIN room ON booking.roomID=room.roomID
WHERE bookID='.$id;


$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result);
if ($rowcount > 0) {
  $row = mysqli_fetch_assoc($result);
?>


<h1>Booking Details Update</h1>
<h2><a href='listbookings.php'>[Return to the Booking List]</a><a href='index.php'>[Return to the main page]</a></h2>

<section>

        <form method="POST" action="editbooking.php" autocomplete="off">
        <input type="hidden" name="id" value="<?php echo $id;?>">

          <label for="current">Current Room: </label>
          <input type="text" id="current" name="current" value="<?php echo $row['roomname']; ?>, <?php echo $row['roomtype']; ?>, <?php echo $row['beds']; ?>"><br><br>


      <label for="rooID"> *NEW Room: <Label>
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
          <input type="text" id="inDate" name="inDate" value="<?php echo $row['inDate']; ?>"><br><br>


          <label for="outDate">Check Out Date: </label>
          <input type="text" id="outDate" name="outDate" value="<?php echo $row['outDate']; ?>"><br><br>

        <label for="cNum">Contact number: <Label>
        <input type="text" id="cNum" name="cNum" minlength="13" maxlength="14" value="<?php echo $row['contactNUM']; ?>"><br>
        <small>Format: (123) 456-7891</small><br><br>


        <label for="bExtras">Booking Extras: <Label>
        <textarea type="text" name="bExtras" id="bExtras" cols="40" rows="5"><?php echo $row['extras']; ?></textarea><br><br>


        <label for="review">Booking Review: <Label>
        <textarea type="text" name="review" id="review" cols="40" rows="5"><?php echo $row['review']; ?></textarea><br><br>


     <input type="submit" name="submit" value="Update">
     <a href="listbookings.php">Cancel</a>
   </form>
</section>

<?php
} else {
 echo "<h2>Booking not found with that ID</h2>"; //simple error feedback
}
mysqli_close($DBC); //close the connection once done

echo '</div></div>';
require_once "footer.php";
?>

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
