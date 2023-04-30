<?php

include("includes/includedFiles.php");

//pogne term du url
if(isset($_GET['term'])) { 
    $term = urldecode($_GET['term']);

}
else{

    $term="";

}
?>

<div class="searchContainer">

    <h4>Search for an artist, album or song</h4>

    <input type="text" class="searchInput" value="<?php echo $term;?>" placeholder="Start typing..." onfocus="this.value = this.value"> 

</div>


<script> 

    $(".searchInput").focus();

    $(function(){
       

        //event
        $(".searchInput").keyup(function(){
            clearTimeout(timer);

            timer = setTimeout(function(){
                
                var val =$(".searchInput").val();
                openPage("search.php?term="+val);


            }, 2000) //number of seconds to wait
        }) 
    })

</script>


<?php if($term=="") exit();?>


<div class="tracklistContainer borderBottom">
    <h2>SONGS</h2>
    <ul class ="tracklist">
    <?php

        $songsQuery = mysqli_query($con, "SELECT id FROM songs WHERE title LIKE '$term%'");


        if(mysqli_num_rows($songsQuery) == 0){
            echo "<span class= 'noResults'>No songs found matching ".$term."</span>";
        }


        $songIdArray = array();

            $i=1;
            while ($row = mysqli_fetch_array($songsQuery)) {

                if($i > 15){
                    break;
                }
                
                array_push($songIdArray,$row['id']);
                
                $albumSong = new Song($con,$row['id']);

               $albumArtist = $albumSong-> getArtist();

                echo "<li class='tracklistRow'>
                    <div class='trackCount'> 
                    <img class='play' src = 'assets/images/icons/playRed.png' onclick='setTrack(\"" . $albumSong->getId() ."\", tempPlaylist, true)'> 
                    <span class = 'trackNumber'> $i</span>
                    </div>


                    <div class='trackInfo'>
                        <span class='trackName'>". $albumSong->getTitle()." </span>
                        <span class='artistName'>".$albumArtist->getName() ." </span>
                     
                        </div>

                    <div class='trackOption'> 
                        <input type='hidden' class='songId' value='". $albumSong->getId()."'>
                        <img class='optionsButton' src='assets/images/icons/more.png' onclick='showOptionsMenu(this)'>
                    </div>

                    <div class='trackDuration'> 
                        <span class='duration'>".$albumSong->getDuration()."</span>
                    </div>

                </li>";

                $i++;
            }
        ?>


        <script>

            //converire en jsson et le retourne en objet
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>';
            tempPlaylist = JSON.parse(tempSongIds);

        </script>

    </ul>
</div>


<div class="artistsContainer borderBottom">

            <h2> ARTISTS </h2>


            <?php
            
            $artistsQuery = mysqli_query($con,"SELECT id FROM artists WHERE name LIKE '$term%' LIMIT 10");
            
            if(mysqli_num_rows($artistsQuery) == 0){
                echo "<span class= 'noResults'>No artists found matching ".$term."</span>";
            }


            while($row = mysqli_fetch_array($artistsQuery)){
                $artistFound = new Artist($con, $row['id']);
            
                echo "<div class='searchResultRow'>
                    <div class='artistName'>

                        <span role='ling' tabindex='0' onclick='openPage(\"artist.php?id=".$artistFound->getId()."\")'>
                            "
                            . $artistFound->getName().
                            "
                        </span>

                    </div>
                </div>";
            
            }

            ?>

</div>



<div class="gridViewContainer">
<h2>ALBUMS</h2>

<?php
        $albumQuery= mysqli_query($con,"SELECT * FROM albums WHERE title LIKE '$term%' LIMIT 10");




        if(mysqli_num_rows($albumQuery) == 0){
            echo "<span class= 'noResults'>No albums found matching ".$term."</span>";
        }

        while($row = mysqli_fetch_array($albumQuery)){
            


            
            
            echo "<div class='gridViewItem'>
            <span role='link' tabindex='0' onclick='openPage(\"album.php?id=".$row['id']."\")'> 
            <img src='". $row['artworkPath']  ."'>

            <div class='gridViewInfo'>"
.$row['title'].
            "</div>
</span>
            </div>";

        }

        if (!$albumQuery) {
            printf("Error: %s\n", mysqli_error($con));
            exit();
        }
?>

</div>


<nav class="optionsMenu">
    <input type="hidden" class="songId">
    
            <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername()); ?> <!-- ::is for accesing static-->
</nav>