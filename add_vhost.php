#!/usr/bin/php5
<?

   #------------------------------------------------------------------#
   #  database configuration section.  Replace the values of the      #
   #  variables below to reflect your environment		      #
   #------------------------------------------------------------------#

$database = array(
	'host' => "",
	'user' => "",
	'pwd' => ""
	);

# Assigns command line arguments.  This is temporary.
#	TODO: Pull in as post
$username = $argv[1];
$password = $argv[2];
$dbusername = $argv[3];
$email = $argv[4];

$home_dir = '/home/vhost/' . $username;
$pass = crypt($password);

$user = array(
	'username' => $username,
	'password' => $password,
	'email' => $email,
	'dbusername' => $dbusername,
	'home_dir' => $home_dir,
	'pass' => $pass,
	'plainpass' => $password
	);

# sets the mail params

//$response = array('DATA' => array(),'ERROR' => array());
$response = array();

#Begin creating the user
$response = createUser($user,$response);
checkError($response);




//Adds a blank error array to the response array
$response['ERROR']="";

//Echo the response array as json-encoded string
echo json_encode($response);



//Begin functions

function checkError($response) {
#Checks to see if there's any value in the error array.  If there is, it will terminate the script and rollback the changes made
	if (isset($response['ERROR'])) {
		switch($response['ERROR']['CODE']) {
			case 1:
				//deleteUser();
				break;
		}
		$response['DATA'] = "";
		echo json_encode($response);
		exit();
	}
}


//assignOwner($user);
//createConfig($user);
//createDbUser($database,$user);
//createZone($user);
//sendEmail($user);

function createUser($user,$response) {
	# uses system to create the user with hashed password.
	$createUser = "useradd -d " . $user['home_dir'] . " -m -k /etc/vhost_skel -p " . $user['pass'] . " " . $user['username'] . " -g vhost";
	# Stores the message returned by system to a variable for later user
	$message = system(escapeshellcmd($createUser),$retval);
	echo $message . "\n" . $retval;

	# Checks to ensure the command returned successfully
	if ($retval == 0) {
		$response['DATA']['FTP_USERNAME'] = $user['username'];
		return $response;
	} else {
		$response['ERROR']['MESSAGE'] = $message;
		$response['ERROR']['DEBUG'] = $createUser;
		$response['ERROR']['CODE'] = 1;
		return $response;
	}
}

function assignOwner($user) {
	# uses system to assign jail permissions per openssh requirements
	system("chown root:root " . $user['home_dir'],$retval);
	if ($retval == 0) {
		echo "Successfully changed jail permissions\n";
	}
}

function createConfig($user) {
	# copies the apache config file from template, replaces values, enables site and reloads apache
	system("mkdir /var/log/vhosts/" . $user['username'],$retval);
	if ($retval == 0) {
		echo "Successfully created log directory\n";
	}
	$template = "conf/apache_template";
	$config_file = "/etc/apache2/sites-available/" . $user['username'];
	$replace = array('DOMAIN' => $user['username'],'EMAIL' => $user['email']);
	
	$input = file_get_contents($template) or die("Could not open $template");
	foreach ($replace as $key => $value) {
		$input = str_replace($key, $value, $input);
	}
	file_put_contents($config_file,$input) or die("Could not write to $config_file");
	
	system("a2ensite " . $user['username']);
	system("service apache2 reload");
}

function createDbUser($database,$user) {
	#creates a database user.  This will be changed to create the db name and user as account number, as the domain name is typically longer than the 16char max
	$dbh = mysql_connect($database['host'],$database['user'],$database['pwd']);
	
	$query = "CREATE DATABASE " . $user['dbusername'];
	if(mysql_query($query,$dbh)) {
		echo "Database user " . $user['dbusername'] . " created\n";
    } else {
		echo "Database not created: " . mysql_error() . "\n";
	}

	$query = "GRANT ALL on " . $user['dbusername']. ".* TO '" . $user['dbusername'] ."'@'localhost' IDENTIFIED BY '" . $user['pass'] . "'";
	if(mysql_query($query,$dbh)) {
        echo "User " . $user['dbusername'] . " assigned to " . $user['dbusername'] .".\n";
    } else {
        echo "User " . $user['dbusername'] . " not assigned: " . mysql_error() . "\n";
    }
}

function createZone($user) {
	require_once('linode/class.linode.php');
	
	# This uses the Linode API to create the new zone in DNS.
	$linode = new linode();
	echo $linode->add_domain($user['username'],$user['email']);
}

function sendEmail($user) {
	require_once 'Mail.php';
	
	# sets message parameters
	$headers = array (
		'From' => "",
		'To' => $user['email'],
		'Subject' => ""
	);

	$smtp_params['host']="";
	$smtp_params['port']="";
	$smtp_params['auth']=true;
	$smtp_params['username']="";
	$smtp_params['password']="";
	
	$smtp = Mail::factory('smtp',$smtp_params);
	
	$body = "Please find your account information below for ". $user['username'] ."
	
		FTP ACCESS:
		Host: . " . $user['username'] . "
		Username: " . $user['username'] . "
		Password: " . $user['plainpass'] . "
		
		
		DATABASE ACCESS:
		Host: localhost
		Username: " . $user['dbusername'] . "
		Password: " . $user['plainpass'] . "
		
		
		We hope you enjoy your services.";
		
	echo "sending email\n";
	$mail = $smtp->send($user['email'], $headers, $body);
	
	if (PEAR::isError($mail)) {
		echo $mail->getMessage();
	} else {
		echo "Welcome message sent\n";
	}
}
?>
