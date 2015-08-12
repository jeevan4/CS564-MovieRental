<!-- This page allows Admins to update their information.
    The form can display current information from database.
    Admins can update all the details except for Email-id and Password.
-->

<html>
<link rel="stylesheet" type="text/css" href="styles.css">
<body>
<?php
session_start();
$status = '';
$first ='';
$last = '';
$email = '';
$paswrd ='';
$gend = '';
$dob = '';
$max_date = date("mm/dd/yyy");
$adrs = '';
$phone = '';
if(isset($_SESSION["firstname"]))
{
    $first = $_SESSION["firstname"];
    $last = $_SESSION["lastname"];
    $custid = $_SESSION["custid"];
    $gend = $_SESSION["gend"];
    $con=mysqli_connect("localhost","root","") or die("Not Connected");
    mysqli_query($con,"use movierental");
    $sql =   "Select firstname,lastname,gender,dob,emailid,address,phoneno,MD5(password) as password from customer where custid='$custid'";
    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_assoc($result);
    $first = $row['firstname'];
    $last = $row['lastname'];
    $email = $row['emailid'];
    $paswrd = $row['password'];
    $gend = $row['gender'];
    $dob = $row['dob'];
    $adrs = $row['address'];
    $phone = $row['phoneno'];
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $first = $_POST["fname"];
        $last = $_POST["lname"];
        $gend = $_POST["gender"];
        $dob = $_POST["dob"];
        $adrs = $_POST["address"];
        $phone = $_POST["mobile"];

        //This query is ued to update the customer details.

        $sql =   "Update CUSTOMER SET firstname = '$first',lastname = '$last',gender = '$gend',
              dob = '$dob', address = '$adrs', phoneno = '$phone' where custid='$custid'";
        if(mysqli_query($con,$sql))
        {
            $_SESSION["firstname"] = $first ;
            $_SESSION["lastname"] = $last ;
            $_SESSION["gend"] =  $gend ;
            $status = "Details Updated Successfully !";
        }
        else
        {
            $status = "Some error while processing !";
        }
    }
}
else
{
    header("Location: error.html");
}
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
        <li><a class="mylink" href="admin_home.php">Home</a></li>
        <li><a class="mylink" href="#update_admin">Update Profile</a></li>
        <li><a class="mylink" href="add_movie.php">Add Movies</a></li>
        <li><a class="mylink" href="logout.php">LogOut</a></li>
    </ul>
</div>
<div class="myTable">
    <h3 style="font-size:medium">Please update your information here</h3>
    <form action="update.php" method="post">
        <table>
            <tr>
                <th>First Name :</th>
                <td><input type="text" name="fname" required placeholder="First Name" value='<?php echo $first; ?>' ></td>
            </tr>
            <tr>
                <th>Last Name :</th>
                <td><input type="text" name="lname" required placeholder="Last Name" value='<?php echo $last; ?>'></td>
            </tr>
            <tr>
                <th>Email Id :</th>
                <td><input type="email" name="emailid" value='<?php echo $email; ?>' disabled placeholder="Email Id" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"></td>
            </tr>
            <tr>
                <th>Password :</th>
                <td><input type="password" name="paswrd" value='<?php echo $paswrd; ?>' disabled placeholder="password"></td>
            </tr>
            <tr>
                <td colspan='2' style="text-align: center"><input type="radio" name="gender" value="M" required <?php if($gend == 'M') {echo 'checked="checked"';} ?>/>Male<input type="radio" name="gender" value="F" <?php if($gend == 'F') {echo 'checked="checked"';} ?>/>Female</td>
            </tr>
            <tr>
                <th>Date of Birth :</th>
                <td><input type="date" name="dob" max='<?php echo $max_date; ?>' required value='<?php echo $dob; ?>'></td>
            </tr>
            <tr>
                <th>Address :</th>
                <td><textarea name="address" required><?php echo $adrs; ?></textarea></td>
            </tr>
            <tr>
                <th>Phone No :</th>
                <td><input type="tel"  pattern='\d{10}' name="mobile" required value='<?php echo $phone; ?>'></td>
            </tr>
            <tr><td></td></tr>
            <tr>
                <td colspan="2" style="text-align: center;vertical-align: bottom"><input type="submit" value="Update"/> <input type="reset" value="Reset"/></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;color: red"><?php echo $status; ?></td>
            </tr>
        </table>
    </form>
</div>
</body>