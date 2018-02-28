<?php
namespace lore\persistence;

use lore\Configurations;
use lore\util\ReflectionManager;


class RepositoryLoader extends Persistence
{

    /**
     * Store all repositories in persistence configuration file
     * @var array
     */
    protected $repositories;

    protected function loadRepositories()
    {
        $repConfig =  Configurations::load("persistence");

        //Get each configured repository and load it
        foreach ($repConfig as $repName => $repData){
            $repository = ReflectionManager::instanceFromFile($repData["class"], $repData["file"], [$repName, $repData]);
            $this->repositories[$repName] = $repository;
        }
    }

    public function getRepository($repName = null): Repository
    {
        if(!isset($repName)){
            if(count($this->repositories) > 0){
                return reset($this->repositories);
            }else{
                throw new PersistenceException("The system requested a repository but any was configured in persistence file");
            }
        }else if(isset($this->repositories[$repName])){
            return $this->repositories[$repName];
        }else{
            throw new PersistenceException("The repository \"$repName\" was not found in persistence configuration file");
        }
    }


}