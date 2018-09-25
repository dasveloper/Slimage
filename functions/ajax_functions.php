<?php ob_start();


session_start();

include("db.php");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "9JhJkhewj";
$dbname = "login_db";
$cloudFrontUri = 'd2yp72sq5kkz8x.cloudfront.net/';
if (isset($_SESSION["apiKey"]) && !empty($_SESSION["apiKey"])) {
    $apiKey = $_SESSION["apiKey"];
}
require '../vendor/autoload.php';

use SparkPost\SparkPost;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

//$con = mysqli_connect('localhost', 'root', '9JhJkhewj', 'login_db');
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;

// Set Amazon S3 Credentials

$bucketName = 'image-resize-927385896375-us-east-1';

$IAM_KEY = 'AKIAJA5SXX2VJPUDAUMA';
$IAM_SECRET = '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';

$s3 = S3Client::factory(
array(
'credentials' => array(
'key' => $IAM_KEY,
'secret' => $IAM_SECRET
),
'version' => 'latest',
'region'  => 'us-east-1'
)
);


if (isset($_GET['action']) && !empty($_GET['action'])) {
    $action = $_GET['action'];
    switch ($action) {
case 'deleteImage': deleteImage();break;
case 'createFolder': createFolder();break;
case 'getFolders': getFolders();break;
case 'getImageCount': getImageCount();break;
case 'getAccount': getAccount();break;

case 'getFolderImages': getFolderImages();break;

// ...etc...
}
}
if (isset($_POST['action']) && !empty($_POST['action'])) {
    $action = $_POST['action'];
    switch ($action) {
case 'login': login();break;
case 'register': validate_user_registration();break;
case 'activateUser': activateUser();break;
case 'forgotPassword': forgotPassword();break;
case 'resetPassword': resetPassword();break;
case 'resetPasswordLoggedIn': resetPasswordLoggedIn();break;


// ...etc...
}
}

    function clean($string) {


    return htmlentities($string);


    }
function getFolderImages()
{
    global $conn;
    global $cloudFrontUri;
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        return;
    }
    if (!isset($_GET['folderId']) || empty($_GET['folderId'])) {
        return;
    }

    $stmt = $conn->prepare("Select ownerApiKey, imageUrl, imageId from `images` where ownerId =  :ownerId and folderId = :folderId");
    $stmt->bindParam(':ownerId', $ownerId, PDO::PARAM_INT);
    $ownerId = $_SESSION['id'];
    $stmt->bindParam(':folderId', $folderId, PDO::PARAM_INT);
    $folderId = $_GET['folderId'];
    $stmt->execute();
    $results=$stmt->fetchAll(\PDO::FETCH_OBJ);

    if ($stmt->rowCount()) {
        $response['status'] = 'success';
        $response['message'] = 'Success';
        $response['results'] =  $results;
        $response['baseUrl'] =  $cloudFrontUri;

        exit(json_encode($response));
    } else {
        $response['status'] = 'error';
        $response['message'] = "No results";
        exit(json_encode($response));
    }
}
function getImageCount()
{
    global $conn;
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        return;
    }


    $stmt = $conn->prepare("Select users.imageCap, count(images.ownerId) as imageCount from `users` left join `images` on users.id = images.ownerId where users.id =  :ownerId GROUP BY images.ownerId");
    $stmt->bindParam(':ownerId', $ownerId, PDO::PARAM_INT);
    $ownerId = $_SESSION['id'];
  
    $stmt->execute();
    $results=$stmt->fetch(\PDO::FETCH_OBJ);
    $imagesRemaining = $results->imageCap - $results->imageCount;
    if ($stmt->rowCount()) {
        $response['status'] = 'success';
        $response['message'] = 'Success';
        $response['count'] =  $imagesRemaining;


        exit(json_encode($response));
    } else {
        $response['status'] = 'error';
        $response['message'] = "Something went wrong";
        exit(json_encode($response));
    }
}
function getFolders()
{
    global $conn;
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        return;
    }


    $stmt = $conn->prepare("Select folderName, folderId from `folders` where ownerId =  :ownerId");
    $stmt->bindParam(':ownerId', $ownerId, PDO::PARAM_INT);
    $ownerId = $_SESSION['id'];

    $stmt->execute();
    $results=$stmt->fetchAll(\PDO::FETCH_OBJ);

    if ($stmt->rowCount()) {
        $response['status'] = 'success';
        $response['message'] = 'Success';
        $response['results'] =  $results;


        exit(json_encode($response));
    } else {
        $response['status'] = 'error';
        $response['message'] = "It appears you've already left a review";
        exit(json_encode($response));
    }
}

