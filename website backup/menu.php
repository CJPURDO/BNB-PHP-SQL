<?php
session_start();

//function to check if the user is logged else send to the login page
function checkUser() {
    $_SESSION['URI'] = '';
    if ($_SESSION['loggedin'] == 1)
       return TRUE;
    else {
       $_SESSION['URI'] = 'http://ongaongabb.net'.$_SERVER['REQUEST_URI']; //save current url for redirect
       header('Location: http://ongaongabb.net/login2.php', true, 303);
    }
}

//just to show we are logged in
function loginStatus() {
    $un = $_SESSION['username'];
    if ($_SESSION['loggedin'] == 1)
        echo "<h2>Logged in as $un </h2>";
    else
        echo "<h2>Logged out</h2>";
}

//log a user in
function login($id,$username) {
   //simple redirect if a user tries to access a page they have not logged in to
   if ($_SESSION['loggedin'] == 0 and !empty($_SESSION['URI']))
        $uri = $_SESSION['URI'];
   else {
     $_SESSION['URI'] =  'http://ongaongabb.net/listcustomers.php';
     $uri = $_SESSION['URI'];
   }

   $_SESSION['loggedin'] = 1;
   $_SESSION['userid'] = $id;
   $_SESSION['username'] = $username;
   $_SESSION['URI'] = '';
   header('Location: '.$uri, true, 303);
}

//simple logout function
function logout(){
  $_SESSION['loggedin'] = 0;
  $_SESSION['userid'] = -1;
  $_SESSION['username'] = '';
  $_SESSION['URI'] = '';
  header('Location: http://ongaongabb.net/login2.php', true, 303);
}
?>

	<div id="header">
      <div id="logo">
        <div id="logo_text">
          <!-- class="logo_colour", allows you to change the colour of the text -->
          <h1><a href=""><span class="logo_colour">Ongaonga Bed & Breakfast</span></a></h1>
          <h2>Make yourself at home is our slogan. We offer some of the best beds on the east coast. Sleep well and rest well.</h2>
        </div>
      </div>
      <div id="menubar">
        <ul id="menu">
          <!-- put class="selected" in the li tag for the selected page - to highlight which page you're on -->
          <li class="selected"><a href="index.php">Home</a></li>
          <li><a href="listrooms.php">Rooms</a></li>
					<?php
            if (isset($_SESSION["username"])) {
							echo "<li><a href='listcustomers.php'>Customers</a></li>";
              echo "<li><a href='makebooking.php'>Make Booking</a></li>";
              echo "<li><a href='logout.php'>Logout</a></li>";
            }
            else {
              echo "<li><a href='registercustomer.php'>Register</a></li>";
              echo "<li><a href='login2.php'>Log in</a></li>";
            }
          ?>


        </ul>
      </div>
    </div>
