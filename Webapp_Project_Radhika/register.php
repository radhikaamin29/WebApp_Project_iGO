<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$fname_err = $lname_err = $email_err = $contact_err ="" ;
$street_add_err = $city_err = $state_err = $postcode_err = $password_err = $confirm_password_err = "";
$fname = $lname = $email = $contact ="";
$street_add = $city = $state = $postcode = $password = $confirm_password = ""; 


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

        if (empty($_POST["fname"])) {
          $fname_err = "First Name is required";
        }elseif(!preg_match('/^[a-zA-Z]*$/', trim($_POST["fname"]))){
            $fname_err = "First name can only contain letters.";
        }else {
            $fname = test_input($_POST["fname"]);
        }

       if (empty($_POST["lname"])) {
           $lname_err = "Last Name is required";
        }elseif(!preg_match('/^[a-zA-Z]*$/', trim($_POST["lname"]))){
            $lname_err = "Last name can only contain letters.";
        }else {
           $lname = test_input($_POST["lname"]);
        }

        if (empty($_POST["email"])) {  
          $email_err = "Email Address is required";  
        } else {  
            $email = test_input($_POST["email"]);  
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {  
                $emailErr = "Invalid email format";  
            }  
        }  

       if (empty($_POST["contact"])) {
           $contact_err = "Contact Number is required";
         } else {
           $contact = test_input($_POST["contact"]);
           if (!preg_match("/^[0-9+]*$/",$contact)) {
             $contact_err = "Contact number should contain only numbers";
           }
         }
         if (empty($_POST["street_add"])) {
            $street_add_err = "Street Address is required";
         } else {
            $street_add = test_input($_POST["street_add"]);
         }
        
         if (empty($_POST["city"])) {
            $city_err = "City name is required";
        } else {
            $city = test_input($_POST["city"]);
            if (!preg_match("/^[a-zA-Z]*$/",$city)) {
              $city_err = "City should contain only letters";
            }
        }
        if($_POST['state'] == '0') { 
          $state_err = 'Please select a state.'; 
        } else{
          $state = test_input($_POST["state"]);
        }
        if (empty($_POST["postcode"])) {
          $postcode_err = "Postcode is required";
        } else {
            $postcode = test_input($_POST["postcode"]);
            if (!preg_match("/^[0-9]*$/",$postcode)) {
              $postcode_err = "Postcode should contain only numbers";
            }
        }
        if(empty($_FILES['profile_image']['name'])){
          $profile_img_err="Please select image.";
        } else{
            $file_name = $_FILES['profile_image']['name'];
            $file_size =$_FILES['profile_image']['size'];
            $file_tmp =$_FILES['profile_image']['tmp_name'];
            $file_type=$_FILES['profile_image']['type'];
            $file_ext = strtolower(pathinfo($_FILES['profile_image']['name'], PATHINFO_EXTENSION));  
            $extensions= array("jpeg","jpg","png");
            if(in_array($file_ext,$extensions)=== false){
               $profile_img_err="Please choose a JPEG or PNG file.";
            }elseif($file_size > 2097152){
               $profile_img_err ='File size must be excately 2 MB';
            }else{
              move_uploaded_file($file_tmp,"images/".$file_name);
              $profile_img = $file_name;
            }
         }
            // Validate email
            if(empty(test_input($_POST["email"]))){
                $email_err = "Email address is required";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $email_err = "Invalid email format"; 
            } else{
                // Prepare a select statement
                $sql = "SELECT id FROM users WHERE email = ?";
                if($stmt = mysqli_prepare($link, $sql)){
                    mysqli_stmt_bind_param($stmt, "s", $param_email);
                    $param_email = test_input($_POST["email"]);
                    if(mysqli_stmt_execute($stmt)){
                        mysqli_stmt_store_result($stmt);
                        if(mysqli_stmt_num_rows($stmt) == 1){
                            $email_err = "This email address is already taken.";
                        } else{
                            $email = test_input($_POST["email"]);
                        }
                    } else{
                        echo "Oops! Something went wrong. Please try again later.";
                    }
                    mysqli_stmt_close($stmt);
                }
            }
    
            // Validate password
            if(empty(test_input($_POST["password"]))){
                $password_err = "Please enter a password.";     
            } elseif(strlen(test_input($_POST["password"])) < 6){
                $password_err = "Password must have atleast 6 characters.";
            } else{
                $password = test_input($_POST["password"]);
            }
    
            // Validate confirm password
            if(empty(test_input($_POST["confirm_password"]))){
                $confirm_password_err = "Please confirm password.";     
            } else{
                $confirm_password = test_input($_POST["confirm_password"]);
                if(empty($password_err) && ($password != $confirm_password)){
                    $confirm_password_err = "Password did not match.";
                }
            }
    
    // Check input errors before inserting in database
    if(empty($fname_err) && empty($lname_err) && empty($email_err) && 
    empty($contact_err) && empty($city_err) && empty($state_err) && 
    empty($postcode_err) && empty($profile_img_err) && empty($password_err) && 
    empty($stare_add_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (fname, lname,profile_image,email,contact,
            street_add,city,state,postcode,country,password) VALUES (?,?,?,?,?,?,?,?,?,?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $country = $_POST['country'];
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssssss", $fname,$lname,$profile_img,
            $email,$contact,$street_add,$city,$state,$postcode,$country,$param_password);
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    // Close connection
    mysqli_close($link);
}
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
 }
