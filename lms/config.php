<?php
session_start();
$pdo=new PDO('mysql:host=localhost;dbname=lms;charset=utf8mb4','lmsuser','lmspass',[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
function e($s){return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8');}
function me(){return $_SESSION['u']??null;}
function q($sql,$p=[]){global $pdo;$s=$pdo->prepare($sql);$s->execute($p);return $s;}
function need(...$roles){$u=me();if(!$u){header('Location: login.php');exit;}if($roles&&!in_array($u['role'],$roles)){http_response_code(403);exit('Access denied');}return $u;}
function tok(){return $_SESSION['t']??=bin2hex(random_bytes(16));}
function f(){return '<input type="hidden" name="t" value="'.tok().'">';}
if($_SERVER['REQUEST_METHOD']==='POST'&&!hash_equals(tok(),$_POST['t']??''))exit('Invalid request');
