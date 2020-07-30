<?php

if (!defined('PASSWORD_BCRYPT')):
    define('PASSWORD_BCRYPT', 1);
    define('PASSWORD_DEFAULT', PASSWORD_BCRYPT);
endif;

function escape_value($con, $value) {
    $escape = mysqli_real_escape_string($con, $value );
    
    if ( $escape )
        $escape = stripcslashes( $value );
    else
        $escape = addslashes( $value );
    
    return htmlspecialchars($escape);
}

function user_login($connection, $email, $password) {
    
    $sql = mysqli_query($connection, "SELECT * FROM tbl_users WHERE email = '". $email."'");
        
    if ( !$sql )
        trigger_error( mysqli_error($connection), E_USER_NOTICE );

    if ( mysqli_num_rows($sql) > 0 ) {

        while( $sql_fetch_data = mysqli_fetch_array($sql, MYSQLI_BOTH)) {

            if ( $email != $sql_fetch_data['email']):
                echo '<div class ="alert alert-warning">Invalid Email Address.</div>';
                exit();

            elseif ( is_password_verified($password) != $sql_fetch_data['password'] ):
                echo '<div class ="alert alert-warning">Incorect Password.</div>';
                exit();

            else:
                $_SESSION['id']     = $sql_fetch_data['id'];
                $_SESSION['email']  = $email;

                $update = mysqli_query($connection, "UPDATE tbl_users SET session = 1, loggedin = NOW(), status = 'Logged In' WHERE id = " . $sql_fetch_data['id']);

                if ($update) {
                    echo '<script> window.location = "../profile.php"; </script>';

                } else {
                    trigger_error( mysqli_error($connection), E_USER_NOTICE );
                    exit();
                }
            endif;
        }
    } else {
        echo '<div class ="alert alert-warning">Invalid Login Credentials.</div>';
        exit();
    }
    
    return true;
}

function is_logged_in() {
    if ( isset($_SESSION['email']) && $_SESSION['email'] == true ) {
        return true;
        
    } elseif ( isset($_SESSION['login_id']) == true ) {
        return true;
    }
}

function user_logout() {
    return session_destroy();
}

function is_password_verified($hash) {
    
    if ( !function_exists('md5')):
        trigger_error("MD5 must be loaded for password_verify to function", E_USER_WARNING);
        return false;
    endif;

    return substr(md5($hash),5);
}

function sql_insert_records($connection, $email, $password) {
    include_once('init.php');
    # Hash, Crypt, Md5 the password for security.
    $password = is_password_verified($password);
    
    /*Check if the email exists
        -------------------------------------------------------*/
        $sql_email_check    = mysqli_query($connection, "SELECT * FROM tbl_users WHERE email = ".$email);
        $sql_email_row      = @mysqli_num_rows( $sql_email_check );
        
        if ( $sql_email_row > 0 ):
            echo $email .' Already Exist in our system.';
            exit();
        endif;
    
    /* Insert Records
        -------------------------------------------------------*/
        $results = mysqli_query($connection, "INSERT INTO tbl_users
        (name,surname,gender,address,email,password,created,updated,loggedin,description,photo,status,session)
        VALUES ('', '', '', '', '".$email."','".$password."', NOW(), '', '', '', '', 'New', '' )");

        $mysql_last_id = mysqli_insert_id($connection);

        if ( !$results ):
            trigger_error( mysqli_error($connection), E_USER_NOTICE );
            exit();
        endif;
        
        $_SESSION['email']  = $email;
        $_SESSION['id']     = $mysql_last_id;

        /* Update the session record if the user has an accout 
        ------------------------------------------------------------------------------------------------*/
        $update_record = mysqli_query($connection, "UPDATE tbl_users SET session = 1, updated = NOW()
        WHERE id = ". $mysql_last_id);

        if ( !$update_record ):
            trigger_error( mysqli_error($connection), E_USER_NOTICE );
            exit();
        endif;
        
    exit();
    
    return true;
}
?>