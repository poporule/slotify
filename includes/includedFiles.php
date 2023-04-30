<?php

//check si la reuqete a ete envoiller par ajax ou manuellement par le user


//SI ENVOILLER PAR AJAX
if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){

    include("includes/config.php");
    include("includes/classes/User.php");
    include("includes/classes/Artist.php");
    include("includes/classes/Album.php");
    include("includes/classes/Song.php");
    include("includes/classes/Playlist.php");
    //ramase le user logged in du url
    if(isset($_GET['userLoggedIn'])){
        $userLoggedIn = new User($con, $_GET['userLoggedIn']);
    }
    else
    {
        echo "username variable was not passed into the page. Check the open page javascript";
    }


}
else{
    include("includes/header.php");
    include("includes/footer.php");


    $url = $_SERVER['REQUEST_URI'];
    //open the page 
    echo "<script>openPage('$url')</script>";

    exit();
}
?>