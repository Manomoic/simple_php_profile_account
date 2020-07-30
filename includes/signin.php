<?php
session_start();
require_once('init.php');
if ( is_logged_in() || isset($_SESSION['email']) )
    echo '<script> window.location = "../index.php"; </script>';

if (isset($_POST['email'], $_POST['password'], $_POST['confirm_password'])) {

    $email              = escape_value($connection, filter_input(INPUT_POST, 'email'));
    $password           = escape_value($connection, filter_input(INPUT_POST, 'password'));
    $confirm_password   = escape_value($connection, filter_input(INPUT_POST, 'confirm_password'));
    
    if ( empty($email)) {
        echo '<div class ="alert alert-warning">Email Field Cannot Be Empty.</div>';
        exit();
        
    } elseif ( !filter_var($email, FILTER_VALIDATE_EMAIL ) ) {
        echo '<div class ="alert alert-warning"> Invalid Email Format, Please Try Again. </div>';
        exit();
        
    } elseif ( empty($password) ) {
        echo '<div class ="alert alert-danger">Password Field Cannot Be Empty..</div>';
        exit();
        
    } elseif (strlen($password) < 4) {
        echo '<div class ="alert alert-danger">Password Should Contain More Than 4 Characters. </div>';
        exit();
        
    }
    elseif ( empty($confirm_password) && strlen($confirm_password) < 3 ) {
        echo '<div class ="alert alert-danger">Confirm Password Field Cannot Be Empty And Should Not Contain Less Than 3 Chars.</div>';
        exit();
        
    } elseif ( strcmp($password, $confirm_password) !== 0 ) {
        echo '<div class ="alert alert-danger"> Passwords does not macth.</div>';
        exit();
        
    } else {
        
        $insert = sql_insert_records($connection, $email, $password);
        
        if ( $insert ) {
            $_SESSION['email'] = $email;
            echo '<script> window.location = "../index.php" <script>';
            exit();
        }
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign In</title>
    <link rel="stylesheet" href="../css/bootstrap.css" type="text/css" />
</head>
<body>
   <!--jumbotron-->
   <div class="container-fluid">
       <section class="jumbotron p-4 pmd-5 text-muted rounded bg-light">
           <div class="col-md-6 px-0">
               <h5 class="font-italic text-left total-users"></h5>
           </div>
       </section>
   </div>
   <!--/jumbotron-->
    
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
                    
                    <div class="form-group">
                        <input type="password" class="form-control" id="confirm_password" placeholder="Re-type Password" name="confirm_password">
                    </div>
                </form>
            </div>
            
            <div class="card-footer w-100 text-muted border-0">
                <!-- Control buttons: Close and Create account -->
                <div class="form-group">
                    <input type="button" class="btn btn-outline-secondary float-right" id="btn_create_account" value="Create Account.">
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/bootstrap.js"></script>
    <script type="text/javascript">
    $(document).ready(function(){
        
        $('#btn_create_account').click(function() {
            let email               = $.trim( $('#email').val() );
            let password            = $.trim( $('#password').val() );
            let confirm_password    = $.trim( $('#confirm_password').val() );
            let message             = $('#message');
            
            if ( email == '' ) {
                message.html('<div class ="alert alert-warning">Email Field Cannot Be Empty.</div>');
                console.log('Email Field Cannot Be Empty.');
            }
            else if ( password == '' ) {
                message.html('<div class ="alert alert-warning">Password Field Cannot Be Empty.</div>');
                console.log('Password Field Cannot Be Empty.');
            }
            else if ( confirm_password == '' ) {
                message.html('<div class ="alert alert-warning">Confirm Password Field Cannot Be Empty.</div>');
                console.log('Confirm Password Field Cannot Be Empty.');
            }
            else if ( password != confirm_password ) {
                message.html('<div class ="alert alert-danger"> Passwords does not macth.</div>');
                console.log('Passwords does not macth.');
            }
            else {
                
                $.ajax({
                    URL: "signin.php",
                    type: "POST",
                    data: {email:email, password:password, confirm_password:confirm_password},
                    beforeSend: function(data) {
                        message.text('Processing ...');
                        $('#btn_save').attr('disabled', 'disabled');
                    },
                    
                    success: function(data) {
                        $('#btn_save').attr('disabled', false);
                        message.html('');
                        
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