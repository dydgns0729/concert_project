<?php
include_once './password.php';
date_default_timezone_set("Asia/Seoul");    //서울시간으로 설정
$DBflag = "NO";
$con =mysqli_connect($ip,$id,$pwd,$db) or die("MySQL 접속실패!");
$sql = "show databases";
$result = mysqli_query($con, $sql) or die("실패원인:".mysqli_error($con));

while($row=mysqli_fetch_row($result)){
    if($row[0] == "classDB"){
        $DBflag = "OK";
        break;
    }
}
if($DBflag !== "OK"){
    $sql = "create database classDB";
    if(mysqli_query($con,$sql)){
        echo "<script>alert('classDB 생성완료!')</script>";
    }
}
$con = mysqli_connect("localhost","root","123456","classDB") or die("classDB 접속실패!");
session_start(); 
if(!empty($_SESSION['userid'])){
    $userid=$_SESSION['userid'];
}
if(!empty($_SESSION['username'])){
    $username=$_SESSION['username'];
}
if(!empty($_SESSION['usernick'])){
    $usernick=$_SESSION['usernick'];
}
if(!empty($_SESSION['userlevel'])){
    $userlevel=$_SESSION['userlevel'];
}
?>
