<?php
date_default_timezone_set('Europe/Paris');
class websocketdaemonTask extends sfBaseTask
{
  protected function configure()
  {
    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = '';
    $this->name             = 'websocket-daemon';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [websocket-daemon|INFO] task does things.
Call it with:

  [php symfony websocket-daemon|INFO]
EOF;
  }
  
  
 /**
	 * The address of the server
	 * @var String
	 */
	private $address;

	/**
	 * The port for the master socket
	 * @var int
	 */
	private $port;

	/**
	 * The master socket
	 * @var Resource
	 */
	private $master;

	/**
	 * The array of sockets (1 socket = 1 client)
	 * @var Array of resource
	 */
	private $sockets;

	/**
	 * The array of connected clients
	 * @var Array of clients
	 */
	private $clients;

	/**
	 * If true, the server will print messages to the terminal
	 * @var Boolean
	 */
	private $verboseMode;
  
  private $shm;

	/**
	 * Server constructor
	 * @param $address The address IP or hostname of the server.
	 * @param $port The port for the master socket (default: 8001)
	 */
  protected function execute($arguments = array(), $options = array()) {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    // Starting server
    $this->Server("0", 8001, true);
  }
	function Server($address = '0', $port = 8001, $verboseMode = false) {
		$this->console("Server starting...");
		$this->address = $address;
		$this->port = $port;
		$this->verboseMode = $verboseMode;

		// socket creation
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

		if (!is_resource($socket))
			$this->console("socket_create() failed: ".
        socket_strerror(socket_last_error()), true);

		if (!socket_bind($socket, $this->address, $this->port))
			$this->console("socket_bind() failed: ".
        socket_strerror(socket_last_error()), true);

		if(!socket_listen($socket, 20))
			$this->console("socket_listen() failed: ".
        socket_strerror(socket_last_error()), true);
		$this->master = $socket;
		$this->sockets = array($socket);
		$this->console("Server started on {$this->address}:{$this->port}");
    
    // Creating a Shared Memory Zone in RAM
    $this->console("Trying to allocate memory");
    $shm_key = ftok(__FILE__, 't');
    $shm_size = 1024*1024; // 1MB
    $this->shm = shm_attach($shm_key, $shm_size);
    
    // Launching...
    $this->run();
	}

	/**
	 * Create a client object with its associated socket
	 * @param $socket
	 */
	private function connect($socket) {
		$this->console("Creating client...");
		$client = new Client(uniqid(), $socket);
		$this->clients[] = $client;
		$this->sockets[] = $socket;
		$this->console("Client #{$client->getId()} is successfully created!");
	}

