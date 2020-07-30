<?php
session_start();

require_once('includes/init.php');
# Get the session of the user if one exists.
if ( !is_logged_in() || !isset($_SESSION['email']))
    echo '<script> window.location = "includes/login.php"; </script>';

$user_session_id = $_SESSION['email'];

$sql_query = mysqli_query($connection, "SELECT * FROM tbl_users WHERE email ='".$user_session_id."'");

if (!$sql_query):
    trigger_error( mysqli_error($connection), E_USER_NOTICE );
    exit();
endif;

if (isset($_POST['description'])) {
    $description = escape_value($connection, filter_input(INPUT_POST, 'description'));
    
    if ( empty($description) ) {
        echo '<div class ="alert alert-warning">Description Field Cannot Be Empty.</div>';
        exit();
        
    } elseif ( strlen($description) < 20 ) {
        echo '<div class ="alert alert-warning">The Description Characters Must Be More Than 20.</div>';
        exit();
        
    } else {
        
        $sql_update = mysqli_query($connection, "UPDATE tbl_users SET description ='".$description."', updated = NOW() WHERE email ='".$user_session_id."' LIMIT 1");
        
        if (!$sql_update):
            trigger_error( mysqli_error($connection), E_USER_NOTICE );
            exit();
        else:
            echo '<div class ="alert alert-success">Your Description Has Been Updated.</div>';
            exit();
        endif;
        
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile Card</title>
    <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
    <link rel="stylesheet" href="css/style.css" type="text/css" />
    
</head>
<body>
    <div class="container-fluid">
       <!--Navigation Bar-->
       <?php while($results = mysqli_fetch_array($sql_query)):?>
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
       <?php endwhile;?>
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
        
        <?php foreach($sql_query as $user_data):?>
        
        <div class="row no-gutters border rounded overflow-hidden flex-md-row mb-4 shadow-sm h-md-250 position-relative">
            <div class="col p-4 d-flex flex-column position-static">
                <a href="index.php" class="d-inline-block mb-2 text-primary" id="edit_profile_link">Edit Profile Card</a>
                
                <h4 class="mb-0 lead display-4"><?php echo htmlspecialchars(json_encode($user_data['name']).' '.$user_data['surname']);?></h4>
                <p class="card-text mb-auto my-2"><?php echo htmlspecialchars($user_data['description']);?>
                </p>
                
                <!-- Modal Description -->
                <div class="row">
                    <div class="col-3">
                      
                      <?php if ($user_data['description'] == ""): ?>
                        <button type="button" class="btn btn-outline-info btn_add_user_data float-left" data-toggle ="modal" data-target ="#addEditUserModal">Add Description</button>
                       <?php else: ?>
                        <button type="button" class="btn btn-outline-info btn_add_user_data float-left" data-toggle ="modal" data-target ="#addEditUserModal">Edit Description</button>
                       <?php endif; ?>
                       
                        <!-- Modal: Add Or Edit Description -->
                        <div class="modal fade" id="addEditUserModal" tabindex ="-1" role ="dialog" aria-labelledby ="addEditUserModalLabel" aria-hidden ="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title lead" id="addEditUserModalLabel">
                                            Add Your Profile Description.
                                        </h5>

                                        <button class="close" type="button" data-dismiss ="modal" aria-label="close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <div class="modal-body">
                                        <span id="message" class="text-muted text-center"></span>
                                        <form onsubmit="return false;" class="text-muted">
                                            <div class="form-group">
                                                <textarea type="text" id="description" name="description" class="form-control" placeholder="Add Your Profile Description." value="<?php echo htmlspecialchars($user_data['description']);?>">
                                                </textarea>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-danger btn_close_description" data-dismiss="modal" >Close</button>
                                        <button type="submit" class="btn btn-outline-success btn_save_description">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /Modal: Add Or Edit Description -->
                    </div>
                </div>
                
                <!-- List Group: Contacts Detailes -->
                <ul class="list-group list-group-flush text-muted my-2">
                    <li class="list-group-item text-left collapsed" data-toggle="collapse" data-target="#myCollapsible" aria-expanded="false" aria-controls="myCollapsible" style="cursor: pointer;">
                        <div class="row">
                            <div class="col-md-">
                                Click To View Contact Detailes
                            </div>
                        </div>
                    </li>

                    <div class="collapse" id="myCollapsible">
                        <li class="list-group-item bg-light"><?php echo htmlspecialchars($user_data['phone']);?></li>
                        <li class="list-group-item"><?php echo htmlspecialchars($user_data['email']);?></li>
                        <li class="list-group-item bg-light"><?php echo htmlspecialchars($user_data['address']) ;?></li>
                    </div>
                </ul>
                <!-- /List Group: Contacts Detailes -->
                        
                <small class="mb-1 text-muted">Created date: <?php echo htmlspecialchars($user_data['created']);?> </small>
                <small class="mb-1 text-muted">Updated date: <?php echo htmlspecialchars($user_data['updated']);?></small>
            </div>
            <!--
            <div class="col-auto d-none d-lg-block">
                 <div id="container">
                    <img src="img/default.png" width="400" height="400" />
                     <div class="middle">
                         <form onsubmit="return false;" enctype="multipart/form-data">
                             <div class="form-group">
                                <input type="hidden" name="MAX_FILE_SIZE" value="10485760">
                                <input type="file" name="imageupload" id="image_upload" style="position: relative;top: 0;left: 10px;width: 100px;"/>
                                <button class="btn btn-outline-info btn_upload_image" type="button"> Upload </button>
                             </div>
                         </form>
                     </div>    
                 </div>
            </div>
            -->
        </div>
        <?php endforeach;?>
        
    </div>
    
    <!-- Foooter -->
    <div class="container-fluid">
        <footer class="footer mt-auto py-3 text-center">
            <div class="container">
                <span class="text-muted text-center font-italic">@Manomoic <?php echo date('Y');?> </span>
            </div>
        </footer>
    </div>
    
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script>
        $(function() {
            
            $('#myCollapsible').on('hidden.bs.collapse', function () {
                toggle: true
            });
            
            $('.btn_close_description').click(function() {
                $('#description').val('');
                $('#message').html('');
                document.location.reload();
            });
            
            $('.btn_add_user_data').click(function() {
                $('#description').val('');
                $('#message').html('');
            });
            
            $('.btn_save_description').click(function() {
                var description = $.trim( $('#description').val() );
                var message     = $('#message');
                
                if ( description == '' ) {
                    message.html('<div class="alert alert-warning">Description Field Cannot Be Empty.</div>');
                    console.log('Description Field Cannot Be Empty.');
                
                } else {
                    
                    $.ajax({
                        URL: "profile.php",
                        type: "POST",
                        data: {description:description},
                        beforeSend: function() {
                            $('.btn_save_description').attr('disabled', 'disabled');
                        },
                        success: function(data) {
                            $('.btn_save_description').attr('disabled', false);
                            
                            message.html(data);
                            console.log(data);
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>;