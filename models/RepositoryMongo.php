<?php

namespace models;

use MongoDB\Collection;
use MongoDB\InsertManyResult;
use MongoDB\InsertOneResult;

/**
 * Class RepositoryMongo
 * @package models
 * @property Collection $_collection
 */
class RepositoryMongo extends Repository
{
    private $_collection;
    private $_props = [];

    public function __construct()
    {
        parent::__construct();
        $this->_collection = MongoDb::$db->{$this->tableName};
    }

/*
    public function prepare($entities = [])
    {
        $num = count($entities);

        for ($i = 0; $i < $num; $i++) {

            $properties = $this->getProperties($entities[$i]);

            if (isset($properties['id'])) {

                $properties['_id'] = $properties['id'];

                unset($properties['id']);

            }

            $this->_props[] = $properties;
        }

        if (count($this->_props) == 1) {
            $this->_props = $this->_props[0];
        }

        return $this;
    }
*/

    public function findOne($data)
    {
        $props = $this->getProperties($data);

        $res = $this->_collection->findOne($props);

        if (isset($res)) {

            $this->replaceId($res);

            $entity = $this->fillEntity($res);

            return $entity;
        }
    }

    public function findAll($data,$options = [])
    {
        $props = $this->getProperties($data);

        $cursor = $this->_collection->find($props, $options);

        $entities = [];

        foreach ($cursor as $document) {
            if (isset($document)) {

                $this->replaceId($document);

                $entities[] = $this->fillEntity($document);
            }
        }

        return $entities;
    }

    public function create($entity)
    {
        $props = $this->getProperties($entity);

        $entity = $this->createOne($props);

        return $entity;
    }

    private function createOne($props)
    {
        $res = $this->_collection->insertOne($props);

        $props['id'] = $this->getIdFromMongoEntity($res);

        $entity = $this->fillEntity($props);

        return $entity;
    }
/*
    private function createMany()
    {
        $res = $this->_collection->insertMany($this->_props);
        $entities = [];

        foreach ($this->_props as $key=>$doc){
            $doc['id'] = $this->getIdFromMongoEntity($res, $key);

            $entity = $this->fillEntity($doc);

            $entities[] = $entity;
        }

        var_dump($entities); die;
    }
*/

    public function delete($entity)
    {
        $props = $this->getProperties($entity);

        $res = $this->_collection->deleteOne($props);

        var_dump($res->getDeletedCount());
    }

    public function deleteMany($entity)
    {
        $props = $this->getProperties($entity);

        $res = $this->_collection->deleteMany($props);

        var_dump($res->getDeletedCount());
    }

    public function aggregate($pipeline, $keys)
    {
        $cursor = $this->_collection->aggregate($pipeline);

        foreach ($cursor as $data) {
            printf("%s has %d zip codes\n", $data[$keys[0]], $data[$keys[1]]);
        }

    }

    public function updateMany(Entity $entity, $to)
    {
        $updateResult = $this->_collection->updateMany(
            $entity,
            ['$set' => $to]
        );
    }


    //update all document but preserve id
    public function replaceOne()
    {
        list($from, $to) = $this->_props;

        if (!isset($from, $to)) return false;

        $updateResult = $this->_collection->replaceOne(
            $from,
            $to,
            ['upsert' => true]
        );

        var_dump($updateResult->getModifiedCount());
    }


    private function replaceId(&$res=[])
    {
        $id = (array)$res['_id'];

        $res['id'] = $id['oid'];

        unset($res['_id']);

        return $res;
    }

    private function getIdFromMongoEntity($res, $key=null)
    {
        if($res instanceof InsertOneResult) {
            $id = (array)$res->getInsertedId();
        }elseif($res instanceof InsertManyResult){
            $id = (array)$res->getInsertedIds()[$key];
        }

        return $id['oid'];
    }


}
