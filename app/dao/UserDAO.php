<?php

require_once "DAO.php";

/**
 * Created by PhpStorm.
 * User: Felipe
 * Date: 16/05/2017
 * Time: 09:47
 */
class UserDAO extends DAO
{
    public function search($id){

    }

    /**
     * Return the data searching by records that match an specific field
     * @param string $field
     * @param $value
     * @return User[]
     */
    public function searchByField(string $field, $value)
    {
        $sql = "SELECT name, lastname, email, password FROM user WHERE $field = :value";
        $stmt = $this->preapre($sql);
        $stmt->bindValue(":value", $value);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, "User");
    }

    /**
     * Persist the user into the database
     * @param User $user
     */
    public function insert(User $user){
        $stmt = $this->preapre("INSERT INTO user SET name = :name, lastname = :lastname, email = :email, password = :password");
        $stmt->bindValue(":name", $user->getName());
        $stmt->bindValue(":lastname", $user->getLastName());
        $stmt->bindValue(":email", $user->getEmail());
        $stmt->bindValue(":password", $user->getPassword());

        $stmt->execute();
    }
}