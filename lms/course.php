<?php require 'config.php';$u=need();$id=(int)($_GET['id']??0);
$c=q('SELECT c.*,u.name t FROM courses c JOIN users u ON u.id=c.teacher_id WHERE c.id=?',[$id])->fetch();if(!$c)exit('Course not found');
$own=$u['role']==='admin'||$c['teacher_id']==$u['id'];
if(!$own&&!q('SELECT 1 FROM enrollments WHERE course_id=? AND student_id=?',[$id,$u['id']])->fetch()){header('Location: courses.php');exit;}
if($_SERVER['REQUEST_METHOD']==='POST'){$d=$_POST['do']??'';$P=$_POST;
 if($own&&$d==='material'&&preg_match('#^https?://#',$P['url']))q('INSERT INTO materials(course_id,title,url) VALUES(?,?,?)',[$id,$P['title'],$P['url']]);
 if($own&&$d==='assign')q('INSERT INTO assignments(course_id,title,details,due_date) VALUES(?,?,?,?)',[$id,$P['title'],$P['details'],$P['due']?:null]);
 if($own&&$d==='news')q('INSERT INTO announcements(course_id,title,body) VALUES(?,?,?)',[$id,$P['title'],$P['body']]);
 if($own&&$d==='grade')q('UPDATE submissions s JOIN assignments a ON a.id=s.assignment_id SET s.grade=?,s.feedback=? WHERE s.id=? AND a.course_id=?',[$P['grade'],$P['feedback'],(int)$P['sid'],$id]);
 if($u['role']==='student'&&$d==='submit'&&q('SELECT 1 FROM assignments WHERE id=? AND course_id=?',[(int)$P['aid'],$id])->fetch())q('INSERT INTO submissions(assignment_id,student_id,answer) VALUES(?,?,?) ON DUPLICATE KEY UPDATE answer=VALUES(answer),grade=NULL',[(int)$P['aid'],$u['id'],$P['answer']]);
 header("Location: course.php?id=$id");exit;}
$ms=q('SELECT * FROM materials WHERE course_id=?',[$id])->fetchAll();$as=q('SELECT * FROM assignments WHERE course_id=? ORDER BY due_date',[$id])->fetchAll();$ns=q('SELECT * FROM announcements WHERE course_id=? ORDER BY id DESC',[$id])->fetchAll();
$title=$c['title'];include 'header.php';?>
<h1><?=e($c['title'])?></h1><p class="muted">Taught by <?=e($c['t'])?> – <?=e($c['description'])?></p>
<div class="cols"><div>
<div class="card"><h2>Announcements</h2><?php if(!$ns)echo'<p>No announcements yet.</p>';foreach($ns as $n):?><p><b><?=e($n['title'])?></b><br><?=nl2br(e($n['body']))?></p><?php endforeach;
if($own):?><form method="post"><?=f()?><input type="hidden" name="do" value="news"><input name="title" placeholder="Title" required><textarea name="body" placeholder="Message"></textarea><button class="btn">Post announcement</button></form><?php endif;?></div>
<div class="card"><h2>Assignments</h2><?php if(!$as)echo'<p>No assignments yet.</p>';foreach($as as $a):?><div class="item"><b><?=e($a['title'])?></b> <small>due <?=e($a['due_date']?:'any time')?></small><p><?=nl2br(e($a['details']))?></p>
<?php if($u['role']==='student'):$s=q('SELECT * FROM submissions WHERE assignment_id=? AND student_id=?',[$a['id'],$u['id']])->fetch();?>
<?php if($s&&$s['grade']!==null):?><p class="ok">Grade: <?=e($s['grade'])?> – <?=e($s['feedback'])?></p><?php endif;?>
<form method="post"><?=f()?><input type="hidden" name="do" value="submit"><input type="hidden" name="aid" value="<?=$a['id']?>"><textarea name="answer" placeholder="Write your answer or paste a link"><?=e($s['answer']??'')?></textarea><button class="btn"><?=$s?'Update submission':'Submit work'?></button></form>
<?php elseif($own):foreach(q('SELECT s.*,u.name FROM submissions s JOIN users u ON u.id=s.student_id WHERE assignment_id=?',[$a['id']])->fetchAll() as $s):?>
<form method="post" class="sub"><?=f()?><input type="hidden" name="do" value="grade"><input type="hidden" name="sid" value="<?=$s['id']?>"><b><?=e($s['name'])?>:</b> <?=e($s['answer'])?><input name="grade" type="number" step="0.01" max="100" min="0" placeholder="Grade" value="<?=e($s['grade'])?>" required><input name="feedback" placeholder="Feedback" value="<?=e($s['feedback'])?>"><button class="btn">Save grade</button></form><?php endforeach;endif;?></div><?php endforeach;
if($own):?><form method="post"><?=f()?><input type="hidden" name="do" value="assign"><input name="title" placeholder="Assignment title" required><textarea name="details" placeholder="Instructions"></textarea><input type="date" name="due"><button class="btn">Add assignment</button></form><?php endif;?></div></div>
<div><div class="card"><h2>Learning materials</h2><?php if(!$ms)echo'<p>No materials yet.</p>';foreach($ms as $m):?><p><a href="<?=e($m['url'])?>" target="_blank" rel="noopener"><?=e($m['title'])?></a></p><?php endforeach;
if($own):?><form method="post"><?=f()?><input type="hidden" name="do" value="material"><input name="title" placeholder="Title" required><input name="url" placeholder="https://link-to-file-or-video" required><button class="btn">Add material</button></form><?php endif;?></div></div></div>
<?php include 'footer.php';