function getAccount()
{
    global $conn;
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        return;
    }


    $stmt = $conn->prepare("Select apiKey, email, imageCap from users where id =  :userId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $userId = $_SESSION['id'];

    $stmt->execute();
    $user=$stmt->fetch(\PDO::FETCH_OBJ);

    if ($stmt->rowCount()) {
        $response['status'] = 'success';
        $response['message'] = 'Success';
        $response['user'] =  $user;


        exit(json_encode($response));
    } else {
        $response['status'] = 'error';
        $response['message'] = "We could not find your account please login or contact support";
        exit(json_encode($response));
    }
}


function createFolder()
{
    global $conn;
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        return;
    }
    if (!isset($_GET['folderName']) || empty($_GET['folderName'])) {
        return;
    }

    $stmt = $conn->prepare("INSERT INTO `folders` (ownerId, folderName) VALUES (:ownerId, :folderName)");
    $stmt->bindParam(':ownerId', $ownerId, PDO::PARAM_INT);
    $ownerId = $_SESSION['id'];
    $stmt->bindParam(':folderName', $folderName);
    $folderName = $_GET['folderName'];
    $stmt->execute();
    $lastId = $conn->lastInsertId();


    if ($stmt->rowCount()) {
        $response['status'] = 'success';
        $response['message'] = "Success";
        $response['results'] = $lastId;


        exit(json_encode($response));
    } else {
        $response['status'] = 'error';
        $response['message'] = "Something went wrong";
        exit(json_encode($response));
    }
}

function deleteImage()
{
    global $s3;
    global $bucketName;
    if (isset($_GET['keyname']) && !empty($_GET['keyname'])) {
        $imageId = $_GET['keyname'];
        $sql = "SELECT ownerApiKey, imageUrl FROM images WHERE imageId = '$imageId'";

        $result = query($sql);

        if (row_count($result) == 1) {
            $row = $result->fetch_assoc();
            $key = $row['ownerApiKey']."/".$row['imageUrl'];
            $placeholder = $row['ownerApiKey']."/placeholder/".$row['imageUrl'];
            echo $key;
            $result = $s3->deleteObject(array(
'Bucket' => $bucketName,
'Key'    => $key
));
            $result = $s3->deleteObject(array(
'Bucket' => $bucketName,
'Key'    => $placeholder
));
            $sql = "DELETE FROM images WHERE imageId = '$imageId'";
            $result = query($sql);
        }
        $response['status'] = 'success';
        $response['message'] = "Success";
        exit(json_encode($response));
    } else {
        echo "key not found";
    }
}



