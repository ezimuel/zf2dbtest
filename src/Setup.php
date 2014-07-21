<?php
namespace Zf2DbTest;

use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Db\Sql\Ddl;
use Zend\Db\Sql\Ddl\Column;
use Zend\Db\Sql\Ddl\Constraint;
use Zend\Db\Sql\Sql;


class Setup
{
	const DBTESTNAME = 'ZF2DB2TEST';
	
	protected $names = array('Foo', 'Bar', 'Baz', 'Foz');
	
	protected $adapter;
	
	protected $sql;
	
	public $num = 100;
	
	public function __construct(array $dbconfig) 
	{
		$this->adapter = new DbAdapter($dbconfig);
		$this->sql     = new Sql($this->adapter);
	}
	
	public function init()
	{
		$table = new Ddl\CreateTable(self::DBTESTNAME);
		$table->addColumn(new Column\Integer('id'));
		$table->addConstraint(new Constraint\PrimaryKey('id'));
		$table->addColumn(new Column\Varchar('name', 255));
		$table->addColumn(new Column\Integer('weight'));
		
		// create the table
		try {
			$this->adapter->query(
				$this->sql->getSqlStringForSqlObject($table),
				DbAdapter::QUERY_MODE_EXECUTE
			);
		} catch (\Exception $e) {
			return false;
		}
		printf("Table %s created.\n", self::DBTESTNAME);
		
		printf("Adding %d records to the table...", $this->num);
		// add values to the table
		$this->fillDatabase();
		
		return true;
	}
	
	public function clean()
	{
		$table = new Ddl\DropTable(self::DBTESTNAME);

        try {
            return $this->adapter->query(
			    $this->sql->getSqlStringForSqlObject($table),
			    DbAdapter::QUERY_MODE_EXECUTE
		    );
        } catch (\Exception $e) {
            return false;
        }
	}
	
	public function test($db)
	{
		foreach (glob(__DIR__ . "/../tests/test*.php") as $filename) {
            $this->filetest = basename($filename);
            printf("TESTING %s\n", $this->filetest);
            try {
                include $filename;
            } catch (\Exception $e) {
                printf("ERROR: %s.\n", $e->getMessage());
                exit(1);
            }
            printf("\n");
		}	
	}
	
    protected function testMe($test, $msg = null)
    {
        if ($test) {
            return sprintf("[%s]: \033[01;32mOK\033[0m\n", $msg);
        } else {
            return sprintf("[%s]: \033[01;31mFAILED\033[0m\n", $msg);
        }
    }
    
    protected function fillDatabase()
	{
		$insert = $this->sql->insert(self::DBTESTNAME);
		for ($i=1; $i <= $this->num; $i++) {
			$insert->values(array(
				'id'     => $i,
			 	'name'   => $this->names[array_rand($this->names)],
				'weight' => rand(1,100)	
			));
			$selectString = $this->sql->getSqlStringForSqlObject($insert);
			$results = $this->adapter->query(
				$selectString, 
				DbAdapter::QUERY_MODE_EXECUTE
			);
		}
	}
	
}
