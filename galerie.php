<!DOCTYPE html>
<html lang="fr"> 

<head>
    <title>Galerie</title>
    <link rel="stylesheet" href="styles/reset.css">
    <link rel="stylesheet" href="styles/galerie.css">
    <link rel="stylesheet" href="styles/font-awesome-4.7.0/css/font-awesome.css">
</head>
  
    <body>
        <main>
            <h2>Galerie</h2>
            <div class="galerie">
                
             <?php 
                
                include("galerie.class.php");
                
                // récupération de l'action sur l'URL
                if (isset($_GET['action'])) $action = $_GET['action']; else $action = '';

                // définition de l'action par défaut
                if (empty($action)) $action = 'list';

                // récupération de la clé si elle existe
                if (isset($_GET['id'])) $id= $_GET['id']; else $id= 0;
                // cela pourrait être fait dans une méthode getId() de la classe Image (voir cas delete)

                // création d'un objet pour appeler les méthodes
                $image = new Galerie();

                // choix de l'action
                switch ($action) {
                    case 'list':
                         $image->selectAllFromDB(); // afficher toutes les images
                         break;
                        
                    case 'updateAffiche':
                        $image->selectFromDB($id);   // récupérer les données de l'image sélectionnée
                        $image->afficheFormulaire($id);
                        break;
                        
                     case 'updateSql':
                        $image->chargePOST();
                        $image->updateDB($id);
                        $image->selectAllFromDB();
                        break;   
                        
                    case 'delete':
                        $image->deleteFromDB($id);
                        $image->selectAllFromDB();
                        break;
                        
                    case 'favori':
                        $image->addFavori($id);
                        break;
                        
                    case 'insert':
                        $image->chargePOST();
                        $image->insertIntoDB();
                        $image->selectAllFromDB();
                        break;
                }
            ?> 
                
            </div>
        </main>
        
        <aside>
           <h2>ajouter une image</h2>
            <form action="galerie.php?action=insert" method="post" enctype="multipart/form-data">
                <input class="form" type="text" name="titre" placeholder="titre"/>
                <input class="form" type="text" name="description" placeholder="description"/>
                <input type="hidden" name="MAX_FILES_SIZE" value="204800"/>
                <input  type="file" name="image"/>
                <input id="btn-validate" type="submit" value="Envoyer"/>
            </form>
        </aside>
        
    </body>
</html>






