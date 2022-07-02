<?php
$title = "Browse Bookings";
include "header.php";
include "menu.php";
checkUser();
loginStatus();


echo '<div id="site_content">';
include "sidebar.php";
echo '<div id="content">';
include "config.php"; //load in any variables
$DBC = mysqli_connect(DBHOST, DBUSER, DBPASSWORD, DBDATABASE);

//insert DB code from here onwards
//check if the connection was good
if (mysqli_connect_errno()) {
    echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
    exit; //stop processing the page further
}
//prepare a query and send it to the server
$query = 'SELECT booking.bookID, booking.inDate, booking.outDate, customer.firstname, customer.lastname, room.roomname
FROM booking
INNER JOIN customer ON booking.customerID=customer.customerID
INNER JOIN room ON booking.roomID=room.roomID';
$result = mysqli_query($DBC,$query);
$rowcount = mysqli_num_rows($result);
?>
<h1>Booking list</h1>
<h2><a href='makebooking.php'>[Make a Booking]</a><a href="index.php">[Return to main page]</a></h2>
<table border="1">
<thead><tr><th>Checkin</th><th>Checkout</th><th>Firstname</th><th>Lastname</th><th>Room Name</th><th>Action</th></tr></thead>
<?php
//makes sure we have rooms
if ($rowcount > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
	  $id = $row['bookID'];
	  echo '<tr><td>'.$row['inDate'].'</td><td>'.$row['outDate'].'</td><td>'.$row['firstname'].'</td><td>'.$row['lastname'].'</td><td>'.$row['roomname'].'</td>';
	  echo         '<td><a href="editbooking.php?id='.$id.'">[edit]</a>';
    echo         '<a href="editreview.php?id='.$id.'">[edit review]</a>';
	  echo         '<a href="deletebooking.php?id='.$id.'">[Delete]</a></td>';
      echo '</tr>'.PHP_EOL;
   }
} else echo "<h2>No bookings found!</h2>"; //suitable feedback
mysqli_free_result($result); //free any memory used by the query
mysqli_close($DBC); //close the connection once done
?>
</table>

<?php
echo '</div></div>';
require_once "footer.php";
?>