function forgotPassword()
{
  global $conn;

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (!isset($_POST['email']) || empty($_POST['email'])) {
            $response['status'] = 'error';
            $response['message'] = "Email field cannot be empty";
            exit(json_encode($response));
        }

    }
    $email        = clean($_POST['email']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
    $stmt->bindParam(':email', $email);

    $stmt->execute();
    if ($stmt->rowCount()) {
 
        $resetCode = bin2hex(random_bytes(16));
        $resetCreated = time();


        $stmt = $conn->prepare("UPDATE users set resetCode = :resetCode, resetCodeCreated = now() where email = :email");
        $stmt->bindParam(':resetCode', $resetCode);
        $stmt->bindParam(':email', $email);

        $stmt->execute();

        if(sendResetEmail($email, $resetCode)){
            $response['status'] = 'success';
            $response['message'] = "Success";
            exit(json_encode($response));
        }
        else{
            $response['status'] = 'error';
            $response['message'] = "Something went wrong";
            exit(json_encode($response));
        }
        $response['status'] = 'error';
        $response['message'] = "Email does not exist";
        exit(json_encode($response));

    }
}
function login()
{
  global $conn;

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (!isset($_POST['email']) || empty($_POST['email'])) {
            $response['status'] = 'error';
            $response['message'] = "Email field cannot be empty";
            exit(json_encode($response));
        }

        if (!isset($_POST['password']) || empty($_POST['password'])) {
            $response['status'] = 'error';
            $response['message'] = "Password field cannot be empty";
            exit(json_encode($response));
        }
        $email        = clean($_POST['email']);
        $password    = clean($_POST['password']);




        $stmt = $conn->prepare("SELECT password, id, apiKey, active FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);

        $stmt->execute();




          if ($stmt->rowCount()) {
              $results=$stmt->fetch(\PDO::FETCH_OBJ);

              $db_password = $results->password;

              if (password_verify($password, $db_password)) {
                  $_SESSION['id'] = $results->id;
                  $_SESSION['apiKey'] = $results->apiKey;

                  $response['status'] = 'success';
                  $response['message'] = "Success";

                  if ($results->active == '1'){
                    $_SESSION['active'] = true;
                    $response['active'] = true;

                    exit(json_encode($response));
                  }
                  $response['active'] = false;
                  exit(json_encode($response));
              } else {
                $response['status'] = 'error';
                $response['message'] = "Email or password is incorrect";
                exit(json_encode($response));
              }
          } else {
            $response['status'] = 'error';
            $response['message'] = "Email or password is incorrect";
            exit(json_encode($response));
          }










        if (login_user($email, $password)) {
            $response['status'] = 'success';
            $response['message'] = "Success";


            exit(json_encode($response));
        } else {
          $response['status'] = 'error';
          $response['message'] = "Email or password are incorrect";


          exit(json_encode($response));
          }
    }
} // function



/****************User login functions ********************/


    function activateUser()
    {
      global $conn;
      if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        $response['status'] = 'error';
        $response['message'] = "Session not available";


        exit(json_encode($response));
      }
      if (!isset($_POST['activation_code']) || empty($_POST['activation_code'])) {
        $response['status'] = 'error';
        $response['message'] = "Please enter activation code";


        exit(json_encode($response));
      }
      $stmt = $conn->prepare("SELECT validation_code FROM users WHERE id = :id");
      $stmt->bindParam(':id', $id, PDO::PARAM_INT);
      $id = $_SESSION['id'];
      $stmt->execute();

        if ($stmt->rowCount()) {
            $results=$stmt->fetch(\PDO::FETCH_OBJ);

            $validation = $results->validation_code;

            if ($validation == $_POST['activation_code']){
              $stmt = $conn->prepare("UPDATE users set active = true WHERE id = :id");
              $stmt->bindParam(':id', $id, PDO::PARAM_INT);
              $stmt->execute();


              $_SESSION['active'] = true;

              $response['status'] = 'success';
              $response['message'] = "Success";


              exit(json_encode($response));

            }
            else{
              $response['status'] = 'error';
              $response['message'] = "Activation code is incorrect";


              exit(json_encode($response));
            }

            return true;
        } else {
            return false;
        }
    }








