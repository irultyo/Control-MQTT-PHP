<?php
  if ($_POST['process'] == 1) {    
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    if (empty($first_name) && empty($last_name)){
      echo "Howdy, stranger";
    }else{
      echo "Hello there, ".$first_name." ".$last_name;
    }
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Form validation example</title>
  <style>
    input{display:block; margin-bottom:10px;}
  </style>
</head>

<body>
  <h1>Form Validation Example</h1>
  <h2>Please enter your name</h2>
  
  <form action="/SmartFarm/test.php" method="post">
    <label for="first_name">First name:</label>
    <input name="first_name" id="first_name" type="text" />
    
    <label for="last_name">Last name:</label>
    <input name="last_name" id="last_name" type="text" />
    
    <input type="hidden" name="process" value="1" />
    <input type="submit" value="Submit" />
  </form>
</body>
</html>