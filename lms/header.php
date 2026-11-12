<?php $u=me();?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=e($title??'LMS')?> – EduNest</title>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;700&family=Nunito+Sans:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css"></head><body>
<header class="top"><a class="brand" href="index.php"><span class="mark"></span>EduNest</a>
<nav><?php if($u):?><a href="dashboard.php">Dashboard</a><a href="courses.php">Courses</a><a href="chatbot.php">AI Chatbot</a><span class="who"><?=e($u['name'])?> (<?=e($u['role'])?>)</span><a class="btn ghost" href="logout.php">Log out</a>
<?php else:?><a href="login.php">Log in</a><a class="btn" href="register.php">Create account</a><?php endif;?></nav></header><main>
