
<?php 

include("includes/includedFiles.php"); ?>

<h1 class="pageHeadingBig">You might aslo like</h1>

<div class="gridViewContainer">

    <?php
            $albumQuery= mysqli_query($con,"SELECT * FROM albums ORDER BY title LIMIT 10");

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