	/**
	 * Do the handshaking between client and server
	 * @param $client
	 * @param $headers
	 */
	private function handshake($client, $headers) {
		$this->console("Getting client WebSocket version...");
		if(preg_match("/Sec-WebSocket-Version: (.*)\r\n/", $headers, $match))
			$version = $match[1];
		else {
			$this->console("The client doesn't support WebSocket");
			return false;
		}

		$this->console("Client WebSocket version is {$version}, (required: 13)");
		if($version == 13) {
			// Extract header variables
			$this->console("Getting headers...");
			if(preg_match("/GET (.*) HTTP/", $headers, $match))
				$root = $match[1];
			if(preg_match("/Host: (.*)\r\n/", $headers, $match))
				$host = $match[1];
			if(preg_match("/Origin: (.*)\r\n/", $headers, $match))
				$origin = $match[1];
			if(preg_match("/Sec-WebSocket-Key: (.*)\r\n/", $headers, $match))
				$key = $match[1];      

			$this->console("Client headers are:");
			$this->console("\t- Root: ".$root);
			$this->console("\t- Host: ".$host);
			$this->console("\t- Origin: ".$origin);
			$this->console("\t- Sec-WebSocket-Key: ".$key);
      
      // Checking if client is already logged, disconnect others
      $this->console("Looking for cookies...");
      $client->setCookies($this->getCookies($headers));
      if ($uid = $client->getCookies("uid")) {
        $this->console("User ID found. Checking database...");
        $ses = Doctrine::getTable("Users")->find($uid);
        // Passwords are matching ?
        if ($ses->getCookiesHash() == $client->getCookies('pass')) {
          $client->setSession($ses);
          $this->console("User identified : ".$ses->getUsername());
        }
      }
      if (!$client->getSession()) {
        $this->console("User not identifed, disconnecting...");
        $this->disconnect($client);
        return false;
      }

			$this->console("Generating Sec-WebSocket-Accept key...");
			$acceptKey = $key.'258EAFA5-E914-47DA-95CA-C5AB0DC85B11';
			$acceptKey = base64_encode(sha1($acceptKey, true));

			$upgrade = "HTTP/1.1 101 Switching Protocols\r\n".
					   "Upgrade: websocket\r\n".
					   "Connection: Upgrade\r\n".
					   "Sec-WebSocket-Accept: $acceptKey".
					   "\r\n\r\n";

			$this->console("Sending this response to the client #{$client->getId()}:\r\n".$upgrade);
			socket_write($client->getSocket(), $upgrade);
			$client->setHandshake(true);
			$this->console("Handshake is successfully done!");
      
      // Sending current online users
      $this->getUsers();
			return true;
		}
		else {
			$this->console("WebSocket version 13 required 
        (the client supports version {$version})");
			return false;
		}
	}

	/**
	 * Disconnect a client and close the connection
	 * @param $socket
	 */
	private function disconnect($client) {
		$this->console("Disconnecting client #{$client->getId()}");
		$i = array_search($client, $this->clients);
		$j = array_search($client->getSocket(), $this->sockets);

		if($j >= 0) {
			array_splice($this->sockets, $j, 1);
			socket_shutdown($client->getSocket(), 2);
			socket_close($client->getSocket());
			$this->console("Socket closed");
		}

		if($i >= 0)
			array_splice($this->clients, $i, 1);
		$this->console("Client #{$client->getId()} disconnected");
    // Sending current online users
    $this->getUsers();
	}

	/**
	 * Get the client associated with the socket
	 * @param $socket
	 * @return A client object if found, if not false
	 */
	private function getClientBySocket($socket) {
		foreach($this->clients as $client)
			if($client->getSocket() == $socket) {
				$this->console("Client found");
				return $client;
			}
		return false;
	}
  
  /**
   * Converting cookies in headers into array
   * @param type $message
   * @return type array() or boolean if false
   */
  private function getCookies($message) {
    $return = array();
    preg_match("#Cookie: (.*)\r\n#", $message, $match);
    $cookies = explode("; ", $match[1]);
    foreach ($cookies as $cookie) {
      $keyvalue = explode("=", $cookie);
      $return[$keyvalue[0]] = $keyvalue[1];
    }
    return (count($return) > 0)?$return:false;
  }

	/**
	 * Do an action
	 * @param $client
	 * @param $action
	 */
	private function action($client, $action) {
    // Decoding...
    $action = $this->unmask($action);
    
		if($action == "exit" || $action == "quit") {
			$this->console("Killing a child process");
			posix_kill($client->getPid(), SIGTERM);
			$this->console("Process {$client->getPid()} is killed!");
		}
    
    // Everything sent by the client is in JSON format
    $msg = json_decode($action, true);
    $this->console("Incoming message");
    
    // If client has sent a message
    if (isset($msg['shoutbox-add'])) {
      // Save it
      $sh = Doctrine::getTable("Shoutbox")->ecrireShout(
        $msg['shoutbox-add'], $client->getSession()->getId()
      );
      // Retrieving other shouts
      $sht = Doctrine::getTable("Shoutbox")->getShout($sh->getId()-1);
      // Encapsulate
      $newsht = array("action" => "shoutbox", "data" => $sht);
      // Send to everybody... move your body !
      foreach ($this->clients as $clt)
        @shm_put_var($this->shm, $clt->getId(), json_encode($newsht));
    }
    

	}

	/**
	 * Run the server
	 */
	public function run() {
		$this->console("Start running...");
		while(true) {
			$changed_sockets = $this->sockets;
			@socket_select($changed_sockets, $write = NULL, $except = NULL, 1);
			foreach($changed_sockets as $socket) {
				if($socket == $this->master) {
					if(($acceptedSocket = socket_accept($this->master)) < 0) {
						$this->console("Socket error: ".
              socket_strerror(socket_last_error($acceptedSocket)));
					}
					else {
						$this->connect($acceptedSocket);
					}
				}
				else {
					$this->console("Finding the socket that associated to the client...");
					$client = $this->getClientBySocket($socket);
					if($client) {
						$this->console("Receiving data from the client");

						$data=null; 
						while($bytes = @socket_recv($socket, $r_data, 2048, MSG_DONTWAIT)){
							$data.=$r_data;
						}

						if(!$client->getHandshake()) {
							$this->console("Doing the handshake");
							if($this->handshake($client, $data))
								$this->startProcess($client);
						}
						elseif($bytes === 0) {
							$this->disconnect($client);
						}
						else {
							// When received data from client
							$this->action($client, $data);
						}
					}
				}
			}
		}
	}
  
  /**
   * Return current connected users
   * @return JSON online users
   */
  private function getUsers() {
    // We need to create links for each member
    $routing = $this->getRouting();
    $a = array();
    foreach ($this->clients as $user) {
      $a[$user->getSession()->getId()] = array(
        "username" => $user->getSession()->getUsername(),
        "avatar" => "/uploads/avatars/16x16/".$user->getSession()->getAvatar("raw"),
        "url" => $routing->generate('profil', array(
            "id" => $user->getSession()->getId(),
            "username" => $user->getSession()->getUsername())
        )
      );
    }
    $b = array("action" => "usersConnected", "data" => $a);
    $r = json_encode($b, true);
    foreach ($this->clients as $clt)
        @shm_put_var($this->shm, $clt->getId(), $r);
  }

	/**
	 * Start a child process for pushing data
	 * @param object Client $client
	 */
	private function startProcess($client) {
		$this->console("Start a client process");
		// Creating child
    $pid = pcntl_fork();
    // If system can't, stop program :'(
		if($pid == -1)
			exit('could not fork');
    // If PID returned, storing it !
		elseif($pid)
			$client->setPid($pid);
    // Child processing
		else {
			// As long as the client is connected
      while(true) {
				// If client has left, disconnect him and kill thread
        if($client->exists==false){
          break;
        }		
        // If we have something to send to the client
        if (@shm_has_var($this->shm, $client->getId())) {
          // Sending buffer to client
          $this->send($client, @shm_get_var($this->shm, $client->getId()));
          // Purge buffer
          @shm_remove_var($this->shm, $client->getId());
        }
        // Keeping server cold :o) 
        else
          usleep(100000); // 100ms
			}
		}
	}

	/**
	 * Send a text to client
	 * @param $client
	 * @param $text
	 */
	private function send($client, $text) {
		$this->console("Send ".strlen($text)." bytes to client #{$client->getId()}");
		$text = $this->encode($text);
		if(socket_write($client->getSocket(), $text, strlen($text)) === false) {
      $client->exists=false; //flag the client as broken			
			$this->console("Unable to write to client #{$client->getId()}'s socket");
			$this->disconnect($client);
		}
	}

	/**
	 * Encode a text for sending to clients via ws://
	 * @param $text
	 * @param $messageType
	 */
	function encode($message, $messageType='text') {

		switch ($messageType) {
			case 'continuous':
				$b1 = 0;
				break;
			case 'text':
				$b1 = 1;
				break;
			case 'binary':
				$b1 = 2;
				break;
			case 'close':
				$b1 = 8;
				break;
			case 'ping':
				$b1 = 9;
				break;
			case 'pong':
				$b1 = 10;
				break;
		}

			$b1 += 128;


		$length = strlen($message);
		$lengthField = "";

		if ($length < 126) {
			$b2 = $length;
		} elseif ($length <= 65536) {
			$b2 = 126;
			$hexLength = dechex($length);
			//$this->stdout("Hex Length: $hexLength");
			if (strlen($hexLength)%2 == 1) {
				$hexLength = '0' . $hexLength;
			} 

			$n = strlen($hexLength) - 2;

			for ($i = $n; $i >= 0; $i=$i-2) {
				$lengthField = chr(hexdec(substr($hexLength, $i, 2))) . $lengthField;
			}

			while (strlen($lengthField) < 2) {
				$lengthField = chr(0) . $lengthField;
			}

		} else {

			$b2 = 127;
			$hexLength = dechex($length);

			if (strlen($hexLength)%2 == 1) {
				$hexLength = '0' . $hexLength;
			} 

			$n = strlen($hexLength) - 2;

			for ($i = $n; $i >= 0; $i=$i-2) {
				$lengthField = chr(hexdec(substr($hexLength, $i, 2))) . $lengthField;
			}

			while (strlen($lengthField) < 8) {
				$lengthField = chr(0) . $lengthField;
			}
		}

		return chr($b1) . chr($b2) . $lengthField . $message;
	}


	/**
	 * Unmask a received payload
	 * @param $buffer
	 */
	private function unmask($payload) {
		$length = ord($payload[1]) & 127;

		if($length == 126) {
			$masks = substr($payload, 4, 4);
			$data = substr($payload, 8);
		}
		elseif($length == 127) {
			$masks = substr($payload, 10, 4);
			$data = substr($payload, 14);
		}
		else {
			$masks = substr($payload, 2, 4);
			$data = substr($payload, 6);
		}

		$text = '';
		for ($i = 0; $i < strlen($data); ++$i) {
			$text .= $data[$i] ^ $masks[$i%4];
		}
		return $text;
	}

	/**
	 * Print a text to the terminal
	 * @param $text the text to display
	 * @param $exit if true, the process will exit 
	 */
	private function console($text, $exit = false) {
		$text = date('[Y-m-d H:i:s] ').$text."\r\n";
		if($exit)
			die($text);
		if($this->verboseMode)
			echo $text;
	}
}

class Client {
	private $id;
	private $socket;
	private $handshake;
  private $session;
  private $cookies;
	private $pid;
  public $buffer;
  public $exists=true; //to check if client is broken	

