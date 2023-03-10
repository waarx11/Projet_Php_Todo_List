<?php

class GatewayTache
{
    private Connection $conx;

    public function __construct()
    {
        global $base,$login,$mdp;
        $this->conx = new Connection($base, $login,$mdp);
    }

    private function resultsToArrayTache(array $results):array{
        $resArray=Array();
        Foreach ($results as $row) { //parcours
            if($row['userid']==null){
                $row['userid']='anonyme';
            }
            $resArray[ $row['id'] ]=new Tache($row['id'],$row['nom'],$row['dateCreation'],$row['dateFin'],$row['repete'],(int)$row['priorite'],$row['checked'],$row['userid'],$row['liste'] );
        }

        return $resArray;
    }
    public function addTache(TacheModal $tache)
    {
        $query = "INSERT INTO TACHE(nom,priorite,dateCreation,dateFin,repete,checked,userid,liste) VALUES(:nom, :priorite, :dateCreation, :dateFin, :repete,:checked, :userid, :list)";
        //Pas besoin de préciser l'id car il est automatiquement incrémenter.
        if ($this->conx->executeQuery($query, array(
            ':nom' => array($tache->getNom(), PDO::PARAM_STR_CHAR),
            ':priorite' => array($tache->getPriorite(), PDO::PARAM_INT),
            ':dateCreation' => array(date("Y-m-d"), PDO::PARAM_STR),
            ':dateFin' => array($tache->getDateFin(), PDO::PARAM_STR),
            ':repete' => array($tache->getRepetition(), PDO::PARAM_BOOL),
            ':checked' => array(false, PDO::PARAM_BOOL),
            ':userid' => array($tache->getUser(), PDO::PARAM_STR_CHAR),
            ':list' => array($tache->getListe(), PDO::PARAM_INT)))) {
        } else {
            throw new PDOException("Class GatewayTache inserTache : la query n'est pas executable");
        }
    }
    public function updateCheckTache(string $id,$idUser)
    {
        $query = "UPDATE TACHE t,LISTE l SET t.checked = not t.checked WHERE t.id=:id AND t.liste=l.id AND (l.visibilite OR l.userid=:user);";

        if ($this->conx->executeQuery($query, array(
            ':id' => array($id, PDO::PARAM_INT),
            ':user' => array($idUser ,$idUser == null ? PDO::PARAM_NULL :  PDO::PARAM_STR_CHAR)

        ))){
            if($this->conx->getStmt()->rowCount()>0){
                return true;
            }
            throw new Exception("Error Edit an task not exist or not belong to you !!");
        } else {
            throw new PDOException("Error Edit an task not exist or not belong to you !!");
        }
    }
    public function supprTachePublic(string $id,$idUser)
    {
        $query = "DELETE FROM TACHE WHERE id=:id AND ((userid=:user OR userid IS NULL ) OR liste IN (SELECT id FROM LISTE WHERE userid=:user));";
        if ($this->conx->executeQuery($query, array(
            ':id' => array($id, PDO::PARAM_INT),
            ':user' => array($idUser , $idUser == null ? PDO::PARAM_NULL : PDO::PARAM_STR_CHAR)

        ))){
            if ($this->conx->getStmt()->rowCount()>0){
                return true;
            }
            return false;

        } else {
            throw new PDOException("Error delete an task not exist or not belong to you !!");
        }
    }

    public function editTachePublic($idTask,$name,$repet,$priorite,$idUser)
    {
        $query = "UPDATE TACHE SET nom=:name, priorite=:priorite, repete=:repet WHERE id=:id AND (userid=:user OR userid IS NULL ) ;";
        if ($this->conx->executeQuery($query, array(
            ':id' => array($idTask, PDO::PARAM_INT),
            ':name' => array($name, PDO::PARAM_STR_CHAR),
            ':repet' => array($repet, PDO::PARAM_BOOL),
            ':priorite' => array($priorite, PDO::PARAM_INT),
            ':user' => array($idUser , $idUser == null ? PDO::PARAM_NULL : PDO::PARAM_STR_CHAR)

        ))){

            if($this->conx->getStmt()->rowCount()>0){
                echo "heloo";

                return true;
            }
            else {
                return false;
            }
        } else {
            throw new PDOException("Error delete an task not exist or not belong to you !!");
        }
    }

    public function getTachePublic($idTask, $idUser)
    {
        $query = "SELECT * FROM TACHE WHERE id=:id AND ((userid=:user OR userid IS NULL ) OR liste IN (SELECT id FROM LISTE WHERE userid=:user));";
        if ($this->conx->executeQuery($query, array(
            ':id' => array($idTask, PDO::PARAM_INT),
            ':user' => array($idUser , $idUser == null ? PDO::PARAM_NULL : PDO::PARAM_STR_CHAR)

        ))){
            if ($this->conx->getStmt()->rowCount()==1){
                Foreach ($this->conx->getResults() as $row) {
                    if($row['userid']==null){
                        $row['userid']='anonyme';
                    }
                   return new Tache($row['id'],$row['nom'],$row['dateCreation'],$row['dateFin'],$row['repete'],(int)$row['priorite'],$row['checked'],$row['userid'],$row['liste'] );
                }
            }
            return null;

        } else {
            throw new PDOException("Error Get a task not exist or not belong to you !!");
        }
    }

    public function deleteTacheByList(string $liste,string $user)
    {
        $query = "DELETE FROM TACHE WHERE liste IN (SELECT id FROM LISTE where userid=:user and id=:liste) ";
        if ($this->conx->executeQuery($query, array(
            ':liste' => array($liste, PDO::PARAM_STR_CHAR),
            ':user' => array($user, PDO::PARAM_STR_CHAR)
            ))){
            return true;
        } else {
            throw new PDOException("Class GatewayTache supprTache : la query n'est pas executable");
        }
    }

    public function findByIdListOrdered($list,$idUser): array
    {
        $query = "SELECT t.* FROM TACHE t, LISTE l WHERE t.liste=:list AND t.liste=l.id AND (l.visibilite OR l.userid=:user) ORDER BY priorite ASC, dateFin asc";
        if($this->conx->executeQuery($query, array(
            ':list' => array($list, PDO::PARAM_INT),
            ':user' => array($idUser, $idUser == null ? PDO::PARAM_NULL : PDO::PARAM_STR_CHAR)
        ))){
            return $this->resultsToArrayTache($this->conx->getResults());
        }
         else {
            throw new PDOException("No task for you ");
        }
    }

    //a voir pour barre de recherche
    public function findByName(string $name)
    {
        $query = "SELECT * FROM TACHE WHERE name=:name";
        if ($this->conx->executeQuery($query, array(':name' => array($name, PDO::PARAM_STR_CHAR)))) {
            return resultsToArrayTache($this->conx->getResults());
        } else {
            throw new PDOException("Class GatewayTache findByName : la query n'est pas executable");
        }
    }


}