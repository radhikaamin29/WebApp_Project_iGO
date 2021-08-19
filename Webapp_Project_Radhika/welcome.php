<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Welcome</title>
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
        <style>
            .profile-header-container{
                margin: 0 auto;
                text-align: center;
            }
            .profile-header-img {
                padding: 54px;
            }
            .profile-header-img > img.img-circle {
                width: 120px;
                height: 120px;
                border: 2px solid #51D2B7;
            }
            .profile-header {
                margin-top: 43px;
            }
            .profile-details {
                margin-top: 20px;
                text-align: center;
            }
            .signout{
                margin-top: 30px;
            }
        </style>
    </head>
    <body>
        <?php 
            $filepath = "/webapp_project_radhika/images/";
            $filename = $_SESSION['profile_image'];
        ?>
        <div class="container">
            <div class="row">
                <div class="profile-header-container">   
                    <div class="profile-header-img">
                        <img alt="profile image" class="img-circle" src="<?php echo $filepath.$filename ?>" width="120" height="120" />
                        <div class="profile-details">
                            <h1>G'Day <?php echo htmlspecialchars($_SESSION["fname"]); ?>! Welcome to our site.</h1>
                        </div>
                        <div class="signout">
                            <h4><a href="logout.php" class="btn btn-danger ml-3">Sign Out</a></h4>
                        </div>
                </div>
            </div> 
        </div>
    </body>
</html>