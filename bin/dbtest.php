#!/usr/bin/env php
<?php
/**
 * Functional tests for Zend\Db of ZF2
 * 
 * @author Enrico Zimuel (enrico@zend.com)
 */
use Zf2DbTest\Setup;

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
	echo "Error: you need to execute composer\n";
	exit(1);
}

include __DIR__ . '/../vendor/autoload.php';

if (!file_exists(__DIR__ . '/../config/config.php')) {
	echo "Error: the configuration file (config/config.php) doesn't exist\n";
	echo "Copy config/config.php.dist in config/config.php and edit the file with your DB credentials\n";
	exit(1);
}

$dbConfig = include __DIR__ . '/../config/config.php';

if (!isset($argv[1])) {
	echo "Usage: php test.php <init|test|clean>\n";
	echo "where 'init' to setup the database, 'test' to execut the tests and 'clean' to drop the database\n";
	exit(1);
}

$setup = new Setup($dbConfig);

switch ($argv[1]) {
	case 'init':
		if (!$setup->init()) {
			printf ("The table %s already exists in the database %s\n", Setup::DBTESTNAME, $dbConfig[$argv[1]]['database']);
			exit(1);
		}
		break;
	case 'clean':
        if (!$setup->clean()) {
            printf ("The table %s doesn't exist\n", Setup::DBTESTNAME);
            exit(1);
        }
		break;
	case 'test':
		$setup->test($argv[1]);
		break;
}
echo "done\n";