?>
 
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 600px; padding: 20px; margin:auto;}
        .form-control.is-invalid{ background-image:none;}
    </style>
  </head>
  <body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <h5>Please fill this form to create an account.</h5>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
        method="post" enctype="multipart/form-data">
            <div class="form-row">
              <div class="form-group col-md-6">
                  <label>First Name</label>
                  <input type="text" name="fname" class="form-control 
                  <?php echo (!empty($fname_err)) ? 'is-invalid' : ''; ?>" 
                  value="<?php echo $fname; ?>">
                  <span class="invalid-feedback"><?php echo $fname_err; ?></span>
              </div>
              
              <div class="form-group col-md-6">
                  <label>Last Name</label>
                  <input type="text" name="lname" class="form-control
                  <?php echo (!empty($lname_err)) ? 'is-invalid' : ''; ?>" 
                  value="<?php echo $lname; ?>">
                  <span class="invalid-feedback"><?php echo $lname_err; ?></span>
              </div> 
            </div>
            <div class="form-row">
              <div class="form-group col-md-12">
                  <label for="exampleFormControlFile1">Profile Picture</label>
                  <input type="file" class="form-control-file 
                  <?php echo (!empty($profile_img_err)) ? 'is-invalid' : ''; ?>" 
                  id="exampleFormControlFile1" name="profile_image" value="<?php echo $profile_img; ?>">
                  <span class = "invalid-feedback"> <?php echo $profile_img_err;?></span>
              </div>
            </div>
            <div class="form-row" >
              <div class="form-group col-md-6">
                <label>Email Address</label>
                <input type = "text" name = "email" class="form-control 
                    <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $email; ?>">
                <span class = "invalid-feedback"> <?php echo $email_err;?></span>
              </div>
              <div class="form-group col-md-6">
                <label>Contact</label>
                <input type = "text" name = "contact" class="form-control 
                    <?php echo (!empty($contact_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $contact; ?>">
                <span class = "invalid-feedback"> <?php echo $contact_err;?></span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Street Address</label>
                <input type = "text" name = "street_add" class="form-control 
                    <?php echo (!empty($street_add_err)) ? 'is-invalid' : ''; ?>"
                    value="<?php echo $street_add; ?>">
                <span class = "invalid-feedback"> <?php echo $street_add_err;?></span>
              </div>
              <div class="form-group col-md-6">
                <label>City/Suburb</label>
                <input type = "text" name = "city" class="form-control 
                  <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>"
                  value="<?php echo $city; ?>">
                <span class = "invalid-feedback"> <?php echo $city_err;?></span>
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="inputState">State</label>
                <select id="inputState" class="form-control 
                  <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>"
                  name="state">
                  <option selected value="0">Choose...</option>
                  <option value="ACT">ACT</option>
                  <option value="NSW">NSW</option>
                  <option value="NT">NT</option>
                  <option value="QLD">QLD</option>
                  <option value="SA">SA</option>
                  <option value="VIC">VIC</option>
                  <option value="TAS">TAS</option>
                  <option value="WA">WA</option>
                  <span class = "invalid-feedback"> <?php echo $state_err;?></span>
                </select>
                <span class = "invalid-feedback"> <?php echo $state_err;?></span>
              </div>
              <div class="form-group col-md-4">
                <label>Postcode</label>
                <input type = "text" name = "postcode" class="form-control 
                  <?php echo (!empty($postcode_err)) ? 'is-invalid' : ''; ?>"
                  value="<?php echo $postcode; ?>">
                <span class = "invalid-feedback"> <?php echo $postcode_err;?></span>
              </div>
              <div class="form-group col-md-4">
                <label>Country</label>
                <input readonly type = "text"  name = "country" class="form-control"
                  value="Australia">
              </div>
            </div>
            <div class="form-row">
              <div class="form-group col-md-6">
                <label>Password</label>
                <input type="password" name="password" class="form-control 
                <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" 
                value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
              </div>
              <div class="form-group col-md-6">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control 
                <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" 
                value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
              </div>
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="Submit">
              <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            <h5>Already have an account? <a href="login.php">Login here</a>.</h5>
        </form>
    </div>    
  </body>
</html>