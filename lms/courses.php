<?php require 'config.php';$u=need();
if($_SERVER['REQUEST_METHOD']==='POST'){
 if($u['role']==='teacher'&&isset($_POST['title'])&&trim($_POST['title']))q('INSERT INTO courses(title,description,teacher_id) VALUES(?,?,?)',[trim($_POST['title']),$_POST['description'],$u['id']]);
 if($u['role']==='student'&&isset($_POST['enroll']))q('INSERT IGNORE INTO enrollments VALUES(?,?)',[(int)$_POST['enroll'],$u['id']]);
 header('Location: courses.php');exit;}
$cs=q('SELECT c.*,u.name t,(SELECT 1 FROM enrollments WHERE course_id=c.id AND student_id=?) joined FROM courses c JOIN users u ON u.id=c.teacher_id ORDER BY c.id DESC',[$u['id']])->fetchAll();
$title='Courses';include 'header.php';?>
<div class="page-head"><h1>Courses</h1></div>
<?php if($u['role']==='teacher'):?><form class="card" method="post"><h2>Create a course</h2><?=f()?><label>Title<input name="title" required></label><label>Description<textarea name="description" rows="3"></textarea></label><button class="btn">Create course</button></form><?php endif;?>
<div class="grid"><?php foreach($cs as $c):$can=$u['role']==='admin'||$c['teacher_id']==$u['id']||$c['joined'];?>
<div class="course-card"><div class="course-badge">Course</div><h3><?=e($c['title'])?></h3><small>by <?=e($c['t'])?></small><p><?=e($c['description'])?></p>
<?php if($can):?><a class="btn" href="course.php?id=<?=$c['id']?>">Open course</a>
<?php elseif($u['role']==='student'):?><form method="post"><?=f()?><button class="btn" name="enroll" value="<?=$c['id']?>">Enroll</button></form><?php endif;?></div><?php endforeach;?></div>
<?php include 'footer.php';
