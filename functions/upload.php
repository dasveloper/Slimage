<?php

session_start();

$apiKey = $_SESSION["apiKey"];
$ownerId = $_SESSION["id"];
$folderId = $_POST["folderId"];

$servername = "localhost";
$username = "root";
$password = "9JhJkhewj";
$dbname = "login_db";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


define('KB', 1024);
define('MB', 1048576);
define('GB', 1073741824);
define('TB', 1099511627776);

function query($query)
{
    global $con;

    $result =  mysqli_query($con, $query);

    confirm($result);

    return $result;
}
function confirm($result)
{
    global $con;

    if (!$result) {
        die("QUERY FAILED" . mysqli_error($con));
    }
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require '../vendor/autoload.php';

$cloudFrontUri = 'http://d2yp72sq5kkz8x.cloudfront.net/';
$target_dir = "function/uploads/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);


    // Installed the need packages with Composer by running:
    // $ composer require aws/aws-sdk-php

    require '../vendor/autoload.php';
    $bucketName = 'image-resize-927385896375-us-east-1';

    $IAM_KEY = 'AKIAJA5SXX2VJPUDAUMA';
    $IAM_SECRET = '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';
    use Aws\S3\S3Client;
    use Aws\S3\Exception\S3Exception;






  use Aws\Lambda\LambdaClient;

  $lambda = LambdaClient::factory(
        array(
            'credentials' => array(
                'key' => $IAM_KEY,
                'secret' => $IAM_SECRET
            ),
            'version' => 'latest',
            'region'  => 'us-east-1'
        )
    );


  // Set Amazon S3 Credentials
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
    $stmt = $conn->prepare("Select users.imageCap, count(images.ownerId) as imageCount from `users` left join `images` on users.id = images.ownerId where users.id =  :ownerId GROUP BY images.ownerId");
    $stmt->bindParam(':ownerId', $ownerId, PDO::PARAM_INT);
    $ownerId = $_SESSION['id'];
  
    $stmt->execute();
    $results=$stmt->fetch(\PDO::FETCH_OBJ);
    $imagesRemaining = $results->imageCap - $results->imageCount;


  foreach ($_FILES as $file) {
      if ($imagesRemaining <= 0){
        http_response_code(400);

        $response['status'] = 'error';
        $response['message'] = "You've exceeded your upload capacity";
        exit(json_encode($response));

      }
      $keyName = $file['name'];
      $imageType = $file['type'];

      $filePath = $file["tmp_name"];

      if ($file["size"] > 10*MB){
        http_response_code(413);
        $response['status'] = 'error';
        $response['message'] = "File too big";
        exit(json_encode($response));

    }
      // check whether it's not empty, and whether it indeed is an uploaded file
      if (!empty($filePath) && is_uploaded_file($filePath)) {
          // the path to the actual uploaded file is in $_FILES[ 'image' ][ 'tmp_name' ][ $index ]
          // do something with it:
      //    $keyName = urlencode($keyName);




          // The region matters. I'm using "US Ohio" so "us-east-2" is the corresponding
          // region code. You can google it or upload a file to the S3 bucket and look at
          // the public url. It will look like:
          // https://s3.us-east-2.amazonaws.com/YOUR_BUCKET_NAME/image.png
          //
          // As you can see the us-east-2 in the url.
          try {
              // So you need to move the file on $filePath to a temporary place.
              // The solution being used: http://stackoverflow.com/questions/21004691/downloading-a-file-and-saving-it-locally-with-php
              if (!file_exists('/tmp/tmpfile')) {
                  mkdir('/tmp/tmpfile');
              }

              // Create temp file
              $tempFilePath = '/tmp/tmpfile/' . basename($filePath);
              $tempFile = fopen($tempFilePath, "w") or die("Error: Unable to open file.");
              $fileContents = file_get_contents($filePath);
              $tempFile = file_put_contents($tempFilePath, $fileContents);
              // Put on S3

              list($width, $height, $type) = getimagesize($tempFilePath);


              $exif = exif_imagetype($tempFilePath);


              if ($type == 1) {
                  $type = "gif";
              } elseif ($type == 2) {
                  $type = "jpeg";
              } elseif ($type == 3) {
                  $type = "png";
              } elseif ($type == 7) {
                  $type = "tif";
              } elseif ($type == 18) {
                $type = "webp";
              } else {
                $response['status'] = 'error';
                $response['message'] = "Invalid image type";
                exit(json_encode($response));
              }

              


              $result = $s3->putObject(
                array(
                    'Bucket'=>$bucketName,
                    'Key' => $apiKey."/".$keyName,
                    'SourceFile' => $tempFilePath,
            'ContentType' => 'image/'.$type
                )
            );
              $payload = json_encode(
      [
        'width' => $width,
        'height' => $height,
      'type' => $type,
       'apiKey' => $apiKey,
       'fileName' => $keyName
     ]

    );


              $result = $lambda->invoke([
        // The name your created Lamda function
        'FunctionName' => 'placeHolder-blur',
        'Payload' => $payload,
    ]);
    
   // $placeholder_url = $s3->getObjectUrl($bucketName, $apiKey."/placeholder/".$keyName);
  //  $placeholder_path = parse_url($placeholder_url, PHP_URL_PATH);




      

        $encoded_keyName =   rawurlencode($keyName);
        $stmt = $conn->prepare("INSERT into images (ownerApiKey, ownerId, folderId, imageUrl) values (:ownerApiKey,:ownerId,:folderId,:keyName)");
        $stmt->bindParam(':ownerId', $ownerId, PDO::PARAM_INT);
        $stmt->bindParam(':folderId', $folderId, PDO::PARAM_INT);
        $stmt->bindParam(':ownerApiKey', $apiKey);
        $stmt->bindParam(':keyName', $encoded_keyName);


        $stmt->execute();

        $imagesRemaining = $imagesRemaining - 1;
        $response['status'] = 'success';
        $response['message'] = "Success";
        exit(json_encode($response));
          } catch (S3Exception $e) {
              echo $e->getMessage();
          } catch (Exception $e) {
              echo $e->getMessage();
          }
      }
  }
?>