/****************User login functions ********************/


    function login_user($email, $password)
    {
      global $conn;

        $stmt = $conn->prepare("SELECT password, id, apiKey FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);

        $stmt->execute();



          $sql = "SELECT password, id, apiKey FROM users WHERE email = '".escape($email)."'";

          $result = query($sql);

        if ($stmt->rowCount()) {
            $results=$stmt->fetch(\PDO::FETCH_OBJ);

            $db_password = $results->password;

            if (password_verify($password, $db_password)) {
                $_SESSION['id'] = $results->id;
                $_SESSION['apiKey'] = $results->apiKey;




                return true;
            } else {
                return false;
            }









            return true;
        } else {
            return false;
        }
    }







    /****************Validation functions ********************/



    function validate_user_registration(){

    	$errors = [];




    	if($_SERVER['REQUEST_METHOD'] == "POST") {


        if (!isset($_POST['email']) || empty($_POST['email'])) {
            $response['status'] = 'error';
            $response['message'] = "Email field cannot be empty";
            exit(json_encode($response));
        }

        if (!isset($_POST['password']) || empty($_POST['password'])) {
            $response['status'] = 'error';
            $response['message'] = "Password field cannot be empty";
            exit(json_encode($response));
        }
        if (!isset($_POST['confirm_password']) || empty($_POST['confirm_password'])) {
            $response['status'] = 'error';
            $response['message'] = "Confirm password cannot be empty";
            exit(json_encode($response));
        }


        $email 				= $_POST['email'];
    		$password			= $_POST['password'];
    		$confirm_password	= $_POST['confirm_password'];



    		if(email_exists($email)){

          $response['status'] = 'error';
          $response['message'] = "Email already in use";
          exit(json_encode($response));
    		}


    		if($password !== $confirm_password) {

          $response['status'] = 'error';
          $response['message'] = "Your passwords don't match";
          exit(json_encode($response));
    		}

    			if(register_user($email, $password)) {


            $response['status'] = 'success';
            $response['message'] = "Success";
            exit(json_encode($response));

    			} else {


            $response['status'] = 'error';
            $response['message'] = "Something went wroasang";
            exit(json_encode($response));

    			}

    	} // post request



    } // function

    /****************Register user functions ********************/

    function register_user( $email, $password) {
      global $conn;
    	if(email_exists($email)) {


    		return false;


    	} else {

    		$hashed_password = password_hash($password, PASSWORD_DEFAULT);

    		$validation_code = bin2hex(random_bytes(16));

    		$apiKey = bin2hex(random_bytes(16));



        $stmt = $conn->prepare("INSERT INTO users (email, password, validation_code, apiKey) values (:email, :password, :validation_code, :apiKey)");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':validation_code', $validation_code);
        $stmt->bindParam(':apiKey', $apiKey);
        $stmt->execute();
        $lastId = $conn->lastInsertId();
        $_SESSION['id'] = $lastId;
        $_SESSION['apiKey'] = $apiKey;

        if(sendEmail($email, $validation_code)){

        

    		//$subject = "Activate Account";
    	//	$msg = " Please click the link below to activate your Account
    //		http://edwincodecollege.com/login_app/activate.php?email=$email&code=$validation_code
  //  		";

  //  		$headers = "From: noreply@edwincodecollege.com";



    	//	send_email($email, $subject, $msg, $headers);


    		return true;
        } else{
            return false;
        }
    	}



    }





    function email_exists($email) {

    	$sql = "SELECT id FROM users WHERE email = '$email'";

    	$result = query($sql);

    	if(row_count($result) == 1 ) {

    		return true;

    	} else {


    		return false;

    	}



    }



    function sendEmail($email, $verificationCode){





  
         
        $httpClient = new GuzzleAdapter(new Client());
        $sparky = new SparkPost($httpClient, ['key'=>'2e7260f1d44f33c40bdad118ee3f748f09d0a507', 'async' => 
        false]);
        
        $resetLink = $verificationCode;
        
        $transmissionData = [
            'content' => [
                'from' => [
                    'name' => 'Slimage Verification',
                    'email' => 'support@slimage.io',
                ],
                'subject' => 'Verify your account',
                'html' => '<html>
                <body yahoo="fix" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
                    
                    <!-- ======= main section ======= -->
                    <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="f0f2f5">
                        
                        <tr>
                            <td align="center">
                                
                                <table border="0" align="center" width="510" cellpadding="0" cellspacing="0" class="container590">
                                    
                                    <tr>
                                        <td>
                                            <table align="center" width="180" border="0" cellpadding="0" cellspacing="0" bgcolor="dee0e5">
                                                <tr><td height="1" style="font-size: 1px; line-height: 1px;">&nbsp;</td></tr>
                                            </table>
                                        </td>
                                    </tr>
                                    
                                    <tr><td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td></tr>
                                    
                                    <tr>
                                        <td align="center" style="color: #646b81; font-size: 32px; mso-line-height-rule: exactly; line-height: 30px;" class="title_color main-header">
                                            
                                            <!-- ======= section header ======= -->
                                            
                                            <div style="line-height: 30px;">
                                                
                                                    Thank you for registering
                                                
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr><td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td></tr>
                                    
                                    <tr>
                                        <td align="center">
                                            <table border="0" width="400" align="center" cellpadding="0" cellspacing="0" class="container580">				
                                                <tr>
                                                    <td align="center" style="color: #94969d; font-size: 14px;  mso-line-height-rule: exactly; line-height: 22px;" class="resize-text text_color">
                                                        <div style="line-height: 22px">
                                                            
                                                            <!-- ======= section text ======= -->
                                                            
                                                            Click the link below to activate your account and complete your registration.
                                                                
                                                        </div>
                                                    </td>	
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    
                                    <tr><td height="45" style="font-size: 45px; line-height: 45px;">&nbsp;</td></tr>
                                    
                                    <tr>
                                        <td align="center">
                                            
                                            <table border="0" align="center" width="250" cellpadding="0" cellspacing="0" bgcolor="D73964" style="border-radius: 30px;">
                                                
                                                <tr><td height="13" style="font-size: 13px; line-height: 13px;">&nbsp;</td></tr>
                                                
                                                <tr>
                                                    
                                                    <td align="center" style="color: #ffffff; font-size: 16px; font-family: Questrial, sans-serif;">
                                                        <!-- ======= main section button ======= -->
                                                        
                                                        <div style="line-height: 24px;">
                                                            <a href="https://slimage.io/verifyAccount.php?verificationId={{verificationId}}" style="color: #ffffff; text-decoration: none;">Activate Account</a> 
                                                        </div>
                                                    </td>
                                                    
                                                </tr>
                                                
                                                <tr><td height="13" style="font-size: 13px; line-height: 13px;">&nbsp;</td></tr>
                                            
                                            </table>
                                        </td>
                                    </tr>
                                    
                                    <tr><td height="80" style="font-size: 80px; line-height: 80px;">&nbsp;</td></tr>
                                    
        
                                                                
                                </table>
                            </td>
                        </tr>
                        
                        <tr><td height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td></tr>
                        
                    </table>
                    <!-- ======= end header ======= -->
                    
                    
                </body></html>'
                ],
            'substitution_data' => ['verificationId' => $verificationCode],
            'recipients' => [
                [
                    'address' => [
                        'name' => 'Justin Harr',
                        'email' => $email,
                    ],
                ],
            ]
        ];
        
        try {
            $response = $sparky->transmissions->post($transmissionData);
           
        }
        catch (\Exception $e) {
            echo $e->getCode()."\n";
            echo $e->getMessage()."\n";
        }
        




        return true;
    }

