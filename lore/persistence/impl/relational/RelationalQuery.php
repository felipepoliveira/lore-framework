<?php
namespace lore\persistence;

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

    public function all()
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
     * @return \PDOStatement
     */
    protected function createAndTriggerQuery() : \PDOStatement{
        //Create the query
        $sql = $this->repository->getTranslator()->query($this);

        //Create the stmt
        $stmt = $this->repository->getPdo()->prepare($sql);
        $stmt->execute();

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
        foreach ($args as $fieldName => $propValue) {

            $this->getMetadata()->setPropertyValue(
                $this->getMetadata()->findFieldByName($fieldName)->getPropertyName(),
                $propValue,
                $entity
            );
        }

        return $entity;
    }

}