<!--This administration page is used to add new movies into the system-->

<html>
<link rel="stylesheet" type="text/css" href="styles.css">
<body>

<?php
session_start();
if (isset($_SESSION["firstname"])) {
    $first = $_SESSION["firstname"];
    $last = $_SESSION["lastname"];
    $custid = $_SESSION["custid"];
    $gend = $_SESSION["gend"];

} else {
    header("Location: error.html");
}
if ($gend == "M") {
    $salut = "Mr";
} else {
    $salut = "Ms";
}

$status = '';
$movie = '';
$director = '';
$production = '';
$age = '';
$episode = '';
$genre = '';
$pod = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $con = mysql_connect("localhost","root","") or die("Not Connected");
    mysql_query("use movierental");

    $movie = $_POST["mname"];
    $director = $_POST["dname"];
    $production = $_POST["pname"];
    $age = $_POST["age"];
    $episode = $_POST["episode"];
    $genre = $_POST["name"];
    $exemplar = $_POST["exemplar_name"];
    $medium = $_POST["medium_type"];
    $pod = $_POST["Price_day"];
    $availability = $_POST["avail"];

    $sql = "Select title from movie where title='$movie'";
    $result_movie = mysql_query($sql);
    if (mysql_numrows($result_movie) == 0) {
        $sql_genre = "SELECT genre_id FROM genre WHERE name = '$genre'";
        $result = mysql_query($sql_genre);
        $row = mysql_fetch_assoc($result);
        $genre_id = $row['genre_id'];

        //Inserts new movie into the database, if movie is not present in database.

        $sql = "INSERT into movie SELECT concat('M',LPAD(substring(max(movie_id),2,8)+1,8,0)),'$movie','$director','$production','$genre_id',
		        '$age','$episode' from movie";

        $var1 = mysql_query($sql);

        $sql_exemplar = "Select exemplar_id from exemplar where exemplar_name='$exemplar'";
        $result = mysql_query($sql_exemplar);
        $row = mysql_fetch_assoc($result);
        $exemplar_id = $row['exemplar_id'];

        $sql_medium = "Select medium_id from medium where medium_type='$medium'";
        $result = mysql_query($sql_medium);
        $row = mysql_fetch_assoc($result);
        $medium_id = $row['medium_id'];

        $sql_movie = "Select movie_id from movie where title='$movie'";
        $result = mysql_query($sql_movie);
        $row = mysql_fetch_assoc($result);
        $movie_id = $row['movie_id'];

        //Inserts new movie into the moviesoffered table in database, if movie is not present in database.

        $sql1 = "INSERT into moviesoffered SELECT concat('O',LPAD(substring(max(offered_id),2,8)+1,8,0)),'$exemplar_id','$movie_id',
        '$medium_id','$pod', '$availability' from moviesoffered";

        $var = mysql_query($sql1);

        if ($var1 && $var) {

            $status = "Movie added Successfully";

        }
            else{
                $status = "Some error while Adding Movie !";
            }

    } elseif(mysql_numrows($result_movie) == 1) { #if movie is already present in movie table
        $status = "Some error while Adding Movie !";
        $sql = "Select genre_id from movie where title='$movie'";
        $res = mysql_query($sql);
        $row = mysql_fetch_assoc($res);
        $movie_genre = $row['genre_id'];

        $sql = "Select genre_id from genre  where name = '$genre'";
        $res = mysql_query($sql);
        $row = mysql_fetch_assoc($res);
        $movie_genre1 = $row['genre_id'];

        //Checks if a movie with same genre is already present.

        if($movie_genre == $movie_genre1){
            $status = "Movie With Same Genre Already Exist !";
        } else{
            $sql_genre = "SELECT genre_id FROM genre WHERE name = '$genre'";
            $result = mysql_query($sql_genre);
            $row = mysql_fetch_assoc($result);
            $genre_id = $row['genre_id'];

            //Inserts movie with same movie name but different genre into database.

            $sql = "INSERT into movie SELECT concat('M',LPAD(substring(max(movie_id),2,8)+1,8,0)),'$movie','$director','$production','$genre_id',
		        '$age','$episode' from movie";

            $var1 = mysql_query($sql);
            if ($var1) {

                $status = "Movie with Another Genre added Successfully";

            }
        }

    }
    else {

    }