function resetPassword(){
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (!isset($_POST['password']) || empty($_POST['password'])) {
            $response['status'] = 'error';
            $response['message'] = "Password field cannot be empty";
            exit(json_encode($response));
        }

        if (!isset($_POST['confirmPassword']) || empty($_POST['confirmPassword'])) {
            $response['status'] = 'error';
            $response['message'] = "Confirm password field cannot be empty";
            exit(json_encode($response));
        }

        if (!isset($_POST['resetCode']) || empty($_POST['resetCode'])) {
            $response['status'] = 'error';
            $response['message'] = "Reset code is empty or invalid";
            exit(json_encode($response));
        }



        $resetCode        = clean($_POST['resetCode']);
        $password    = clean($_POST['password']);
        $confirmPassword    = clean($_POST['confirmPassword']);
 
        $stmt = $conn->prepare("Select (resetCodeCreated + INTERVAL 15 MINUTE) as resetCodeExpiration from users WHERE resetCode = :resetCode");
        $stmt->bindParam(':resetCode', $resetCode);

        $stmt->execute();
        if (!$stmt->rowCount() > 0) {
            $response['status'] = 'error';
            $response['message'] = "Reset code is empty or invalid";
            exit(json_encode($response));
        }
        $results=$stmt->fetch(\PDO::FETCH_OBJ);
   

        if (time() > strtotime($results->resetCodeExpiration)){
            $response['status'] = 'error';
            $response['message'] = "Reset code expired, please try again";
            exit(json_encode($response));

        }

        if($password !== $confirmPassword) {

            $response['status'] = 'error';
            $response['message'] = "Your passwords don't match";
            exit(json_encode($response));
              }
          
  
       
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
       
        $stmt = $conn->prepare("Update users set password = :password, resetCode = null WHERE resetCode = :resetCode");
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':resetCode', $resetCode);

        $stmt->execute();

            $response['status'] = 'success';
            $response['message'] = "Success";


            exit(json_encode($response));

          $response['status'] = 'error';
          $response['message'] = "Something went wrong";


          exit(json_encode($response));
          
    }
}



    function sendResetEmail($email, $verificationCode){





  
         
$httpClient = new GuzzleAdapter(new Client());
$sparky = new SparkPost($httpClient, ['key'=>'2e7260f1d44f33c40bdad118ee3f748f09d0a507', 'async' => 
false]);

$resetLink = $verificationCode;

$transmissionData = [
    'content' => [
        'from' => [
            'name' => 'Slimage Verification',
            'email' => 'support@slimage.io',
        ],
        'subject' => 'Reset your password',
        'html' => '<html>
        <body yahoo="fix" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
            
            <!-- ======= main section ======= -->
            <table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="f0f2f5">
                
                <tr>
                    <td align="center">
                        
                        <table border="0" align="center" width="510" cellpadding="0" cellspacing="0" class="container590">
                            
                            <tr>
                                <td>
                                    <table align="center" width="180" border="0" cellpadding="0" cellspacing="0" bgcolor="dee0e5">
                                        <tr><td height="1" style="font-size: 1px; line-height: 1px;">&nbsp;</td></tr>
                                    </table>
                                </td>
                            </tr>
                            
                            <tr><td height="40" style="font-size: 40px; line-height: 40px;">&nbsp;</td></tr>
                            
                            <tr>
                                <td align="center" style="color: #646b81; font-size: 32px; mso-line-height-rule: exactly; line-height: 30px;" class="title_color main-header">
                                    
                                    <!-- ======= section header ======= -->
                                    
                                    <div style="line-height: 30px;">
                                        
                                            Password reset link
                                        
                                    </div>
                                </td>
                            </tr>
                            
                            <tr><td height="25" style="font-size: 25px; line-height: 25px;">&nbsp;</td></tr>
                            
                            <tr>
                                <td align="center">
                                    <table border="0" width="400" align="center" cellpadding="0" cellspacing="0" class="container580">				
                                        <tr>
                                            <td align="center" style="color: #94969d; font-size: 14px;  mso-line-height-rule: exactly; line-height: 22px;" class="resize-text text_color">
                                                <div style="line-height: 22px">
                                                    
                                                    <!-- ======= section text ======= -->
                                                    
                                                    A password reset has been requested for this account. If you did not request a password reset you can ignore this email. To reset your password, click the link below.
                                                        
                                                </div>
                                            </td>	
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            <tr><td height="45" style="font-size: 45px; line-height: 45px;">&nbsp;</td></tr>
                            
                            <tr>
                                <td align="center">
                                    
                                    <table border="0" align="center" width="250" cellpadding="0" cellspacing="0" bgcolor="D73964" style="border-radius: 30px;">
                                        
                                        <tr><td height="13" style="font-size: 13px; line-height: 13px;">&nbsp;</td></tr>
                                        
                                        <tr>
                                            
                                            <td align="center" style="color: #ffffff; font-size: 16px; font-family: Questrial, sans-serif;">
                                                <!-- ======= main section button ======= -->
                                                
                                                <div style="line-height: 24px;">
                                                    <a href="https://slimage.io/passwordResetFunction.php?resetCode={{verificationId}}" style="color: #ffffff; text-decoration: none;">Reset Password</a> 
                                                </div>
                                            </td>
                                            
                                        </tr>
                                        
                                        <tr><td height="13" style="font-size: 13px; line-height: 13px;">&nbsp;</td></tr>
                                    
                                    </table>
                                </td>
                            </tr>
                            
                            <tr><td height="80" style="font-size: 80px; line-height: 80px;">&nbsp;</td></tr>
                            

                                                        
                        </table>
                    </td>
                </tr>
                
                <tr><td height="50" style="font-size: 50px; line-height: 50px;">&nbsp;</td></tr>
                
            </table>
            <!-- ======= end header ======= -->
            
            
        </body></html>'
        ],
    'substitution_data' => ['verificationId' => $verificationCode],
    'recipients' => [
        [
            'address' => [
                'name' => 'Justin Harr',
                'email' => $email,
            ],
        ],
    ]
];

try {
    $response = $sparky->transmissions->post($transmissionData);
   
}
catch (\Exception $e) {
    echo $e->getCode()."\n";
    echo $e->getMessage()."\n";
}





return true;
}




