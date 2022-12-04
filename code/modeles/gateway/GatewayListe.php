<?php

namespace gateway;

use classeMetier\Liste;

require_once('Connection.php');

class GatewayListe
{
    private Connection $conx;

    public function __construct()
    {
        global $base,$login,$mdp;
        $this->conx = new Connection($base, $login,$mdp);
    }

    public function inserListe(Liste $liste)
    {
        $query = "INSERT INTO LISTE(nom,visibilite,description,userid) VALUES(:nom, :visibilite, :description, :userid)";
        //Pas besoin de préciser l'id car il est automatiquement incrémenter.
        if ($this->conx->executeQuery($query, array(
            ':nom' => array($liste->getNom(), PDO::PARAM_STR_CHAR),
            ':visibilite' => array($liste->getVisibilite(), PDO::PARAM_INT),
            ':description' => array($liste->getDescription(), PDO::PARAM_STR_CHAR),
            ':userid' => array($liste->getUserid(), PDO::PARAM_STR_CHAR)))) {
        } else {
            throw new \mysql_xdevapi\Exception("Class GatewayListe inserListe : la query n'est pas executable");
        }
    }

    public function supprList(string $id)
    {
        $query = "DELETE FROM LISTE WHERE id=:id;  ";
        if ($this->conx->executeQuery($query, array(':id' => array($id, PDO::PARAM_STR_CHAR)))){
            return true;
        } else {
            throw new \mysql_xdevapi\Exception("Class GatewayListe supprList : la query n'est pas executable");
        }
    }

    private function resultsToArrayList($results):array{
        $resArray=Array();
        Foreach ($results as $row) { //parcours
            $resArray[$row['id']]=new Liste($row['id'],$row['nom'],(bool)$row['visibilite'],$row['description'],$row['userid']);
        }
        return $resArray;
    }

    public function displayAll(): array
    {
        $query = "SELECT * FROM LISTE";
        if ($this->conx->executeQuery($query)) {
            return  resultsToArrayTache($this->conx->getResults());
        } else {
            throw new \mysql_xdevapi\Exception("Class GatewayListe displayAll : la query n'est pas executable");
        }
    }
    public function displayAllPublic(): array
    {
        $query = "SELECT * FROM LISTE where visibilite=true";
        if ($this->conx->executeQuery($query)) {
            return  resultsToArrayTache($this->conx->getResults());
        } else {
            throw new \mysql_xdevapi\Exception("Class GatewayListe displayAllPublic : la query n'est pas executable");
        }
    }

    public function findById($id): array
    {
        $query = "SELECT * FROM LISTE WHERE id=:id";
        if ($this->conx->executeQuery($query, array(':id' => array($id, PDO::PARAM_STR_CHAR)))) {
            return resultsToArrayTache($this->conx->getResults());
        } else {
            throw new \mysql_xdevapi\Exception("Class GatewayListe findById : la query n'est pas executable");
        }
    }

    public function findByName($name)
    {
        $query = "SELECT * FROM LISTE WHERE name=:name";
        if ($this->conx->executeQuery($query, array(':name' => array($name, PDO::PARAM_STR_CHAR)))) {
            return resultsToArrayTache($this->conx->getResults());
        } else {
            throw new \mysql_xdevapi\Exception("Class GatewayListe findByName : la query n'est pas executable");
        }
    }
    public function findByNamePublic($name)
    {
        $query = "SELECT * FROM LISTE WHERE name=:name AND visibilite=true";
        if ($this->conx->executeQuery($query, array(':name' => array($name, PDO::PARAM_STR_CHAR)))) {
            return resultsToArrayTache($this->conx->getResults());
        } else {
            throw new \mysql_xdevapi\Exception("Class GatewayListe findByNamePublic : la query n'est pas executable");
        }
    }

    public function findByUser($usrid)
    {
        $query = "SELECT * FROM LISTE WHERE usrid=:usrid";
        if ($this->conx->executeQuery($query, array(':name' => array($usrid, PDO::PARAM_STR_CHAR)))) {
            return resultsToArrayTache($this->conx->getResults());
        } else {
            throw new \mysql_xdevapi\Exception("Class GatewayListe findByUser : la query n'est pas executable");
        }
    }

}