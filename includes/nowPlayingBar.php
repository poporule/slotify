<?php 

	$songQuery = mysqli_query($con,"SELECT id FROM songs ORDER BY RAND() LIMIT 10");

	$resultArray = array();

	while($row = mysqli_fetch_array($songQuery)){

		array_push($resultArray,$row['id']);

	}

	//met ca en json pour le javascript
	$jsonArray = json_encode($resultArray);
?>

<script>

	
	

	$(document).ready(function(){
		var newPlaylist= <?php echo $jsonArray; ?>;
		audioElement = new Audio();

		setTrack(newPlaylist[0],newPlaylist, false);

		/* updateVolumeProgressBar(audioElement.audio); voir video 100 pour arranger ca manque probablement un 
		import*/ 


		$("#nowPlayingBarContainer").on("mousedown touchstart mousemove touchmove", function(e){
			e.preventDefault(); 
		});



		$(".playbackBar .progressBar").mousedown(function(){
			mouseDown=true;
		});


		
		$(".playbackBar .progressBar").mousemove(function(e){
			if(mouseDown){
				//set time of song, depending on position
				timeFromOffset(e,this); 
			}
		});

		
		$(".playbackBar .progressBar").mouseup(function(e){
			
				timeFromOffset(e,this); 
			
		});



		//pour pas highlighter
		$(".volumeBar .progressBar").mousedown(function(){
			mouseDown=true;
		});


		
		$(".volumeBar .progressBar").mousemove(function(e){
			if(mouseDown){
				var percentage = e.offsetX / $(this).width();
				if(percentage >= 0 && percentage <= 1){
					audioElement.audio.volume = percentage;
				}
			}
		});

		
		$(".volumeBar .progressBar").mouseup(function(e){
				
			var percentage = e.offsetX / $(this).width();
				if(percentage >= 0 && percentage <= 1){
					audioElement.audio.volume = percentage;
				}
		});


		$(document).mouseup(function(){
			mouseDown=false;
		});
	
	}); // wait until the page is ready before


	function timeFromOffset(mouse, progressBar){
		var percentage = (mouse.offsetX / $(progressBar).width()) *100;
		var seconds = audioElement.audio.duration *(percentage /100);
		audioElement.setTime(seconds);
	}


	function prevSong() {

		if(audioElement.audio.currentTime >= 3 || currentIndex ==0){
			 audioElement.setTime(0);
		}
		else{
			currentIndex = currentIndex -1;
			setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
		}

	}


	function nextSong() {


		if(repeat == true){
			audioElement.setTime(0);
			playSong();
			return;
		}

		if(currentIndex == currentPlaylist.length -1){
			currentIndex = 0; 
		}
		else{
			currentIndex = currentIndex +1;
		}

		var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex];

		setTrack(trackToPlay, currentPlaylist, true);
	}


	function setRepeat(){
		repeat= !repeat;

		var imageName = repeat ? "repeat-active.png" : "repeat.png";
		$(".controlButton.repeat img").attr("src","assets/images/icons/" + imageName);
	}


	
	function setMute(){
		
		//retourne un bool de si mute et le vire de bord avec le !
		audioElement.audio.muted = !audioElement.audio.muted;

		//imediate if TODO changer ca 
 		var imageName = audioElement.audio.muted  ? "volume-mute.png" : "volume.png";
		$(".controlButton.volume img").attr("src","assets/images/icons/" + imageName);
	}


	function setShuffle(){
	
		//retourne un bool de si mute et le vire de bord avec le !
		shuffle = !shuffle;

		//imediate if TODO changer ca 
 		var imageName = shuffle  ? "shuffle-active.png" : "shuffle.png";
		$(".controlButton.shuffle img").attr("src","assets/images/icons/" + imageName);

		if(shuffle == true){
			//randomize playlist
			shuffleArray(shufflePlaylist);
			currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id);

		}
		else{
			//shuffle off go back regular
			currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
		}
	}


	//TODO
	function shuffleArray(a){

		var j, x, i;
		for(i=a.length; i; i--){
			j=Math.floor(Math.random() *i);
			x = a[i-1];
			a[i -1] = a[j];
			a[j] =x;
		}
		 
	}

	function setTrack(trackId, newPlaylist, play){
		

		if(newPlaylist != currentPlaylist){
			currentPlaylist = newPlaylist;
			shufflePlaylist = currentPlaylist.slice(); //slice copy l'array
			shuffleArray(shufflePlaylist);
		}

		if(shuffle == true){
			
			currentIndex = shufflePlaylist.indexOf(trackId);

		}
		else{
			currentIndex = currentPlaylist.indexOf(trackId);
		}
		pauseSong();
		//audioElement.setTrack("assets/music/Ziggy Stardust/Starman.flac");
		
		//ajax call 1 er param la page; 2em le data jveu passer ; ques que on fait
		//avec le resultat
		$.post("includes/handlers/ajax/getSongJson.php",{ songId: trackId }, function(data){
	
			

		

			var track = JSON.parse(data);
			
			//JQUERY object
			$(".trackName span").text(track.title);
			
			console.log(track.artist);
			$.post("includes/handlers/ajax/getArtistJson.php",{ ArtistId: track.artist }, function(data){
				
				
				var artist = JSON.parse(data);
			
				$(".trackInfo .artistName span").text(artist.name);

				//ajoute a la now playing bar
				$(".trackInfo .artistName span").attr("onclick","openPage('artist.php?id=" +artist.id+ " ')");
			});


			$.post("includes/handlers/ajax/getAlbumJson.php",{ albumId: track.album }, function(data){
				
				
				var album = JSON.parse(data);
			
				$(".content .albumLink img").attr("src",album.artworkPath);
				$(".content .albumLink img").attr("onclick","openPage('album.php?id=" +album.id+ " ')");
				$(".trackInfo .trackName span").attr("onclick","openPage('album.php?id=" +album.id+ " ')");
			});


			audioElement.setTrack(track);



			if(play){
			playSong();
		}
 		});



		
	}

	function playSong(){

		//ajax
		if(audioElement.audio.currentTime ==0){
			$.post("includes/handlers/ajax/updatePlays.php", {songId: audioElement.currentlyPlaying.id});
				}
		


		//jquery object
		$(".controlButton.play").hide();
		$(".controlButton.pause").show();
		audioElement.play();
	}

