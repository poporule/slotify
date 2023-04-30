<?php

include("../../config.php");

if(isset($_POST['playlistId'])){

    if(isset($_POST['songId'])){
        $playlistId=$_POST['playlistId'];

        $songId=$_POST['songId'];
        
        //echo connection_status();
        
        try{
            //place code here that could potentially throw an exception
        
                
            if(!mysqli_query($con, "DELETE FROM playlistSongs WHERE  playlistID='$playlistId' AND songId = '$songId'")){
                echo("Error description: " . mysqli_error($con));
            }

        }
            catch(Exception $e)
            {
            //We will catch ANY exception that the try block will throw
                echo "$e";
            }
    }
    else{
        echo "songID was not passed in removePlaylist.php";
    }
}
else{
    echo "PlaylistId was not passed in removePlaylist.php";
}


?>