function resetPasswordLoggedIn(){
    global $conn;

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        if (!isset($_POST['currentPassword']) || empty($_POST['currentPassword'])) {
            $response['status'] = 'error';
            $response['message'] = "Current password field cannot be empty";
            exit(json_encode($response));
        }
        if (!isset($_POST['newPassword']) || empty($_POST['newPassword'])) {
            $response['status'] = 'error';
            $response['message'] = "New password field cannot be empty";
            exit(json_encode($response));
        }
        if (!isset($_POST['confirmPassword']) || empty($_POST['confirmPassword'])) {
            $response['status'] = 'error';
            $response['message'] = "Confirm password field cannot be empty";
            exit(json_encode($response));
        }

        if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
            return;
        }



        $currentPassword    = clean($_POST['currentPassword']);
        $newPassword    = clean($_POST['newPassword']);

        $confirmPassword    = clean($_POST['confirmPassword']);
 
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $id = $_SESSION['id'];
        $stmt->execute();

        if($newPassword !== $confirmPassword) {

            $response['status'] = 'error';
            $response['message'] = "Your new passwords do not match";
            exit(json_encode($response));
        }


          if ($stmt->rowCount()) {
              $results=$stmt->fetch(\PDO::FETCH_OBJ);

              $db_password = $results->password;

              if (password_verify($currentPassword, $db_password)) {

                $hashed_password = password_hash($newPassword, PASSWORD_DEFAULT);
       
                $stmt = $conn->prepare("Update users set password = :password WHERE id = :id");
                $stmt->bindParam(':id', $id);
                $stmt->bindParam(':password', $hashed_password);
        
                $stmt->execute();
                $response['status'] = 'success';
                $response['message'] = "Success";
    
    
                exit(json_encode($response));
    
              }
else{

          $response['status'] = 'error';
          $response['message'] = "Your password is incorrect";


          exit(json_encode($response));
}
            }




          
  
       



          $response['status'] = 'error';
          $response['message'] = "Something went wrong";


          exit(json_encode($response));
          
    }
}


?>