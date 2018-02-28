<?php
namespace lore\persistence;

use lore\Lore;
use lore\ModuleException;

require_once __DIR__ . "/../../../utils/ReflectionManager.php";


class RelationalQuery extends Query
{
    /**
     * @var RelationalRepository
     */
    private $repository;

    /**
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * Instantiate the RelationalQuery
     * @param EntityMetadata $metadata
     * @param RelationalRepository $repository
     */
    function __construct(EntityMetadata $metadata, RelationalRepository $repository)
    {
        parent::__construct($metadata);
        $this->reflectionClass = new \ReflectionClass($metadata->getEntityClassName());
        $this->repository = $repository;
    }

    public function one()
    {
        $stmt = $this->createAndTriggerQuery();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if($result){
            return $this->loadEntity($result);
        }else{
            return false;
        }
    }

    public function all() : array
    {
        $stmt = $this->createAndTriggerQuery();
        $resultAll = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if($resultAll){
            $entities = [];
            foreach ($resultAll as $result){
                $entities[] = $this->loadEntity($result);
            }

            return $entities;
        }else{
            return [];
        }
    }

    /**
     * @throws \Exception
     * @return \PDOStatement
     */
    protected function createAndTriggerQuery() : \PDOStatement{
        //Create the query
        $sql = $this->repository->getTranslator()->query($this);

        //Create the stmt
        $stmt = $this->repository->getPdo()->prepare($sql);

        try{
            $stmt->execute();
        }catch (\Exception $e){
            if(Lore::app()->getContext()->onDevelopment()){
                throw new PersistenceException("Error while trying to execute $sql\n" . $e->getMessage());
            }else{
                throw $e;
            }
        }

        return $stmt;
    }

    /**
     * @return Entity
     */
    protected function instantiateEntity(){
        return $this->reflectionClass->newInstance();
    }

    /**
     * @param array $args
     * @return Entity $entity
     */
    protected function loadEntity($args){
        $entity = $this->instantiateEntity();

        //Use the object loader module to load the data returned in sql into the entity
        if(Lore::app()->isObjectLoaderEnabled()){
            Lore::app()->getObjectLoader()->load($entity, $args);
        }else{
            throw new ModuleException("To use RelationalQuery::query functions you must use an ObjectLoader module");
        }

        return $entity;
    }

}