//        die("CF"+mysqli_connect_error()) ;


}
?>
<div class="myheading">
    Hello <strong style="color: brown"> <?php echo "$salut. $first $last" ?></strong>
    <br>
    <strong>Please Select any opetration</strong>
</div>
<div class="mynavigation">
    <ul>
        <li><a class="mylink" href="admin_home.php">Admin Home</a></li>
        <li><a class="mylink" href="update_admin.php">Update Profile</a></li>
        <li><a class="mylink" href="add_movie.php">Add Movies</a></li>
        <li><a class="mylink" href="logout.php">Logout</a></li>
    </ul>
</div>

<div class="myTable">
    <h3 style="font-size:x-large">Enter movie details</h3>

    <form action="add_movie.php" method="post">
        <table>
            <tr>
                <th>Movie Name :</th>
                <td><input type="text" name="mname" required placeholder="Movie Name" value='<?php echo $movie; ?>'>
                </td>
            </tr>
            <tr>
                <th>Director :</th>
                <td><input type="text" name="dname" required placeholder="Director Name"
                           value='<?php echo $director; ?>'></td>
            </tr>
            <tr>
                <th>Production Company :</th>
                <td><input type="text" name="pname" required placeholder="Production Name"
                           value='<?php echo $production; ?>'></td>
            </tr>
            <tr>
                <th>Genre :</th>
                <td>
                    <?php
                    $con = mysql_connect("localhost", "root", "") or die("Not Connected");
                    mysql_query("use movierental");
                    $sql = "Select name from genre";
                    $q = mysql_query($sql);
                    echo "<select name=\"name\">";
                    echo "<option size =30 ></option>";
                    while ($row = mysql_fetch_array($q)) {
                        echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                    }
                    echo "</select>";
                    ?>
                </td>
            </tr>
            <tr>
                <th>Exemplar :</th>
                <td>
                    <?php
                    $con = mysql_connect("localhost", "root", "") or die("Not Connected");
                    mysql_query("use movierental");
                    $sql = "select exemplar_name from exemplar";
                    $q = mysql_query($sql);
                    echo "<select name=\"exemplar_name\">";
                    echo "<option size =25 ></option>";
                    while ($row = mysql_fetch_array($q)) {
                        echo "<option value='" . $row['exemplar_name'] . "'>" . $row['exemplar_name'] . "</option>";
                    }
                    echo "</select>";
                    ?>
                </td>
            </tr>
            <tr>
                <th>Available Medium :</th>
                <td>
                    <?php
                    $con = mysql_connect("localhost", "root", "") or die("Not Connected");
                    mysql_query("use movierental");
                    $sql = "select medium_type from medium";
                    $q = mysql_query($sql);
                    echo "<select name=\"medium_type\">";
                    echo "<option size =15 ></option>";
                    while ($row = mysql_fetch_array($q)) {
                        echo "<option value='" . $row['medium_type'] . "'>" . $row['medium_type'] . "</option>";
                    }
                    echo "</select>";
                    ?>
                </td>
            </tr>
            <tr>
                <th>Minimum Age :</th>
                <td><input type="input" name="age" required placeholder="Minimum Age" required
                           value='<?php echo $age; ?>'></td>
            </tr>
            <tr>
                <th>Episode Id :</th>
                <td><input type="input" name="episode" required placeholder="Episode ID" required
                           value='<?php echo $episode; ?>'></td>
            </tr>
            <tr>
                <th>Price Per Day :</th>
                <td><input type="input" name="Price_day" required placeholder="Price Per Day" required
                           value='<?php echo $pod; ?>'></td>
            </tr>
            <tr>
                <th>Availability :</th>
                <td>
                    <select name="avail">
                        <option value="Y">Y</option>
                        <option value="N">N</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center;vertical-align: bottom"><input type="submit"
                                                                                         value="Add Movie"/> <input
                        type="reset" value="Reset"/></td>
            </tr>

            <tr>
                <td colspan="2" style="text-align: center;color: red"><?php echo $status; ?></td>
            </tr>
        </table>
    </form>
</div>
</body>
</html>