function pauseSong(){
	$(".controlButton.play").show();
		$(".controlButton.pause").hide();
		audioElement.pause();
	}


</script>


<div id="nowPlayingBarContainer" >
<div id="nowPlayingBar">

		<div id=nowPlayingLeft>	
			<div class="content">
				<span class="albumLink">
					<img role="link" tabindex="0" src="" alt="" class="albumArtwork">
				</span>

				<div class="trackInfo">
					<span class="trackName">
						<span role="link" tabindex="0">  </span>
					</span>

					<span class="artistName">
						<span role="link" tabindex="0"></span>
					</span>
				</div>

			</div>
		</div>

		<div id=nowPlayingCenter>
			<div class="content playerControls">
			
				<div class="buttons">
					<button class="controlButton shuffle" title="Shuffle button" onclick="setShuffle()">
						<img src="assets/images/icons/shuffle.png" alt="Shuffle" >
					</button>
				

			
					<button class="controlButton previous" title="Previous button">
						<img src="assets/images/icons/previous.png" alt="Previous" onclick="prevSong()">
					</button>
				

				
					<button class="controlButton play" title="Play button" onclick="playSong()">
						<img src="assets/images/icons/play.png" alt="Play">
					</button>
			

					<button class="controlButton pause" title="Pause button" onclick="pauseSong()" style="display: none;">
						<img src="assets/images/icons/pause.png" alt="Pause">
					</button>

				
					<button class="controlButton next" title="Next button" onclick="nextSong()">
						<img src="assets/images/icons/next.png" alt="Next">
					</button>
				

				
					<button class="controlButton repeat" title="repeat button" onclick="setRepeat()">
						<img src="assets/images/icons/repeat.png" alt="repeat">
					</button>
				</div>


				<div class="playbackBar">

					<span class="progressTime current">0.00</span>
					<div class="progressBar" >

						<div class="progressBarBg">
							<div class="progress"></div>
						</div>

					</div>
					<span class="progressTime remaining">0.00</span>

				</div>

			</div>
		</div>

		<div id=nowPlayingRight>
			<div class="volumeBar">
				<button class="controlButton volume" title= "Volume button" onclick="setMute()">
						<img src="assets/images/icons/volume.png" alt="Volume">
				</button>

					<div class="progressBar" >

						<div class="progressBarBg">
							<div id="volume" class="progress"></div>
						</div>

					</div>

			</div>
		</div>
	</div>
    </div>