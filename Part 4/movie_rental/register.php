<!-- This page helps users to register with all the necessary details.
    HTML5 validations are done to identify proper email pattern,date of birth and mobile number pattern
    Date of birth can't be more than current date. We handeled that using HTML5.
    The PHP script below store's all the details entered by Customer if the email id is not registered before.
-->
<html>
    <style type="text/css">
    .mytable
        {
    position: absolute;
    margin-left:-150px; /* half of width */
    margin-top:-150px;  /* half of height */
    top:50%;
    left:50%;
        }
    </style>
<body>   
<?php
 $status = '';
 $first =''; 
 $last = '';
 $email = '';
 $paswrd =''; 
 $gend = '';
 $dob = '';
 $adrs = '';
 $phone = '';
 if ($_SERVER["REQUEST_METHOD"] == "POST")
 {
session_start();
 $first = $_POST["fname"];
 $last = $_POST["lname"];
 $email = $_POST["emailid"];
 $paswrd = $_POST["paswrd"];
 $gend = $_POST["gender"];
 $dob = $_POST["dob"];
 $adrs = $_POST["address"];
 $phone = $_POST["mobile"];
 
// Checks if the email-id entered is already registered
$con=mysqli_connect("localhost","root","") or die("Not Connected");
mysqli_query($con,"use movierental");
$sql=   "Select emailid from customer where emailid='$email'";
$result = mysqli_query($con,$sql);
// A new user will be registered if the email-id is not registered before
// Encrypted Password is saved inthe database 
if(mysqli_num_rows($result) == 0)
{
    // A new customer id is generated and a new customer is inserted upon new user registration.
    
    $sql = "INSERT into customer SELECT concat('C',substring(max(custid),2,5)+1),'$first','$last',
    'active','$gend','$dob','$email','$adrs','$phone',MD5('$paswrd') from customer";
    if(mysqli_query($con,$sql))
    {
        $status = "User Created Successfully ! To login <a href='index.php'>click here</a>";
    }
    else
    {
        $status = "Some error while processing !";
    }
 }
 else {
    $status = "Email Id already exists ! Please register with another email !";
 }
 }
 ?>
<br><br><br><br>
<center><u><h3 style="font-size:x-large">Register here for Movie Rental Database System</h3></u>
</center>
<div class="myTable">
    <h3 style="font-size:medium">If you are already registered please login <a href="index.php">here</a></a></h3>
   <form action="register.php" method="post">
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
                <td><input type="email" name="emailid" required placeholder="Email Id" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"></td>
            </tr>
            <tr>
                <th>Password :</th>
                <td><input type="password" name="paswrd" placeholder="password"></td>
            </tr>
            <tr>
                <td colspan='2' style="text-align: center"><input type="radio" name="gender" value="M" required <?php if($gend == 'M') {echo 'checked="checked"';} ?>/>Male<input type="radio" name="gender" value="F" <?php if($gend == 'F') {echo 'checked="checked"';} ?>/>Female</td>
            </tr>
            <tr>
                <th>Date of Birth :</th>
                <td><input type="date" name="dob" max='<?php echo date("Y-m-d"); ?>' required value='<?php echo $dob; ?>'></td>
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
                <td colspan="2" style="text-align: center;vertical-align: bottom"><input type="submit" value="Register"/> <input type="reset" value="Reset"/></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;color: red"><?php echo $status; ?></td>
            </tr>
        </table>     
    </form> 
</div>
</body>
