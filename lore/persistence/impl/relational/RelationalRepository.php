<?php
namespace lore\persistence;

require_once __DIR__ . "/../../Repository.php";

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

    public function loadTranslator(){
        switch ($this->rdbms){
            case "mysql":
                require_once "MySqlTranslator.php";
                $this->translator = new MySqlTranslator($this);
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

    public function delete($entity): bool
    {
        // TODO: Implement delete() method.
    }

    public function exists($entity): bool
    {
        return false;
    }

    public function insert($entity)
    {
        $stmt = $this->translator->insert($entity);
        $stmt->execute();
    }

    public function query(): QuerySyntax
    {
        // TODO: Implement query() method.
    }

    public function update($entity)
    {
        // TODO: Implement update() method.
    }

}