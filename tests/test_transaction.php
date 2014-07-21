<?php
$test = 'COMMIT';

$connection = $this->adapter->getDriver()->getConnection();
$connection->beginTransaction();

$insert = $this->sql->insert(self::DBTESTNAME);
$newData = array(
    'id'     => '111',
    'name'   => 'Enrico',
    'weight' => '120'
);
$insert->values($newData);
$stmt   = $this->sql->prepareStatementForSqlObject($insert);
$result = $stmt->execute();

$connection->commit();
echo $this->testMe($result->valid(), $test);

// check for the new record
$select  = $this->sql->select()->from(self::DBTESTNAME)->where(array('id' => 111));
$stmt2   = $this->sql->prepareStatementForSqlObject($select);
$result2 = $stmt2->execute();
$row     = $result2->current();
echo $this->testMe(($row['id'] == 111 && $result2->valid()), $test);

$delete = $this->sql->delete()->from(self::DBTESTNAME)->where(array('id' => 111));
$stmt3  = $this->sql->prepareStatementForSqlObject($delete);
$result3 = $stmt3->execute();
echo $this->testMe($result3->valid(), $test);

$test = 'COMMIT + ROLLBACK';

try {
    $connection = $this->adapter->getDriver()->getConnection();
    $connection->beginTransaction();

    $insert = $this->sql->insert(self::DBTESTNAME);
    $newData = array(
        'id'     => '112',
        'name'   => 'Enrico',
        'weight' => '120'
    );
    $insert->values($newData);
    $stmt   = $this->sql->prepareStatementForSqlObject($insert);
    $result = $stmt->execute();

    // select with wrong table that produces an Exception
    $select  = $this->sql->select()->from('foo');
    $stmt2   = $this->sql->prepareStatementForSqlObject($select);
    $result2 = $stmt2->execute();

    $connection->commit();
    echo $this->testMe(false, $test);
} catch (\Exception $e) {
    
    $connection->rollback();

    echo $this->testMe($result->valid(), $test);

    // check that the record has not been created
    $select2 = $this->sql->select()->from(self::DBTESTNAME)->where(array('id' => 112));
    $stmt3   = $this->sql->prepareStatementForSqlObject($select2);
    $result3 = $stmt3->execute();
    $row     = $result3->current();
    
    echo $this->testMe(empty($row), $test);
}
