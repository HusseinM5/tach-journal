<?php
//session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('192.168.174.128', 'root', 'secret', 'web');

// REGISTER USER
if (isset($_POST['reg_password'])) {
  // receive all input values from the form

  //$password_1 = bin2hex(mysqli_real_escape_string($db, $_POST['password_1']));
  function strToBin3($input)
   {
    if (!is_string($input))
     return false;
     $input = unpack('H*', $input);
     $chunks = str_split($input[1], 2);
     $ret = '';
     foreach ($chunks as $chunk)
     {
       $temp = base_convert($chunk, 16, 2);
       $ret .= str_repeat("0", 8 - strlen($temp)) . $temp;
     }
      return $ret;
   }
  $password_1 = strToBin3(mysqli_real_escape_string($db, $_POST['password_1']));
  //echo $password_1;

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($password_1)) { array_push($errors, "Password is required"); }
 

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $pass_check_query = "SELECT * FROM passwords_table WHERE password=$password_1 LIMIT 1";
  $result = mysqli_query($db, $pass_check_query);
  $pass = mysqli_fetch_assoc($result);
  
  if ($pass) { // if user exists
    if ($pass['password'] === $password_1) {
      array_push($errors, "Username already exists");
    }
  }

  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
//      $password = md5($password_1);//encrypt the password before saving in the database
    $password = $password_1;
        $query = "INSERT INTO passwords_table (password) 
                          VALUES('$password')";
        mysqli_query($db, $query);
        header('location: index.php');
  }
}

// https://codewithawa.com/posts/complete-user-registration-system-using-php-and-mysql-database
// https://thenewstack.io/deploy-mysql-and-phpmyadmin-with-docker/
// https://www.howtogeek.com/devops/how-to-run-phpmyadmin-in-a-docker-container/
// https://linuxhint.com/mysql_server_docker/
// https://www.javatpoint.com/docker-php-example

// SET MYSQL DATA TYPE TO TEXT
?>
