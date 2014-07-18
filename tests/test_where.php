<?php
$test   = 'WHERE';
$select = $this->sql->select()->from(self::DBTESTNAME)->where(array('id' => 1));
$stmt   = $this->sql->prepareStatementForSqlObject($select);
$result = $stmt->execute();
$row    = $result->current();

echo $this->testMe($row['id'] == 1, $test);

$test   = 'WHERE LIKE';
$where  = new Zend\Db\Sql\Where();
$where->like('name', 'F%');
$select = $this->sql->select()->from(self::DBTESTNAME)->where($where);
$stmt   = $this->sql->prepareStatementForSqlObject($select);
$result = $stmt->execute();
$row    = $result->current();

echo $this->testMe($row['name'][0] === 'F', $test);

$test   = 'WHERE OR';
$where  = new Zend\Db\Sql\Where();
$where->equalTo('id', 1)->OR->equalTo('id', 2);
$select = $this->sql->select()->from(self::DBTESTNAME)->where($where);
$stmt   = $this->sql->prepareStatementForSqlObject($select);
$result = $stmt->execute();
$row1   = $result->current();
$row2   = $result->next();

echo $this->testMe($row1['id'] == 1, $test);
echo $this->testMe($row2['id'] == 2, $test);

$test   = 'WHERE AND';
$where  = new Zend\Db\Sql\Where();
$where->greaterThan('id', 0)->AND->lessThan('id', 3);
$select = $this->sql->select()->from(self::DBTESTNAME)->where($where);
$stmt   = $this->sql->prepareStatementForSqlObject($select);
$result = $stmt->execute();
$row1   = $result->current();
$row2   = $result->next();

echo $this->testMe($row1['id'] == 1, $test);
echo $this->testMe($row2['id'] == 2, $test);
