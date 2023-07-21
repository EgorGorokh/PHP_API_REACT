<?php

namespace Api\Controllers;

use Services\DB;

class PostsController
{
    public $conn=null;

    public function __construct()
    {
        $this->conn=(new DB())->databases();
    }
    public function getPosts()
    {
        try {
            $url="https://jsonplaceholder.typicode.com/posts";
            $ch=curl_init();
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_ENCODING, 0);
            curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

            //get images

            $url="https://jsonplaceholder.typicode.com/photos";
            $chImg=curl_init();
            curl_setopt($chImg, CURLOPT_AUTOREFERER, true);
            curl_setopt($chImg, CURLOPT_HEADER, 0);
            curl_setopt($chImg, CURLOPT_ENCODING, 0);
            curl_setopt($chImg, CURLOPT_MAXREDIRS, 10);
            curl_setopt($chImg, CURLOPT_TIMEOUT, 30);
            curl_setopt($chImg, CURLOPT_CUSTOMREQUEST, "GET");
            curl_setopt($chImg, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($chImg, CURLOPT_URL, $url);
            curl_setopt($chImg, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($chImg, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));


            $responseData=json_decode(curl_exec($ch), true);
            // var_dump($responseData);
            $responseImages=json_decode(curl_exec($chImg), true);
            // var_dump($responseImages);
            $newArray=[];
            //combining data

            foreach($responseData as $resData) {
                if(isset($responseImages[$resData['id']])) {
                    $resData['image']=$responseImages[$resData['id']]["url"];
                }
                $newArray[]=$resData;
            }
            $this->savePostsToDatabase($newArray);

        } catch(\Exception $e) {
            var_dump($e->getMessage());
            exit;
        }
    }


    public function savePostsToDatabase($posts=null)
    {
        foreach($posts as $post) {
            $sql="INSERT INTO posts(`user_id`,`title`,`content`,`image`) 
   VALUES (
    '".$post['userId']."',
    '".$post['title']."',
    '".$post['body']."',
    '".$post['image']."')";

            if(mysqli_query($this->conn, $sql)) {
                echo   "new record";
            } else {
                echo "error: ".$sql."<bt/>".mysqli_error($this->conn);
            }

        }

        mysqli_close($this->conn);
    }




    public function getPostsFromDatabase()
    {
        try {



            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: *");
           // echo "<pre>";
            $perPage=$_GET['limit'] ?? 5;
            $pageNumber=$_GET['offset']??0;
            $postsArray=[];

            $sql="SELECT * FROM posts";
            $totalPosts=mysqli_num_rows(mysqli_query($this->conn, $sql));

            $sql="SELECT * FROM posts ORDER BY id LIMIT $perPage OFFSET $pageNumber";
            $responce=mysqli_query($this->conn, $sql);
            if($responce) {
                while($row=mysqli_fetch_assoc($responce)) {
                    $postsArray['posts'][]=$row;
                }
            } else {
                echo"error".$sql."<br/>".mysqli_error($this->conn);
            }

            $postsArray['count']=$totalPosts;

            mysqli_close($this->conn);
            echo json_encode($postsArray, JSON_PRETTY_PRINT);
           // return json_encode($postsArray, JSON_PRETTY_PRINT);


        } catch(\Exception $e) {
            var_dump($e->getMessage());

        }
    }



    public function getSearchResults(){
        try{
$postArray=[];
$keyword=$_GET['keyword'] ??null;

if($keyword){
    $sql="SELECT id,title From posts WHERE title LIKE '%$keyword%' LIMIT 5";
    $responce=mysqli_query($this->conn,$sql);
    if($responce){
        while($row=mysqli_fetch_assoc($responce)){
            $postsArray['posts'][]=$row;
        }
    }
}
echo json_encode($postsArray,JSON_PRETTY_PRINT);
        }catch(\Exception $e){
var_dump($e->getMessage());
exit;
        }
    }





}
