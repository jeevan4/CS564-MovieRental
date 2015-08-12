<!-- This is the home page that appears after a user login's successfully.
     This page display's details of previous orders of a customer ordered by purchased date.
     If there are no previous orders, the page shows a message "No previous orders available"
-->

<html>
<link rel="stylesheet" type="text/css" href="styles.css">
<body>    
<?php
session_start();
// If the session is not SET, the page redirects to error page as it is an invalid request
if(isset($_SESSION["firstname"]))
{
 $first = $_SESSION["firstname"];
 $last = $_SESSION["lastname"];
 $custid = $_SESSION["custid"];
 $gend = $_SESSION["gend"];
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
<br>
<strong>Please Select any opetration</strong>
</div>
<div class="mynavigation">
<ul>
  <li><a class="mylink" href="#home">Home</a></li>
  <li><a class="mylink" href="update.php">Update Profile</a></li>
  <li><a class="mylink" href="browse.php">Browse Movies</a></li>
  <li><a class="mylink" href="logout.php">Logout</a></li>
</ul>
</div>
<div class="myorder">
 <center><strong style="color: red">Your Previous Order Details</strong></center>
 <?php
 // this section contains logic to display the details of previous order of a customer that logins.
 $con=mysqli_connect("localhost","root","") or die("Not Connected");
   mysqli_query($con,"use movierental");
 // Query to get previous order details of a customer ordered by the Purchased date
   $sql = "SELECT firstname as fname, 
       lastname as lname, 
       emailid as emailid, 
       borrowing_id as borrow, 
       start_date as startdt, 
       end_date as enddt, 
       e.exemplar_name as exmp, 
       mov.title as title,
       ord.movie_id as movie_id,
       med.medium_type as med, 
       total_price as tot, 
       vat as vat,
       total_price + vat as amt
FROM   (SELECT c.firstname    AS firstname, 
               c.lastname     AS lastname, 
               c.emailid      AS emailid, 
               b.borrowing_id AS borrowing_id, 
               b.start_date   AS start_date, 
               b.end_date     AS end_date, 
               b.total_price  AS total_price, 
               b.vat          AS vat, 
               m.exemplar_id  AS exemplar_id, 
               m.movie_id     AS movie_id, 
               m.medium_id    AS medium_id 
        FROM   borrowing b 
               INNER JOIN customer c 
                       ON c.custid = b.custid and b.custid='$custid'
               INNER JOIN moviesoffered m 
                       ON b.offered_id = m.offered_id) ord 
       INNER JOIN exemplar e 
               ON e.exemplar_id = ord.exemplar_id 
       INNER JOIN movie mov 
               ON mov.movie_id = ord.movie_id 
       INNER JOIN medium med 
               ON med.medium_id = ord.medium_id
       ORDER BY startdt";
   $result = mysqli_query($con,$sql);
   if(mysqli_num_rows($result)>0)
   {
   echo '<center><table class="TFtable"><th>Borrow Id</th><th>Title</th><th>Exemplar</th><th>Medium</th><th>Start Date</th><th>End Date</th><th>Amount</th><th>Vat</th><th>Total</th>';
   while($row = mysqli_fetch_assoc($result))
   {
    // $movie_id = $row['movie_id'];
   // $link = "rate.php?movie_id=$movie_id";
   echo "<tr><td>" .$row['borrow']. "</td>";
   echo "<td>" .$row['title']. "</td>";
   echo "<td>" .$row['exmp']. "</td>";
   echo "<td>" .$row['med']. "</td>";
   echo "<td>" .$row['startdt']. "</td>";
   echo "<td>" .$row['enddt']. "</td>";
   echo "<td>" .$row['tot']. "</td>";
   echo "<td>" .$row['vat']. "</td>";
   echo "<td>" .$row['amt']. "</td></tr>";

   }
    echo "</table></center>";
   }
   else {
    echo '<br><center><strong style="color : red">No Orders Placed Yet</strong><center>';
   }
 ?>
<div class="myTable">

</body>