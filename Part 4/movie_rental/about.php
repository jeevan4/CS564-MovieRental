<!--
This page returns all the details about the movie such as director,producer,genre, exemplar and medium
when a user clicks on a particular movie from browse.php. Buy option will be enabled if a movie is currently offered
by an exemplar. Customer will be redirected to buy.php if he clicks on BUY button.
-->

<html>
<link rel="stylesheet" type="text/css" href="styles.css">
<body>    
<?php
session_start();

// If the session and Movie_id is not SET, the page redirects to error page as it is an invalid request

if(isset($_SESSION["firstname"]) && isset($_GET['movie_id']))
{
 $first = $_SESSION["firstname"];
 $last = $_SESSION["lastname"];
 $custid = $_SESSION["custid"];
 $gend = $_SESSION["gend"];
 $movie_id = $_GET['movie_id'];
   $con=mysqli_connect("localhost","root","") or die("Not Connected");
   mysqli_query($con,"use movierental");
   
   // Query to display movie information along with its genre and exemplar availability details
   
   $sql = "Select
           mov.title as title,
           mov.director as director,
           mov.production_company as production,
           mov.genre_name as genre,
           ex.exemplar_name as exemplar,
           med.medium_type as medium_name,
           m.price_per_day as price,
           m.availability_status as availability,
           m.offered_id as offerid
           from moviesoffered m
           INNER JOIN medium med on m.medium_id = med.medium_id
           INNER JOIN (Select movie_id,title,director,production_company,name as genre_name from movie,genre where movie.genre_id = genre.genre_id) mov on mov.movie_id = m.movie_id
           INNER JOIN exemplar ex on ex.exemplar_id = m.exemplar_id
           where m.movie_id='$movie_id'";
   $result = mysqli_query($con,$sql);
   echo '<div class="myresult">';
   while($row = mysqli_fetch_assoc($result))
   {
   echo '<table class="TFtable" align="center"><th colspan="2">Movie Information</th>';
   echo "<tr><th>Moive Name :</th><td>" .$row['title']. "</td></tr>";
   echo "<tr><th>Director Name :</th><td>" .$row['director']. "</td></tr>";
   echo "<tr><th>Production Name :</th><td>" .$row['production']. "</td></tr>";
   echo "<tr><th>Moive Name :</th><td>" .$row['genre']. "</td></tr>";
   echo "<tr><th>Exemplar Name :</th><td>" .$row['exemplar']. "</td></tr>";
   echo "<tr><th>Medium :</th><td>" .$row['medium_name']. "</td></tr>";
   echo "<tr><th>Exemplar Name :</th><td>" .$row['exemplar']. "</td></tr>";
   echo "<tr><th>Price :</th><td>" .$row['price']. "</td></tr>";
   echo "<tr><th>Offer Id :</th><td>" .$row['offerid']. "</td></tr>";
   if($row['availability'] == 'Y')
   {
     echo '<form action="buy.php" method="POST"><input type="hidden" name="offerid" value='.$row['offerid'].'>';
     $title = $row['title'];
     echo "<input type='hidden' name='title' value='$title'>";
     echo '<input type="hidden" name="exemplar" value='.$row['exemplar'].'>';
     echo '<input type="hidden" name="price" value='.$row['price'].'>';
     echo '<tr><td colspan ="2" align ="center"><input type="submit" value="Buy"/></form></table><br>';
   }
   else { echo '<tr><td colspan ="2" align ="center"><input type="submit" name="movie-buy" disabled value="Buy"/></table><br>';}
   }

     echo "</div>";
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
  <li><a class="mylink" href="home.php">Home</a></li>
  <li><a class="mylink" href="update.php">Update Profile</a></li>
  <li><a class="mylink" href="browse.php">Browse Movies</a></li>
  <li><a class="mylink" href="logout.php">Logout</a></li>
</ul>
</div>
</body>