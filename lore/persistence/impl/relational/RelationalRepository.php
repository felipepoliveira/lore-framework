<?php
namespace lore\persistence;

require_once __DIR__ . "/../../Repository.php";
require_once "RelationalQuery.php";

use lore\Lore;
use lore\persistence\Query;

class RelationalRepository extends Repository
{
    /**
     * @var string
     */
    private $rdbms;

    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $database;

    /**
     * @var string
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var SqlTranslator
     */
    private $translator;

    function __construct($name, $data)
    {
        parent::__construct($name, $data);
        $this->loadPdo();
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return \PDO
     */
    public function getPdo(): \PDO
    {
        return $this->pdo;
    }

    /**
     * @return string
     */
    public function getRdbms(): string
    {
        return $this->rdbms;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @return SqlTranslator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    public function loadData($data)
    {
        //RDBMS
        if(isset($data["rdbms"])){
            $this->rdbms = $data["rdbms"];
            $this->loadTranslator();

        }else{
            throw new PersistenceException("You must configure witch relational database management system 
        this repository uses. Example 'rdbms' => 'mysql'");
        }

        //HOST
        if(isset($data["host"])){
            $this->host = $data["host"];
        }else{
            throw new PersistenceException("You must configure the host of the database server, example: 
            'host' => 'localhost'");
        }

        //DATABASE
        if(isset($data["database"])){
            $this->database = $data["database"];
        }else{
            throw new PersistenceException("You must configure witch database to access, example: 
            'database' => 'bookshelf_db'");
        }

        //USER
        if(isset($data["user"])){
            $this->user = $data["user"];
        }else{
            throw new PersistenceException("You must configure witch user to access the database, example: 
            'user' => 'root'");
        }

        //PASSWORD
        if(isset($data["password"])){
            $this->password = $data["password"];
        }else{
            $this->password = null;
        }

    }

    private function loadTranslator(){
        switch ($this->rdbms){
            case "mysql":
                require_once "GenericSqlTranslator.php";
                $this->translator = new GenericSqlTranslator($this);
                break;
            default:
                throw new PersistenceException("The rdbms: \"" . $this->rdbms . "\" is not implemented 
                    in the used persistence framework");
        }
    }

    protected function loadPdo(){

        $this->pdo = new \PDO($this->rdbms . ":" .
                                    "host=" . $this->host . ";" .
                                    "dbname=" . $this->database . ";",
                                    $this->user,  $this->password);

        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    protected function executeSql(\PDOStatement $stmt, $sql){
        try{
            $stmt->execute();
        }catch (\Exception $e){
            if(Lore::app()->getContext()->onDevelopment()){
                throw new PersistenceException("Error while trying to execute $sql\n" . $e->getMessage());
            }else{
                throw $e;
            }
        }
    }

    public function delete($entity): int
    {
        $sql = $this->translator->delete($entity);
        $stmt = $this->pdo->prepare($sql);
        $this->executeSql($stmt, $sql);

        return $stmt->rowCount();
    }

    public function exists($entity): bool
    {
        return false;
    }

    public function insert($entity)
    {
        $insertTranslationResult = $this->translator->insert($entity)->first();


        $this->pdo->beginTransaction();
        $insertData = $insertTranslationResult;
        $lastInsertId = false;
        do{
            //Get the id metadata
            $metadata =  $insertData->getEntity()->metadata();


            //Get only identification fields marked as @auto
            $autoIdentificationFields = array_filter($metadata->getIdentificationFields(), function($field){
                return ($field->isAuto());
            });

            //Thrown an error if has more than one auto field
            if(($count = count($autoIdentificationFields)) > 1){
                throw new PersistenceException("In the entity: " . $metadata->getEntityClassName() . " you have $count 
                @id @fields marked as @auto. You can't define that if you are using the " . RelationalRepository::class .
                " implementation for " . Repository::class . ". You can only use one @auto @id @field");
            }

            //Prepare the sql to be executed
            $insertData->setSql(str_replace("'@auto'", $lastInsertId, $insertData->getSql()));
            $stmt = $this->pdo->prepare($insertData->getSql());
            try{
                echo "Executing " . $insertData->getSql();
                $this->executeSql($stmt, $insertData->getSql());
                echo " everything OK!<br>";
            }catch (\Exception $e){
                //Rollback in case of transaction error
                $this->pdo->rollBack();
                throw $e;
            }


            if($count === 1){
                $lastInsertId = $this->pdo->lastInsertId("country");
                $metadata->setPropertyValue($autoIdentificationFields[0]->getPropertyName(), $lastInsertId, $insertData->getEntity());
            }

        }while($insertData = $insertData->getNextInsertion());

        $this->pdo->commit();}

    public function query($class = null): Query
    {
        $metadata = Entity::metadataOf($class);
        return new RelationalQuery($metadata, $this);
    }

    public function update($entity) : int
    {
        $sql = $this->translator->update($entity);

        $stmt = $this->pdo->prepare($sql);
        $this->executeSql($stmt, $sql);

        return $stmt->rowCount();
    }

}