<?php
require_once('dbconfig.php');
$response=array();
if($_SERVER['REQUEST_METHOD']=='POST')
{
    if(!empty($_FILES['upload']['name'])&&isset($_POST['profile_id']))
    {
        $user_dir=$_POST['profile_id'];
        $file_path=null;
          //Get the temp file path
            $tmpFilePath = $_FILES['upload']['tmp_name'];
            $file_ext=pathinfo($_FILES["upload"]["name"])['extension'];
            $extensions= array("jpg","jpeg","png"); 
            if(in_array($file_ext,$extensions)=== false)
            {
                http_response_code(400);
                $response['message']="Extension Not Allowed";
            }
            else
            {
            //Make sure we have a filepath
                if($tmpFilePath != "")
                {
                //save the filename
                    $shortname = $_FILES['upload']['name'];
                    if (!file_exists('../profiles/uploads/'.$user_dir))
                    {
                       mkdir('../profiles/uploads/'.$user_dir, 0777, true);
                    }
                    //save the url and the file
                    $filePath = "../profiles/uploads/".$user_dir."/" .time().'-'.$_FILES['upload']['name'];
                    $fileurl="profiles/uploads/".$user_dir."/" .time().'-'.$_FILES['upload']['name'];
                    //Upload the file into the temp dir
                    if(move_uploaded_file($tmpFilePath, $filePath))
                    {
                        $file = $shortname;
                        $file_path='http://www.21cssindia.com/couplemeetup/'.$fileurl;
                        $sql_insert="INSERT INTO profile_images (image_url, profile_id) VALUES ('$file_path', $user_dir)";
                        $res_insert=$mysqli->query($sql_insert);
                        if(!$res_insert) die($mysqli->error);
                        $image_id=mysqli_insert_id($mysqli);

                    }
                    else
                    {
                        http_response_code(400);
                        $response['message']="File not uploaded";
                    }
                }
            }
        $response['image_id']=$image_id;
        $response['profile_id']=$user_dir;
        $response['file']=$file_path;
    }
    else
    {
        http_response_code(204);
        $response['message']="No File/Profile ID Selected";
    }
   
}
elseif ($_SERVER['REQUEST_METHOD']=='GET') {
    # code...
    if(isset($_GET['profile_id'])){
        $profile_id=$_GET['profile_id'];
        $sql_get="SELECT * FROM profile_images WHERE profile_id=$profile_id";
        $res_get=$mysqli->query($sql_get);
        while ($row_get=$res_get->fetch_assoc()) {
            # code...
            $row_array['image_id'] = $row_get['image_id'];
            $row_array['image_url'] = $row_get['image_url'];
            array_push($response,$row_array);
        }


    }
    else{ 
           http_response_code(400);
    
    $response['message']="Profile id not set";
}
}
else
{
    http_response_code(405);
}
 //show success message
    header('Content-Type: application/json');
    echo json_encode($response, JSON_UNESCAPED_SLASHES);
?>