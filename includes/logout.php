<?php
session_start();
require_once('init.php');

if ( isset($_SESSION['email']) || isset($_SESSION['id']) != '') {
    $user_session_id = $_SESSION['id'];
    
    $sql_query = mysqli_query($connection, "SELECT * FROM tbl_users WHERE id = ". $user_session_id);
    
    if ( !$sql_query )
        trigger_error( mysqli_error($connection), E_USER_NOTICE );
    
    while( $fetch = mysqli_fetch_array($sql_query, MYSQLI_BOTH) ) {
        $update = mysqli_query($connection, "UPDATE tbl_users SET session = 0, status = 'Logged Out' WHERE id = " . $user_session_id);
        
        if ( !$update ):
            trigger_error( mysqli_error($connection), E_USER_NOTICE );
        else:
            user_logout();
            //header('refresh: 1, login.php', true);
        echo '<script> window.location = "login.php"; </script>';

        endif;
    }
}
?>