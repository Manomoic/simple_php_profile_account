<?php
session_start();
require_once('init.php');
# Get the session of the user if one exists.
if ( is_logged_in() || isset($_SESSION['email']))
    echo '<script> window.location = "../index.php"; </script>';

if ( isset($_POST['email'], $_POST['password']) ) {

    $email    = escape_value($connection, filter_input(INPUT_POST, 'email'));
    $password = escape_value($connection, filter_input(INPUT_POST, 'password'));
    
    if ( empty($email)) {
        echo '<div class ="alert alert-warning">Email Field Cannot Be Empty.</div>';
        exit();
        
    } elseif ( !filter_var($email, FILTER_VALIDATE_EMAIL ) ) {
        echo '<div class ="alert alert-warning"> Invalid Email Format, Please Try Again. </div>';
        exit();
        
    } elseif ( empty($password) ) {
        echo '<div class ="alert alert-danger">Password Field Cannot Be Empty. </div>';
        exit();
        
    } elseif (strlen($password) < 4) {
        echo '<div class ="alert alert-danger">Password Should Contain More Than 4 Characters. </div>';
        exit();
        
    } else {
        
        $login_user = user_login( $connection, $email, $password );
        
        if ( $login_user )
            $_SESSION['email'] = $email;
        
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In</title>
    <link rel="stylesheet" href="../css/bootstrap.css" type="text/css" />
</head>
<body>
    <div class="container-fluid">
       <!--Navigation Bar-->
       <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
           <a href="../index.php" class="navbar-brand">Mano</a>
           
           <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
           </button>
            
           <div class="collapse navbar-collapse" id="navbarNav">
               <ul class="navbar-nav ml-auto">
                   <li class="nav-item active">
                       <a href="signin.php" class="btn btn-outline-secondary text-white ">Sign In</a>
                   </li>
               </ul>
           </div>
       </nav>
       <!--/Navigation Bar-->
       
        <!--jumbotron-->
        <section class="jumbotron p-4 pmd-5 text-muted rounded bg-light">
            <div class="col-md-6 px-0">
                <h5 class="font-italic text-left total-users">
                    
                </h5>
            </div>
        </section>
        <!--/jumbotron-->
    </div>
    
    <div class="container">
        <!-- Card: Sign in to the system -->
        <div class="card mb-3 mx-auto" style="width: 30rem;">
            
            <div class="card-body">
                <form onsubmit="return false;" class="text-muted">
                   <div id="message"></div>
                    <div class="form-group">
                        <input type="email" name="email" id="email" class="form-control email" placeholder="Email Address" />
                    </div>
                    
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" placeholder="Password" name="password">
                    </div>
                </form>
            </div>
            
            <div class="card-footer w-100 text-muted border-0">
                <!-- Control buttons: Close and Create account -->
                <div class="form-group">
                    <input type="button" class="btn btn-outline-secondary float-right" id="btn_login_account" value="Login">
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        
        $('#btn_login_account').click(function() {
            let email               = $('#email').val();
            let password            = $('#password').val();
            let message             = $('#message');
            
            if ( $.trim(email) == '' ) {
                message.html('<div class ="alert alert-warning">Email Field Cannot Be Empty.</div>');
                console.log('Email Field Cannot Be Empty.');
            }
            else if ( $.trim(password) == '' ) {
                message.html('<div class ="alert alert-warning">Password Field Cannot Be Empty.</div>');
                console.log('Password Field Cannot Be Empty.');
            }
            else {
                
                $.ajax({
                    URL: "login.php",
                    type: "POST",
                    data: {email:email, password:password},
                    beforeSend: function(data) {
                        $('#btn_login_account').attr('disabled', 'disabled');
                    },
                    
                    success: function(data) {
                        $('#btn_login_account').attr('disabled', false);
                        
                        message.html( data );
                        console.log( data );
                    }
                });
            }
        });
    });
    </script>
</body>
</html>