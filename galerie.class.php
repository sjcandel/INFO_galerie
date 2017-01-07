<?php





///////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////


class Galerie {
	private $id;
	private $titre;
	private $description;
	private $image;
	private $dateCreation;
    private $favori;
    
	
	function __construct() {
		$this->id = 0;
		$this->titre = 'inconnu';
		$this->description = 'inconnue';
		$this->image = 'inconnu';
		$this->dateCreation = 'date("j/n/Y", time())';
        $this->favori = 0;
	}
	
//	function getId() {
//		return $this->id;
//	}
//	
//	function setId($id) {
//		$this->id = intval($id); // tranforme $id en entier, pour être sur du type
//	}
//	
	function setAttributs($id, $titre, $description, $image, $dateCreation) {
		$this->id = intval($id); // tranforme $id en entier, pour être sur du type
		$this->titre = $titre;
		$this->description = $description;
		$this->image = $image;
		$this->dateCreation = $dateCreation;
        $this->favori = $favori;
	}
	
	function affiche() {        
        echo '<div class="galerie-item">';
        echo '<div class="galerie-item-titre"> ' .$this->titre. '</div>';
        echo '<div class="galerie-item-desc"> ' .$this->description. '</div>';
        echo '<img src=" '.$this->image.' " alt="' .$this->titre. '"><br>';
        echo '<div class="btn-flex">';
        echo '<button id="btn-update"><a href="galerie.php?action=updateAffiche&id='.$this->id.'"><i class="fa fa-pencil" aria-hidden="true"></i></a></button> ';
        echo '<button id="btn-delete"><a href="galerie.php?action=delete&id='.$this->id.'"><i class="fa fa-times" aria-hidden="true"></i></a></button>  ';
        echo '<button id="btn-like"><a href="galerie.php?action=favori&id='.$this->id.'"><i class="fa fa-heart" aria-hidden="true"></i></a></button> ';
        echo $this->favori.'</div></div>';
	}
	
	function afficheFormulaire($id) {
        echo '<img src=" '.$this->image.' " alt="' .$this->titre. '" style="width:300px;"><br>';
        
		print('<form action="galerie.php?action=updateSql&id='.$this->id.'" method="post">
			<table>
            
            <tr>
                <td>Titre</td>
                <td><input type="text" name="titre" placeholder="titre" value="'.$this->titre.'"/></td>
            </tr>
            
            <tr>
                <td>Description</td>
                <td><input type="text" name="description" placeholder="description" value="'.$this->description.'"/></td>
            </tr>
            
            <tr>
            <td>Image</td>
                <td>
            <input type="hidden" name="MAX_FILES_SIZE" value="204800"/>
            <input  type="file" name="image" value="'.$this->image.'"/>
            <input type="hidden" name="editImage" value="'.$id.'"/>
			     </td>
            </tr>
            
            <tr><td>
			<input id="btn-validate" type="submit" value="Modifier"/>
			</td></tr>
            
			</table>
			</form>');
	}
    
    
	
	function chargePOST() {
        if (isset($_POST['editImage'])) { //il s'agit d'une mise à jour des infos
            if (isset($_POST['id']) && !empty($_POST['id']))
                $this->id = intval($_POST['id']); // pour être sur d'avoir un entier
            if (isset($_POST['titre']) && !empty($_POST['titre']))
                $this->titre = $_POST['titre'];
            if (isset($_POST['description']) && !empty($_POST['description']))
                $this->description = $_POST['description'];
            
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $temp = $_FILES['image']['tmp_name'];
                $name = $_FILES['image']['name'];
                $size = $_FILES['image']['size'];
                $type = $_FILES['image']['type'];
                $this->image = './images_uploads/'.$name;
                // déplacement du fichier reçu
                move_uploaded_file($temp, $this->image);
            } 
            else {
                $this->image = $_FILES['image']['name']; 
                exit;
            }
        } 
        
        else { //il s'agit d'une insertion d'infos
            if (isset($_POST['id']) && !empty($_POST['id']))
                $this->id = intval($_POST['id']); // pour être sur d'avoir un entier
            if (isset($_POST['titre']) && !empty($_POST['titre']))
                $this->titre = $_POST['titre'];
            if (isset($_POST['description']) && !empty($_POST['description']))
                $this->description = $_POST['description'];
            if (isset($_POST['dateCreation']) && !empty($_POST['dateCreation']))
                $this->dateCreation = $_POST['dateCreation']; 
            
            if (isset($_FILES['image']['name']) && !empty($_FILES['image']['name'])) {
                $temp = $_FILES['image']['tmp_name'];
                $name = $_FILES['image']['name'];
                $size = $_FILES['image']['size'];
                $type = $_FILES['image']['type'];
                $this->image = './images_uploads/'.$name;
                // déplacement du fichier reçu
                move_uploaded_file($temp, $this->image);
            } 
            else {
                print("Aucune image reçue !");
                exit;
            }
            
        }
		
	}

