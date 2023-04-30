<?php

include("../../config.php");


//TODO changer les mysqli demem
if(isset($_POST['name']) && isset($_POST['username'])){

    if(is_resource($connection))
    {
        echo "connecter";
    }

    $name=$_POST['name'];
    $username = $_POST['username'];
    $date= date("Y-m-d");

    if(!mysqli_query($con,"INSERT INTO playlist( `name`, `owner`, `dateCreated`) VALUES('$name','$username','$date')")){

    echo("Error description: " . mysqli_error($con));
    }
 
    //mysqli_close($con);

}
else{
    echo "name or username parameter not passed into file.";
}
?>