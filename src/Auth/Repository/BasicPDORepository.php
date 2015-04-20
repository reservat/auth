<?php

namespace Reservat\Auth\Repository;

use Reservat\Core\Repository\PDORepository;

abstract class BasicPDORepository extends PDORepository
{

    public function getByAPIKey($key)
    {
        $data = $this->query(array('api_key' => $key), 1);

        if ($data->execute(array($key))) {
            $this->records[] = $data->fetch(\PDO::FETCH_ASSOC);
        }

        return $this;
    }

    public function getByAuthIdentifiers($username)
    {
        $checkArray = [];
        foreach ($this->identifiers() as $identifier) {
            $checkArray[$identifier] = $username;
        }

        $data = $this->orQuery($checkArray, 1);
        $this->records = [];

        if ($data->execute(array_values($checkArray)) && $results = $data->fetch(\PDO::FETCH_ASSOC)) {
            $this->records[] = $results;
        }

        return $this;
    }

    private function orQuery(array $data, $limit)
    {
        $query = $this->selectOrQuery($data).' LIMIT '.intval($limit);
        $db = $this->db->prepare($query);

        return $db;
    }

    private function selectOrQuery(array $data)
    {
        $query = 'SELECT * FROM '.$this->table();

        if (!empty($data)) {
            $query .= ' WHERE ';
            $counter = 0;
            foreach ($data as $column => $value) {
                $query .= ($counter ? ' OR ' : '') . $column.' = ?';
                $counter++;
            }
        }

        return $query;
    }
}
