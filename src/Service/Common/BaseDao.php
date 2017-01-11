<?php

namespace TastPHP\Service\Common;

class BaseDao
{
    protected $connection;
    protected $container = [];
    protected $isMaster = false;

    public function setContainer($container)
    {
        $this->container = $container;
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;
    }

    protected function getConnection($name = 'master')
    {
        if ($this->isMaster) {
            return $this->connection['master'];
        }

        if ($name == 'read') {
            $randKey = array_rand($this->container['dbs.name']);
            return $this->connection[$this->container['dbs.name'][$randKey]];
        }

        $this->isMaster = true;
        if ($name == 'write') {
            return $this->connection['master'];
        }

        return $this->connection['master'];
    }

    protected function createDaoException($message = "", $code = 0)
    {
        return new DaoException($message, $code);
    }

    protected function get($id, $fields, $table)
    {
        $conn = $this->getConnection('read');
        $queryBuilder = $conn->createQueryBuilder();
        $queryBuilder
            ->select($fields)
            ->from($table)
            ->where('id = ?')
            ->setParameter(0, $id);
        return $queryBuilder->execute()->fetch();
    }

    protected function getAll($table)
    {
        $conn = $this->getConnection('read');
        $queryBuilder = $conn->createQueryBuilder();
        $queryBuilder
            ->select('*')
            ->from($table);
        return $queryBuilder->execute()->fetchAll();
    }

    protected function create($table, $data)
    {
        $conn = $this->getConnection('write');
        $affected = $conn->insert($table, $data);
        if ($affected <= 0) {
            throw $this->createDaoException("Insert {$table} error.");
        }
        return $this->get($conn->lastInsertId(), '*', $table);
    }

    protected function update($table, $data, $id)
    {
        $this->getConnection('write')->update($table, $data, ['id' => $id]);
        return $this->get($id, '*', $table);
    }

    protected function delete($table, $id)
    {
        return $this->getConnection('write')->delete($table, ['id' => $id]);
    }

    protected function search(&$queryBuilder, $andWhere, $condition)
    {
        foreach ($andWhere as $key => $value) {
            if (isset($condition[$key])) {
                if ($this->isInCondition($value)) {
                    $marks = array();
                    foreach (array_values($condition[$key]) as $index => $one) {
                        $marks[] = ":{$key}_{$index}";
                        $condition["{$key}_{$index}"] = $one;
                    }
                    $value = str_replace(":{$key}", join(',', $marks), $value);
                    unset($condition[$key]);
                }
                $queryBuilder->andWhere($value);
            }
        }

        foreach ($condition as $key => $value) {
            $queryBuilder->setParameter(":{$key}", $value);
        }
    }

    protected function bindValues($stmt, $conditions, $names)
    {
        foreach ($names as $name) {
            if (!empty($name)) {
                $stmt->bindValue($name, $conditions[$name]);
            }
        }

        $stmt->execute();
        return $stmt;
    }

    private function isInCondition($where)
    {
        $matched = preg_match('/\s+(IN)\s+/', $where, $matches);
        if (empty($matched)) {
            return false;
        } else {
            return true;
        }
    }
}