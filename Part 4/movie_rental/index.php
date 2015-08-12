<!-- This page offers Login functionality into the website.
    HTML5 validations are done to identify proper email and password patterns.
    The PHP script checks for the right email-id,password combination and redirects user to home page
    after setting session variables.
    If the status in the Customer table is "inactive",
    we considered him as Administrator and he will be redirected to Admin Home page after login.
    Else, we considered him as Customer and he will be redirected to Customer Home page after login.
-->
<html>
    <style type="text/css">
        .mytable
        {
    position:absolute;
    margin-left:-150px; /* half of width */
    margin-top:-150px;  /* half of height */
    top:50%;
    left:50%;
        }
    </style>
    <title>Welcome to Movie Rental Database</title>
    <body>
         <?php
         // If the form is submitted the below loop will be called
         session_start();
         $status = '';
        if ($_SERVER["REQUEST_METHOD"] == "POST")
        {
            $email = $_POST['email_id'];
            $passwrd = $_POST['passwrd'];
            // mysql function to connect database
            $con=mysqli_connect("localhost","root","") or die("Not Connected");
            mysqli_query($con,"use movierental");
            
            if(isset($_POST['email_id']))
        {
            // query to check if the given email-id and password combination is correct 
            $sql="Select firstname,lastname,custid,gender,status from customer where emailid='$email' and password=MD5('$passwrd')";
            $result = mysqli_query($con,$sql);
            
            // If there are any results, session vaiables are set, if not error message is displayed
            if (mysqli_num_rows($result) > 0)
            {
                $row = mysqli_fetch_assoc($result);
                
                $_SESSION["firstname"] = $row['firstname'];
                $_SESSION["lastname"] = $row['lastname'];
                $_SESSION["custid"] = $row['custid'];
                $_SESSION["gend"] = $row['gender'];
                $admin = $row['status'];
                //Checks Whether the user is Administrator or Normal Customer, then redirects to that page
                if ($admin == "inactive")
                {
                    header("Location: admin_home.php");
                }
                else
                {
                    header("Location: home.php");
                }

                // After successfull login and if the status is Active, it will redirect to Customer home page
               
             // output data of each row
                //while($row = mysql_fetch_assoc($result)) {
                //    echo $row['firstname'];
                  //   }
                }
            else {
                 
            $status = 'Login not successful ! Please check your email id or password !!' ;
                }
                }
        }
?>
        <center>
        <br><br><br><br>
        <u><h3 style="font-size:x-large;vertical-align: middle">Movie Rental Database System</h3></u>
        <strong style="color: red"><?php echo $status ?></strong>
        <div class="mytable">
        <h3>Login Here</h3>      
        <form action='index.php' method='post'>
        <table >
        <tr>
            <th>Email Id :</th>
            <td><input type="email" name="email_id" required placeholder="valid email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"/></td>
        </tr>
        <tr>
            <th>Password :</th>
            <td><input type='password' name='passwrd' required placeholder="password"/></td>
        </tr>
        <tr style="text-align: center">
            <td colspan="2"><input type="submit" value="Submit"</td>
        </tr>
        <tr></tr>
        <tr style="text-align: center"><td colspan=2>New User ? <a href="register.php">Click Here</a></td></tr>
        </table>
        </form>
        </div>
    </center>    
    </body>
</html>