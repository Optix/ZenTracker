<?php

class frontendConfiguration extends sfApplicationConfiguration
{
  public function configure()
  {
  }

  // Selecting the best cache driver, the fastest to the lowest (memcache, apc, sqlite, array(nocache))
  public function configureDoctrine(Doctrine_Manager $manager) {
    
    // Memcache
    if (class_exists('Memcache')) {
      $cacheDriver = new Doctrine_Cache_Memcache(array(
          'servers' => array(
            'host' => 'localhost',
            'port' => 11211,
            'persistent' => true
          ),
          'compression' => true
        )
      );
    }

    // APC
    elseif (function_exists('apc_store'))
      $cacheDriver = new Doctrine_Cache_Apc();

  	// SQLite
  	elseif (function_exists('sqlite_query')) {
      // Open file
  		$cacheConn = Doctrine_Manager::connection(new PDO("sqlite:%sf_root_dir%/cache/cache.db"));
      // Loading it to driver
  		$cacheDriver = new Doctrine_Cache_Db(array('connection' => $cacheConn, 'TABLEName' => 'cache'));
      // If cache table doesn't exists, create it
  		$cacheConn->exec('CREATE TABLE IF NOT EXISTS cache (id VARCHAR(255), data LONGBLOB, expire DATETIME, PRIMARY KEY(id))');
  	}

    // No cache (array)
    else 
      $cacheDriver = new Doctrine_Cache_Array();

    // Injecting cache driver to Doctrine
    $manager = Doctrine_Manager::getInstance();
    $manager->setAttribute(Doctrine::ATTR_QUERY_CACHE, $cacheDriver);
    $manager->setAttribute(Doctrine::ATTR_RESULT_CACHE, $cacheDriver);
  }
}
