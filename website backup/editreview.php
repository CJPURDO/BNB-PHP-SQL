<?php
$title = "Edit Review";
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
    if (isset($_POST['review']) and !empty($_POST['review']) and is_string($_POST['review'])) {
       $rw = cleanInput($_POST['review']);
       $review = (strlen($rw)>2)?substr($rw,1,2):$rw; //check length and clip if too big
       //we would also do context checking here for contents, etc
    } else {
       $error++; //bump the error flag
       $msg .= 'Invalid roomID '; //append eror message
       $review = '';
    }



//save the room data if the error flag is still clear and room id is > 0
    if ($error == 0 and $id > 0) {
        $query = "UPDATE booking SET review=? WHERE bookID=?";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'si',$rw, $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        echo "<h2>Review details updated.</h2>";
        header('Location: http://ongaongabb.net/listbookings.php', true, 303);
    } else {
      echo "<h2>$msg</h2>".PHP_EOL;
    }
}
//locate the room to edit by using the roomID
//we also include the room ID in our form for sending it back for saving the data
$query = 'SELECT bookID,review FROM booking
WHERE bookID ='.$id;


$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result);
if ($rowcount > 0) {
  $row = mysqli_fetch_assoc($result);
?>
<h1>Review Details Update</h1>
<h2><a href='listbookings.php'>[Return to the Booking List]</a><a href='index.php'>[Return to the main page]</a></h2>

<p>

  <form method="POST" action="editreview.php" autocomplete="off">
        <input type="hidden" name="id" value="<?php echo $id;?>">
  <div>
        <label for="review">Booking Review:<Label><br>
        <textarea type="text" name="review" id="review" cols="40" rows="5"><?php echo $row['review']; ?></textarea>
        <p>
          </div>

     <input type="submit" name="submit" value="Update">
     <a href="listbookings.php">Cancel</a>
   </form>


  <?php
  } else {
    echo "<h2>booking not found with that ID</h2>"; //simple error feedback
  }
  mysqli_close($DBC); //close the connection once done

    echo '</div></div>';
    require_once "footer.php";
  ?>


