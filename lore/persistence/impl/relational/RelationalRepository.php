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
     * @var ISqlTranslator
     */
    private $translator;

    function __construct($name, $data)
    {
        parent::__construct($name, $data);
        $this->loadPdo();
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
                $this->translator = new MySqlTranslator();
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
        $sql = $this->translator->insert($entity);
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