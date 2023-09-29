
var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;

var mouseDown = false;

var  currentIndex =0;

var repeat = false;

var shuffle = false;

var userLoggedIn;

var timer;

$(document).click(function(click){
    var taget = $(click.target); // thing that was clicked on

    if(!taget.hasClass("item") && !taget.hasClass("optionsButton")){ //si ne posede pas la class item ou optionsMenu
        hideOptionsMenu();
    }
     
});

$(window).scroll(function(){
    hideOptionsMenu();
});

//ajax event
$(document).on("change","select.playlist", function(){

    var select = $(this);

    var playlistID = select.val();
    var songId= select.prev(".songId").val();


  
    
    
    $.post("includes/handlers/ajax/addToPlaylist.php",{paramPlaylistId:playlistID, songId:songId }).done(function(){
        
      
        
        hideOptionsMenu();
        select.val("");
    });
    
    
});

function openPage(url){
    
    console.log("open page called");

    if(timer != null){
        clearTimeout(timer);
    }

    if(url.indexOf("?") == -1){
        url = url + "?";
    }
    //encoding URL
    var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn); //javascript function

        $("#mainContent").load(encodedUrl); 

    $("body").scrollTop(0);
    history.pushState(null, null, url); //mets le url comme du monde pcq sans ca le jquery reste a la meme page


    console.log("open page end");
}


function removeFromPlaylist(button, playlistId){


    

    var songId = $(button).prevAll(".songId").val();


    console.log("songID: ".songId);
    console.log("playlistId: ".playlistId);
    
    $.post("includes/handlers/ajax/removeFromPlaylist.php", {playlistId: playlistId, songId:songId })
    .done(function(error){

        /* TODO fix error
        if(error != ""){
            console.log(error);
            alert(error);
            return;
        }*/
        
    openPage("playlist.php?id="+playlistId);

    });

}


function logout(){
                                                //la fonction dans le deuxiemme parametre est caller apres que c'est done
    $.post("includes/handlers/ajax/logout.php", function(){
        location.reload();
    });

}

function updateEmail(emailClass){
    var emailValue = $("."+emailClass).val();

    $.post("includes/handlers/ajax/updateEmail.php",{email:emailValue, username: userLoggedIn})
    .done(function(response){

        $("."+emailClass).nextAll(".message").text(response);

    });

}



function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2){
    var oldPassword = $("."+oldPasswordClass).val();
    var newPassword1 = $("."+newPasswordClass1).val();
    var newPassword2 = $("."+newPasswordClass2).val();
    
    $.post("includes/handlers/ajax/updatePassword.php",
    {oldPassword:oldPassword, 
        newPassword1: newPassword1,
        newPassword2: newPassword2,
        username: userLoggedIn})
    .done(function(response){

        $("."+oldPasswordClass).nextAll(".message").text(response);

    });

}

function createPlaylist() {

    var popup = prompt("Please enter the name of your playlist");

    if(popup != null){

        //ajax call

        //console.log(popup);
        //console.log(userLoggedIn);
        
        $.post("includes/handlers/ajax/createPlaylist.php", { name: popup, username: userLoggedIn })
        .done(function(error){

            /* TODO fix error
            if(error != ""){
                console.log(error);
                alert(error);
                return;
            }*/
            
        openPage("yourMusic.php");

        });

    }

}

function deletePlaylist(playlistId){
    var propmt = confirm("Are you sure you want to delete this playlist?");

    if(prompt){
        console.log("Delete Playlist " + playlistId)
    

        //ajax call

        
        try{
        $.post("includes/handlers/ajax/deletePlaylist.php", { paramPlaylistId: playlistId})
        .done(function(error){

           /* 
          if(error != ""){
                console.log(error);
                
                return;
            }
            */
       
        openPage("yourMusic.php");

        });
    }
    catch (erro){
        console.error(erro);
    }
        console.log("end deleteplaylist");
    }
}

function hideOptionsMenu(){
    var menu = $(".optionsMenu");
    if(menu.css("display") != "none"){
        menu.css("display","none");
    }
}

function showOptionsMenu(button){
    
    var songId = $(button).prevAll(".songId").val();
    
    var menu = $(".optionsMenu");
    var menuWidth = menu.width();

    menu.find(".songId").val(songId);

    var scrollTop = $(window).scrollTop(); //distance from top of window to top of document
    var elementOffset = $(button).offset().top; //distance from top of document

    var top = elementOffset -scrollTop;

    var left = $(button).position().left;

    menu.css({"top": top +"px","left": left - menuWidth+"px","display":"inline"  });
}

function formatTime(seconds){
    var time = Math.round(seconds);
    var minutes = Math.floor(time / 60);
    var seconds = time - (minutes * 60);

    var extraZero="";

    if(seconds<10){
        extraZero="0"
    }

    return minutes + ":"+extraZero+seconds;
}


function playFirstSong() {

    setTrack(tempPlaylist[0], tempPlaylist, true);

}

function Audio(){

    //variables
    this.currentlyPlaying;
    this.audio = document.createElement('audio'); //ccree element html



    this.audio.addEventListener("ended", function(){

        nextSong();

    });

    /* src : source de l'audio */
    this.setTrack = function(track){
        
        this.audio.pause();
        this.currentlyPlaying = track;
        this.audio.src=track.path; // element audio a deja des propriete
        

    }


    this.play = function(){
        this.audio.play();
    }

    this.pause = function(){
        this.audio.pause();
    }


    this.setTime = function(seconds){
        this.audio.currentTime = seconds;
    }

    


    function updateTimeProgressBar(audio){
        $(".progressTime.current").text(formatTime(audio.currentTime));
        $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime));

        //la barre
        var progress = (audio.currentTime / audio.duration) * 100;
        $(".playbackBar .progress").css("width", progress+"%");
    }
    function updateVolumeProgressBar(audio){
        var volume = audio.volume * 100;
        $(".volumeBar .progress").css("width", volume+"%");
    }

    //event listner
    this.audio.addEventListener("canplay", function(){

        var duration = formatTime(this.duration);
        //jquery object  this refers ti the object that event was called on
        $(".progressTime.remaining").text(duration);
        updateVolumeProgressBar(this);

        
    });


    this.audio.addEventListener("timeupdate", function(){

        if(this.duration){
            updateTimeProgressBar(this);
        }

    });

    this.audio.addEventListener("volumechange",function(){
        updateVolumeProgressBar(this);
    });

}