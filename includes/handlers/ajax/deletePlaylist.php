
<?php


include("../../config.php");

if(isset($_POST['paramPlaylistId'])){

    $playlistId=$_POST['paramPlaylistId'];

    
    //echo connection_status();
    
    try{
        //place code here that could potentially throw an exception
    
   
        
    if(!mysqli_query($con, "DELETE FROM playlist WHERE id='$playlistId'")){
        echo("Error description: " . mysqli_error($con));
    }
    
    if(!mysqli_query($con, "DELETE FROM playlistSongs WHERE playlistId='$playlistId'")){
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
    echo "PlaylistId was not passed into delete.php playlist";
}
?>