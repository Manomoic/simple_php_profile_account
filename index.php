<?php
session_start();
//session_destroy();
require_once('includes/init.php');
# Get the session of the user if one exists.
if ( !is_logged_in() || !isset($_SESSION['email']))
    echo '<script> window.location = "includes/login.php"; </script>';

$user_session_id = $_SESSION['id'];

$sql_display_user = mysqli_query($connection, "SELECT * FROM tbl_users WHERE id = ". $user_session_id." LIMIT 1");

if (!$sql_display_user):
    trigger_error( mysqli_error($connection), E_USER_NOTICE );
    exit();
endif;

if ( isset($_POST['name'], $_POST['surname'], $_POST['gender'], $_POST['address'], $_POST['phone']) )
{
    $name     = escape_value($connection, filter_input(INPUT_POST, 'name'));
    $surname  = escape_value($connection, filter_input(INPUT_POST, 'surname'));
    $gender   = escape_value($connection, filter_input(INPUT_POST, 'gender'));
    $address  = escape_value($connection, filter_input(INPUT_POST, 'address'));
    $phone    = escape_value($connection, filter_input(INPUT_POST, 'phone'));
    
    if ($name == '') {
        echo '<div class ="alert alert-warning">Name Field Cannot Be Empty.</div>';
        exit();
        
    } elseif (empty($surname)) {
        echo '<div class ="alert alert-warning">Surname Field Cannot Be Empty.</div>';
        exit();
        
    } elseif (empty($gender)) {
        echo '<div class ="alert alert-warning">Gender Field Cannot Be Empty.</div>';
        exit();
        
    } elseif (empty($address)) {
        echo '<div class ="alert alert-warning">Address Field Cannot Be Empty.</div>';
        exit();
        
    } elseif (empty($phone)) {
        echo '<div class ="alert alert-warning">Phone Number Field Cannot Be Empty.</div>';
        exit();
        
    } elseif (!strlen($phone) > 10) {
        echo '<div class ="alert alert-warning">Phone Number Must Have 10 Digits.</div>';
        exit();
        
    } elseif (!is_numeric($phone)) {
        echo '<div class ="alert alert-warning">Phone Number Must In Number Format.</div>';
        exit();
        
    } else {
        
        if ( $user_session_id )
            $sql_update_users = mysqli_query($connection, "UPDATE tbl_users SET name ='".$name."', surname ='".$surname."', gender ='".$gender."', address ='".$address."', phone ='".$phone."', updated = NOW() WHERE id ='".$user_session_id."' LIMIT 1");
        
        if ( !$sql_update_users ):
            trigger_error( mysqli_error($connection), E_USER_NOTICE );
            exit();
        else:
            echo '<script> window.location = "profile.php"; </script>';
        endif;
        
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Card Project</title>
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
</head>
<body>
    <div class="container-fluid">
       <!--Navigation Bar-->
       <?php while($results = mysqli_fetch_array($sql_display_user)):?>
       <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
           <a href="index.php" class="navbar-brand">Mano</a>

           <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
           </button>
            
           <div class="collapse navbar-collapse" id="navbarNav">
               <ul class="navbar-nav ml-auto">
                   <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 
                        <?php echo htmlspecialchars($results['email']);?> 
                      </a>
                       
                       <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                           <a class="dropdown-item" href="includes/logout.php">Logout</a>
                       </div>
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
        <!-- Welcome Note -->
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            HEY! <strong><?php echo htmlspecialchars($results['email']);?></strong> Please tell us more about yourself.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endwhile;?>
        <!-- /Welcome Note -->

        <?php foreach($sql_display_user as $data):?>
        <div class="card my-5 border-light mb-3 border-1 mx-auto" style="width: 40%;">
            <div class="card-body">
                <span id="message" class="text-muted text-center"></span>

                <form onsubmit="return false;" class="text-muted">
                    <div class="form-group">
                        <input type="text" class="form-control" id="name" placeholder="First name" name="name" value="<?php echo htmlspecialchars($data['name']);?>"/>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="surname" placeholder="Last name" name="surname" value="<?php echo htmlspecialchars($data['surname']);?>"/>
                    </div>

                    <div class="form-group">
                        <input type="text" class="form-control" id="phone" placeholder="Phone Number" name="phone" value="<?php echo htmlspecialchars($data['phone']);?>" maxlength="10"/>
                    </div>

                    <div class="form-group">
                        <select name="gender" id="gender" class="form-control" >
                            <option value=""></option>
                            <option value="Female">Female</option>
                            <option value="Male" selected>Male</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <textarea type="text" id="address" name="address" class="form-control" placeholder="Add Your Physical Address." value="<?php echo htmlspecialchars($data['address']);?>">
                        </textarea>
                    </div>
                </form>

            </div>
            
            <div class="card-footer text-muted">
                <button type="submit" class="btn btn-outline-success btn_save_profile float-right" id="<?php echo htmlspecialchars($data['id']);?>">Save</button>
            </div>
        </div>
        <?php endforeach;?>
        
        
    </div>
    
    <div class="container-fluid">
        <footer class="footer mt-auto py-3 text-center">
            <div class="container">
                <span class="text-muted text-center font-italic">@Manomoic <?php echo date('Y');?> </span>
            </div>
        </footer>
    </div>
    
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript">
        $(function() {
            
            $('.btn_save_profile').click(function(){
                var name        = $.trim( $('#name').val() );
                var surname     = $.trim( $('#surname').val() );
                var gender      = $.trim( $('#gender').val() );
                var address     = $.trim( $('#address').val() );
                var phone       = $.trim( $('#phone').val() );
                var message     = $('#message');
                
                if ( name == '' ) {
                    message.html('<div class ="alert alert-warning">Name Field Cannot Be Empty.</div>');
                    console.log('Name Field Cannot Be Empty.');
                    
                } else if ( surname == '' ) {
                    message.html('<div class ="alert alert-warning">Surname Field Cannot Be Empty.</div>');
                    console.log('Surname Field Cannot Be Empty.');
                    
                } else if ( gender == '' ) {
                    message.html('<div class ="alert alert-warning">Gender Field Cannot Be Empty.</div>');
                    console.log('Gender Field Cannot Be Empty.');
                    
                } else if ( address == '' ) {
                    message.html('<div class ="alert alert-warning">Address Field Cannot Be Empty.</div>');
                    console.log('Address Field Cannot Be Empty.');
                    
                } else if ( phone == '' ) {
                    message.html('<div class ="alert alert-warning">Phone Number Field Cannot Be Empty.</div>');
                    console.log('Phone Number Field Cannot Be Empty.');
                    
                } else if ( !phone.length > 10 ) {
                    message.html('<div class ="alert alert-warning">Phone Number Must Have 10 Digits.</div>');
                    console.log('Phone Number Must Have 10 Digits.');
                    
                }
                else {
                    
                    $.ajax({
                        URL: "index.php",
                        type: "POST",
                        data: {name:name, surname:surname,gender:gender,address:address, phone:phone},
                        beforeSend: function() {
                            $('.btn_save_profile').attr('disabled', 'disabled');
                            if ( $('.btn_save_profile').val('<?php echo htmlspecialchars($data['id']);?>') ){
                                $('.btn_save_profile').text('Edit');
                            }
                        },
                        success: function(data) {
                            $('.btn_save_profile').attr('disabled', false);
                            
                            if ( data.id == '<?php echo htmlspecialchars($data['id']);?>' ) {
                                
                            }
                            
                            message.html(data);
                            console.log(data);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>