	function Client($id, $socket) {
		$this->id = $id;
		$this->socket = $socket;
		$this->handshake = false;
    $this->session = false;
    $this->cookies = false;
		$this->pid = null;
    $this->buffer = false;
	}

	public function getId() {
		return $this->id;
	}

	public function getSocket() {
		return $this->socket;
	}
  
  public function getSession() {
    return $this->session;
  }
  
  public function getCookies($s = null) {
    if ($s != null && isset($this->cookies[$s]))
      return $this->cookies[$s];
    elseif ($s != null && !isset($this->cookies[$s]))
      return false;
    else
      return $this->cookies;
  }

	public function getHandshake() {
		return $this->handshake;
	}

	public function getPid() {
		return $this->pid;
	}
  
  public function getBuffer($check = false) {
    if (!isset($this->buffer[0]))
      return false;
    
    if (!$check)
      unset($this->buffer[0]);
    else
      return true;
    
    return json_encode($this->buffer[0]);
  }

	public function setId($id) {
		$this->id = $id;
	}
  
  public function setSession($s) {
    $this->session = $s;
  }
  
  public function setCookies($c) {
    $this->cookies = $c;
  }

	public function setSocket($socket) {
		$this->socket = $socket;
	}

	public function setHandshake($handshake) {
		$this->handshake = $handshake;
	}

	public function setPid($pid) {
		$this->pid = $pid;
	}
  
  public function setBuffer($text) {
    $this->buffer[0] = $text;
  }
}