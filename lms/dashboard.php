<?php require 'config.php';$u=need();
if($u['role']==='admin'&&($_POST['del']??0)){q('DELETE FROM users WHERE id=? AND role<>"admin"',[(int)$_POST['del']]);header('Location: dashboard.php');exit;}
$title='Dashboard';include 'header.php';echo '<h1>Hello, '.e($u['name']).'</h1>';
if($u['role']==='admin'):$us=q('SELECT * FROM users ORDER BY id DESC')->fetchAll();?>
<div class="stats"><div><b><?=count($us)?></b>users</div><div><b><?=q('SELECT COUNT(*) c FROM courses')->fetch()['c']?></b>courses</div></div>
<div class="card"><h2>Manage users</h2><table><tr><th>Name</th><th>Email</th><th>Role</th><th></th></tr>
<?php foreach($us as $r):?><tr><td><?=e($r['name'])?></td><td><?=e($r['email'])?></td><td><?=e($r['role'])?></td><td><?php if($r['role']!=='admin'):?><form method="post" onsubmit="return confirm('Delete this user?')"><?=f()?><button class="btn ghost" name="del" value="<?=$r['id']?>">Delete</button></form><?php endif;?></td></tr><?php endforeach;?></table></div>
<?php elseif($u['role']==='teacher'):$cs=q('SELECT c.*,(SELECT COUNT(*) FROM enrollments WHERE course_id=c.id) n FROM courses c WHERE teacher_id=?',[$u['id']])->fetchAll();?>
<div class="card"><h2>Your courses</h2><?php if(!$cs):?><p>No courses yet. <a href="courses.php">Create your first course</a>.</p><?php endif;foreach($cs as $c):?><p><a href="course.php?id=<?=$c['id']?>"><?=e($c['title'])?></a> – <?=$c['n']?> students</p><?php endforeach;?></div>
<?php else:$cs=q('SELECT c.id,c.title FROM courses c JOIN enrollments e ON e.course_id=c.id WHERE e.student_id=?',[$u['id']])->fetchAll();
$gr=q('SELECT a.title,c.title ct,s.grade,s.feedback FROM submissions s JOIN assignments a ON a.id=s.assignment_id JOIN courses c ON c.id=a.course_id WHERE s.student_id=? AND s.grade IS NOT NULL',[$u['id']])->fetchAll();?>
<div class="card"><h2>My courses</h2><?php if(!$cs):?><p>You are not enrolled yet. <a href="courses.php">Find a course</a>.</p><?php endif;foreach($cs as $c):?><p><a href="course.php?id=<?=$c['id']?>"><?=e($c['title'])?></a></p><?php endforeach;?></div>
<div class="card"><h2>My results</h2><?php if(!$gr):?><p>Graded work will appear here.</p><?php else:?><table><tr><th>Course</th><th>Assignment</th><th>Grade</th><th>Feedback</th></tr><?php foreach($gr as $g):?><tr><td><?=e($g['ct'])?></td><td><?=e($g['title'])?></td><td><?=e($g['grade'])?></td><td><?=e($g['feedback'])?></td></tr><?php endforeach;endif;?></table></div>
<?php endif;include 'footer.php';
