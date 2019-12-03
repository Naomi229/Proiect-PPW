<?php 
session_start(); //deschidem o sesiune pentru a vedea utilizatorii utentificati

//initializam variabilele pe care le vom folosi
$username = "";
$email = "";
$errors = array();

//ne conectam la baza de date
$db =mysqli_connect('localhost', 'root', '', 'registration');

//urmeaza partea efectiva de inregistare a utilizatorului
if(isset($_POST['reg_user'])){

    //preluam toate datele introduse de utilizator in formularul de inregistrare

    $username = mysqli_real_escape_string($db, $_POST['username']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);

//verificam daca utilizatorul a lasat campuri necompletate in formular si returnam eroare

        if(empty($username)) { array_push($errors, "Username is required"); }
        if(empty($email)) { array_push($errors, "Email is required"); }
        if(empty($password_1)) { array_push($errors, "Password is required"); }
        if(password_1 != $password_2) {
            array_push($errors, "The two passwords do not match" );
        }


//verificam daca nu cumva utilizatorul s-a mai autentificat in trecut 

$user_check_query ="SELECT * FROM users WHERE username = '$username' OR email = '$email' LIMIT 1";
$result = mysqli_query($db, $user_check_query);
$user = mysqli_fetch_assoc($result);

//daca utilizatorul exista deja in baza noastra de date

if($user){

    if($user['username']===$username){
        array_push($errors,"username already exists");
        }
    if($user['email']===$email){
        array_push($errors,"Email already exists");
        }
}

//inregistram utilizatorul daca formularul e completat corect si utilizatorul nu exista in baza noastra de date

if(count($errors) == 0){
    $password = md5($password_1);

    $query = "INSERT INTO users(username, email, password)
            VALUES('$username', '$email', '$password')";
    mysqli_query($db, $query);
    $_SESSION['username'] = $username;
    $_SESSION['success'] = "You are now logged in!";
    header('location : index.php');
    }
}

//Logare utilizator

if(isset($_POST['login_user'])){
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if(empty($username)){
        array_push($errors, "Username is required");
    }

    if(empty($password)){
        array_push($errors,"Password is required");
    }

    if(count($errors) == 0){
        $password = md5($password);
        $query = "SELECT * FROM users WHERE username='$username' AND password='$password' ";
        $result=mysqli_query($db, $query);
        if(mysqli_num_rows($result) == 1){
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are logged in!";
            header ('location : index.php');
        }else{
            array_push($errors, "Wrong username/password combination");
        }
     }
}

?>