	function selectFromDB($id) {
		try {
			$pdo = new PDO("mysql:host=base.iha.unistra.fr;dbname=candel_INFO3_galerie", "candel", "HZ91000bysc");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// construction de la requête
			$sql = 'select * from Galerie where id='.$id.';';
			// exécution de la requête
			//print('<p>Exécution de la requête : '.$sql.'</p>');
			$result = $pdo->query($sql);
			
			// on récupère la première ligne du résultat
            
            $objet = $result->fetch(PDO::FETCH_OBJ);
              if (!empty($objet)) {
                $this->id = $objet->id;
                $this->titre = $objet->titre;
                $this->description = $objet->description;
                $this->image = $objet->image;
                $this->dateCreation = $objet->dateCreation;
                $this->favori = $objet->favori;
              }
              else {
                print('<p>Erreur : utilisateur inexistant '.$id.'</p>');
              }
              $pdo = null;
    
		}
		catch (Exception $e) {
			// code exécuté si une erreur à lieu dans le bloc try
			print('<p>Erreur : '.$e->getMessage().'</p>');
			exit;
		}
	}
    
    function selectAllFromDB() {
		try {
			$pdo = new PDO("mysql:host=base.iha.unistra.fr;dbname=candel_INFO3_galerie", "candel", "HZ91000bysc");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// construction de la requête
			$sql = 'select * from Galerie;';
			// exécution de la requête
			//print('<p>Exécution de la requête : '.$sql.'</p>');
			$result = $pdo->query($sql);
			
			// on récupère la première ligne du résultat
            
            while ($objet = $result->fetch(PDO::FETCH_OBJ)) {
                $this->id = $objet->id;
                $this->titre = $objet->titre;
                $this->description = $objet->description;
                $this->image = $objet->image;
                $this->dateCreation = $objet->dateCreation;
                $this->favori = $objet->favori;
                $this->affiche();
              }
              
              $pdo = null;
    
		}
		catch (Exception $e) {
			// code exécuté si une erreur à lieu dans le bloc try
			print('<p>Erreur : '.$e->getMessage().'</p>');
			exit;
		}
	}
    
	function insertIntoDB() {
		try {
			$pdo = new PDO("mysql:host=base.iha.unistra.fr;dbname=candel_INFO3_galerie", "candel", "HZ91000bysc");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// construction de la requête
			$sql = 'insert into Galerie (titre, description, image, dateCreation, favori)
					values (\''.$this->titre.'\', \''.$this->description.'\',
					\''.$this->image.'\', \''.$this->dateCreation.'\', \''.$this->favori.'\');';

			// exécution de la requête
			//print('<p>Exécution de la requête : '.$sql.'</p>');
			$nb = $pdo->exec($sql);
			//print('<p>'.$nb.' ligne(s) ont été insérée(s)</p>');
			
			// récupération de la dernière clé créée (auto-incrément)
			$this->id = $pdo->lastInsertId();
			
			// fin de la connexion
			$pdo = null;
		}
		catch (Exception $e) {
			// code exécuté si une erreur à lieu dans le bloc try
			print('<p>Erreur : '.$e->getMessage().'</p>');
			exit;
		}
	}

	function updateDB($id) {
		try {
			// connexion
			$pdo = new PDO("mysql:host=base.iha.unistra.fr;dbname=candel_INFO3_galerie", "candel", "HZ91000bysc");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

			// construction de la requête
			$sql = 'update Galerie set titre = \''.$this->titre.'\', description = \''.$this->description.
				'\', image = \''.$this->image.'\', favori = \''.$this->favori.'\' where id = '.$id.';';
			print('<p>Exécution de la requête : '.$sql.'</p>');

			// exécution de la requête
			$nb = $pdo->exec($sql);
			print('<p>'.$nb.' ligne(s) ont été mise(s) à jour</p>');

			// fin de la connexion
			$pdo = null;
		}
		catch (Exception $e) {
			// code exécuté si une erreur à lieu dans le bloc try
			print('<p>Erreur : '.$e->getMessage().'</p>');
			exit;
		}
	}

	function deleteFromDB($id) {
		try {
			// connexion
			$pdo = new PDO("mysql:host=base.iha.unistra.fr;dbname=candel_INFO3_galerie", "candel", "HZ91000bysc");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// construction de la requête
			$sql = 'delete from Galerie where id = '.$id.';';
			// exécution de la requête
			$nb = $pdo->exec($sql);
			if ($nb > 0) {
				print('<p>Exécution de la requête : '.$sql.'</p>');
				print('<p>'.$nb.' ligne(s) ont été supprimée(s)</p>');
			}

			// fin de la connexion
			$pdo = null;
		}
		catch (Exception $e) {
			// code exécuté si une erreur à lieu dans le bloc try
			print('<p>Erreur : '.$e->getMessage().'</p>');
			exit;
		}
	}
    
  
    function addFavori($id) {
		try {
			// connexion
			$pdo = new PDO("mysql:host=base.iha.unistra.fr;dbname=candel_INFO3_galerie", "candel", "HZ91000bysc");
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// construction de la requête
			$sql = 'update Galerie set favori = \''.$this->favori.'\' where id = '.$id.';';
			// exécution de la requête
			$nb = $pdo->exec($sql);
			if ($nb > 0) {
				print('<p>Exécution de la requête : '.$sql.'</p>');
				print('<p>'.$nb.' ligne(s) ont été mise(s) à jour</p>');
			}

			// fin de la connexion
			$pdo = null;
		}
		catch (Exception $e) {
			// code exécuté si une erreur à lieu dans le bloc try
			print('<p>Erreur : '.$e->getMessage().'</p>');
			exit;
		}
	}
    
    
    
    
    
}
?>