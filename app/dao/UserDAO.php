<?php

require_once  "DAO.php";

class UserDAO extends DAO
{
    /**
     * Insert an user in database
     * @param User $user
     */
    public function insert(User $user){
        $sql = "INSERT INTO user(name, email, password) VALUES (:name, :email, :password)";
        $stmt =  $this->pdo->prepare($sql);

        $stmt->bindValue("name", $user->getName());
        $stmt->bindValue("email", $user->getEmail());
        $stmt->bindValue("password", $user->getPassword());

        $stmt->execute();
        $user->setId($this->pdo->lastInsertId());
    }
}