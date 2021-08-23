function printError(elemId, hintMsg) {
    document.getElementById(elemId).innerHTML = hintMsg;
  }
  function formValidation(){
    var fname = document.registerform.fname.value;
    var lname = document.registerform.lname.value;
    var email = document.registerform.email.value;
    var contact = document.registerform.contact.value;
    var street_add = document.registerform.street_add.value;
    var city = document.registerform.city.value;
    var state = document.registerform.state.value;
    var postcode = document.registerform.postcode.value;
    var password = document.registerform.password.value;
    var confirm_password = document.registerform.confirm_password.value;
    var profile_image = document.registerform.profile_image.value;

   var fname_err = lname_err = email_err = contact_err = 
   street_add_err = city_err = state_err = postcode_err = password_err = 
   confirm_password_err = profile_img_err = true;
  
   if(fname == "") {
      printError("fname_err", "First Name is required");
    } else {
      var regex = /^[a-zA-Z\s]+$/;                
      if(regex.test(fname) === false) {
          printError("fname_err", "First name can only contain letters.");
      } else {
          printError("fname_err", "");
          fname_err = false;
      }
    }
    if(lname == "") {
        printError("lname_err", "Last Name is required");
      } else {
        var regex = /^[a-zA-Z\s]+$/;                
        if(regex.test(lname) === false) {
            printError("lname_err", "Last name can only contain letters.");
        } else {
            printError("lname_err", "");
            lname_err = false;
        }
    }
    if(profile_image == ''){
        printError("profile_img_err","Please select image");
    }else{
        var regex = profile_image.substring(profile_image.lastIndexOf('.') + 1).toLowerCase();
        if(regex == "jpeg" || regex == "png" || regex == "jpg") {
            printError("profile_img_err", "");
            profile_img_err = false;
        } else{
            printError("profile_img_err", "Photo only allows file types of PNG, JPG, JPEG.");
        }
          
    }
    if(email == "") {
        printError("email_err", "Please enter your email address");
    } else {
        // Regular expression for basic email validation
        var regex = /^\S+@\S+\.\S+$/;
        if(regex.test(email) === false) {
            printError("email_err", "Please enter a valid email address");
        } else{
            printError("email_err", "");
            emailErr = false;
        }
    }
    if(contact == "") {
        printError("contact_err", "Please enter your contact number");
    } else {
        var regex = /^\d{10}$/;
        if(regex.test(contact) === false) {
            printError("contact_err", "Please enter a valid 10 digit contact number");
        } else{
            printError("contact_err", "");
            contact_err = false;
        }
    }
    if(street_add == "") {
        printError("street_add_err", "Street Address is required");
      } else {
            printError("street_add_err", "");
            street_add_err = false;
      }
    
      if(city == "") {
        printError("city_err", "City Name is required");
      } else {
        var regex = /^[a-zA-Z\s]+$/;                
        if(regex.test(city) === false) {
            printError("city_err", "City name can only contain letters.");
        } else {
            printError("city_err", "");
            city_err = false;
        }
      }
      if(state == 0) {
        printError("state_err", "Please select state");
      } else {
            printError("state_err", "");
            state_err = false;
        }
    if(postcode == "") {
        printError("postcode_err", "Postcode is required");
        } else {
        var regex = /^[1-9]\d{3}$/;              
        if(regex.test(postcode) === false) {
            printError("postcode_err", "Postcode should be 4 digits.");
        } else {
            printError("postcode_err", "");
            postcode_err = false;
        }
    }

    if(password == "") {
        printError("password_err", "Password is required");
        } else {             
        if(password.length < 6) {
            printError("password_err", "Password length must be atleast 6 characters.");
        } else {
            printError("password_err", "");
            password_err = false;
        }
    }
    if(confirm_password == "") {
        printError("confirm_password_err", "Confirm Password is required");
        } else {             
        if(password != confirm_password) {
            printError("confirm_password_err", "Password and Confirm password not matching.");
        } else {
            printError("confirm_password_err", "");
            confirm_password_err = false;
        }
    }

    if((fname_err || lname_err || email_err || contact_err || street_add_err 
        || city_err || state_err || postcode_err || password_err || confirm_password_err)== false){
            document.getElementById('rForm').submit();
            
    }else {
        return false;
    }
  }
  function loginValidation(){
    var email = document.registerform.email.value;
    var password = document.registerform.password.value;

    var email_err = $password_err = true;
    if(email == "") {
        printError("email_err", "Please enter your email address");
    } else {
        // Regular expression for basic email validation
        var regex = /^\S+@\S+\.\S+$/;
        if(regex.test(email) === false) {
            printError("email_err", "Please enter a valid email address");
        } else{
            printError("email_err", "");
            emailErr = false;
        }
    }
    if(password == "") {
        printError("password_err", "Password is required");
    } else {             
        if(password.length < 6) {
            printError("password_err", "Password length must be atleast 6 characters.");
        } else {
            printError("password_err", "");
            password_err = false;
        }
    }

    if((email_err || password_err) == true){
        return false;
    }
  }