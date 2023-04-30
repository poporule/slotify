<?php
	class Artist {

		private $con;
		private $id;
   


		public function __construct($con,$id) {
			$this->con = $con;
			$this->id = $id;
		}

        
        public function getName(){
            $artitstQuery = mysqli_query($this->con,"SELECT name FROM  artists WHERE id='$this->id'");


            $artist= mysqli_fetch_array($artitstQuery);

            return $artist['name'];
        }


		public function getId(){
			return $this->id;
		}

		public function getSongIds(){
			$query = mysqli_query($this->con, "SELECT id FROM songs WHERE artist='$this->id' ORDER BY plays DESC");

            $array = array(); //empty array

            while($row = mysqli_fetch_array($query)){
                array_push($array,$row['id']); //pousse quoi dans quoi
            }

            return $array;
		}

	}
?>