<!--
This page allows customer to give from-date and to-date till when the user wishes
to rent the selected movie. Once the dates are selected, the system will calculate the total order amount for the
number of days the user wish to rent. Customer can confirm the order once the BILL is displayed.
A new entry will be inserted into borrowing table to store the order information.
-->

<html>
<link rel="stylesheet" type="text/css" href="styles.css">
<body>    
<?php
$todt = '';
session_start();
$first = $_SESSION["firstname"];
 $last = $_SESSION["lastname"];
 $custid = $_SESSION["custid"];
 $gend = $_SESSION["gend"];

 // If the session and Offered_id is not SET, the page redirects to error page as it is an invalid request

if(isset($_SESSION["firstname"]) && isset($_POST["offerid"]))
{
 $_SESSION["offerid"] = $_POST["offerid"];
 $_SESSION["title"] = $_POST["title"];
 $_SESSION["exemplar"] = $_POST["exemplar"];
 $_SESSION["price"] = $_POST["price"];
}
elseif($_SERVER["REQUEST_METHOD"] == "POST")
{
  $todt = $_POST['todt'];
}
else
{
   header("Location: error.html");
}
// Salutation section , Mr or Ms is decided based on the gender.

if ($gend == "M")
 {
    $salut = "Mr";
 }
 else {
     $salut = "Ms";
 }
 ?> 
<div class="myheading">
Hello <strong style="color: brown"> <?php echo "$salut. $first $last"?></strong>
<br><strong>Please Select any opetration</strong>
</div>
<div class="mynavigation">
<ul>
  <li><a class="mylink" href="home.php">Home</a></li>
  <li><a class="mylink" href="update.php">Update Profile</a></li>
  <li><a class="mylink" href="browse.php">Browse Movies</a></li>
  <li><a class="mylink" href="logout.php">Logout</a></li>
</ul>
<center><?php echo 'You are about to buy moive <b>' .$_SESSION['title']. '</b> from <b>' .$_SESSION['exemplar']. '</b>' ?></center>
</div>
<div class="mytable" align="center">
 <form action="buy.php" method="POST">
  <table>
  <tr><th> From Date :</th><td><input type="date" name="fromdt" value='<?php date_default_timezone_set('America/Los_Angeles'); echo date("Y-m-d"); ?>' disabled></td></tr>
  <tr><th> To Date :</th><td><input type="date" value = '<?php echo $todt ?>'name="todt" min='<?php echo date("Y-m-d"); ?>' required></td></tr>
  <tr><td colspan="2" align="center"><input type="submit" name="place_order" value="Place Order"></td></tr>
  <tr><td colspan="2" aligh="center">
  <?php
  if($todt != '')
  {
   // Below logic calculates the date difference to calculate the total order amount along with vat. 
    $diff = date_diff(date_create($todt),date_create(date("Y-m-d")))->format("%a");
    $amt = $_SESSION['price'] * $diff;
    $vat = $amt * 0.07;
    $tot = $vat+$amt;
    echo "Ordering  for $diff days <b>Amount :</b> $$amt <b>Vat :</b> $$vat <b>Total Amount :</b> $$tot";
    echo "</td></tr>";
    echo '<tr><td colspan="2" align="center"><form action="buy.php"><input type="submit" name="order-confirm" value="Confirm Order"/></td></td>';
  }?>
  <?php
  if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['order-confirm']))
  {
   // If the customer confirms the order , a new entry will be inserted inthe borrowing table.
   
     $offerid =  $_SESSION["offerid"];
     $con=mysqli_connect("localhost","root","") or die("Not Connected");
     mysqli_query($con,"use movierental");
     
     // A new Borrowing Id will be generated upon creation of a new order.
     
     $sql = "INSERT into borrowing Select concat('BO',LPAD(substring(max(borrowing_id),3,8) +1,8,0)),'$offerid','$custid',
             current_timestamp(),'$todt','$amt','$vat' from borrowing";
     if(mysqli_query($con,$sql))
    {
        echo '<tr><td colspan="2" align="center">Order Created Succseefully !</td></tr>';
    }
    else
    {
       echo '<tr><td colspan="2" align="center">Problem Occurred !</td></tr>';
    }
     
  }
  ?>
</table>
 </form>
</div>
</body>
</html>