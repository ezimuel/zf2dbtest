<?php
$test   = 'LIMIT';
$select = $this->sql->select()->from(self::DBTESTNAME)->limit(10);
$stmt   = $this->sql->prepareStatementForSqlObject($select);
$result = $stmt->execute();

$i       = 1;
$success = true;
foreach ($result as $row) {
    $success &= ($row['id'] == $i++);
}
echo $this->testMe($success, $test);

$test   = 'LIMIT + WHERE';
$select = $this->sql->select()->from(self::DBTESTNAME)->where(array('id' => 1))->limit(10);
$stmt   = $this->sql->prepareStatementForSqlObject($select);
$result = $stmt->execute();
$row    = $result->current();

echo $this->testMe($row['id'] == 1, $test);

$test   = 'LIMIT + OFFSET';
$select = $this->sql->select()->from(self::DBTESTNAME)->limit(10)->offset(10);
$stmt   = $this->sql->prepareStatementForSqlObject($select);
$result = $stmt->execute();

$i       = 11;
$success = true;
foreach ($result as $row) {
    $success &= ($row['id'] == $i++);
}
echo $this->testMe($success, $test);

$test   = 'LIMIT + OFFSET + WHERE';
$where  = new Zend\Db\Sql\Where();
$where->greaterThan('id', 10)->AND->lessThan('id', 31);
$select = $this->sql->select()->from(self::DBTESTNAME)->where($where)->limit(10)->offset(10);
$stmt   = $this->sql->prepareStatementForSqlObject($select);
$result = $stmt->execute();

$i       = 21;
$success = true;
foreach ($result as $row) {
    $success &= ($row['id'] == $i++);
}
echo $this->testMe($success, $test);

