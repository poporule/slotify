
<?php


include("../../config.php");




if(isset($_POST['paramPlaylistId']) && isset($_POST['songId'])){

    $playlistId=$_POST['paramPlaylistId'];
    $songId=$_POST['songId'];

    //echo connection_status();
    
    try{

    printf("<h3>");
    printf("SELECT MAX(playlistOrder) + 1 AS playlistOrder FROM playlistSongs WHERE playlistId='$playlistId'");
    printf("</h3>");

   /* $orderIdQuery= mysqli_query($con, "SELECT MAX(playlistOrder) + 1 AS playlistOrder FROM playlistSongs WHERE playlistId='$playlistId')
    "); */

if ($orderIdQuery = mysqli_query($con,"SELECT IFNULL (MAX(playlistOrder) + 1,1) AS playlistOrder FROM playlistSongs WHERE playlistId='$playlistId'")) {
    
    //echo "Returned rows are: " . mysqli_num_rows($orderIdQuery);

    while ($row=mysqli_fetch_row($orderIdQuery))
    {
        $order =  $row[0];
        
        $query =mysqli_query($con, "INSERT INTO playlistSongs (songId,playlistId,playlistOrder)  VALUES('$songId','$playlistId','$order')");

    

    }
 
  }
  else{

    
    echo("Error description: " . mysqli_error($con));
  }
  
    

    


    mysqli_free_result($orderIdQuery);
}
    catch(Exception $e)
    {
      //We will catch ANY exception that the try block will throw
        echo "$e";
    }

}
else{
    echo "PlaylistId or songid was not passed into addToPlaylist.php playlist";
}


    
?>