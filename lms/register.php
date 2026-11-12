<?php require 'config.php';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){$n=trim($_POST['name']);$m=trim($_POST['email']);$p=$_POST['password'];$r=$_POST['role']==='teacher'?'teacher':'student';
 if(!$n||!filter_var($m,FILTER_VALIDATE_EMAIL)||strlen($p)<6)$err='Enter your name, a valid email and a password of 6+ characters.';
 elseif(q('SELECT 1 FROM users WHERE email=?',[$m])->fetch())$err='That email is already registered.';
 else{q('INSERT INTO users(name,email,password,role) VALUES(?,?,?,?)',[$n,$m,password_hash($p,PASSWORD_DEFAULT),$r]);header('Location: login.php?ok=1');exit;}}
$title='Create account';include 'header.php';?>
<div class="auth-wrap">
  <form class="card auth-card" method="post">
    <div class="auth-header">
      <span class="eyebrow">Join EduNest</span>
      <h2>Create your account</h2>
    </div>
    <?php if($err):?><p class="err"><?=e($err)?></p><?php endif;?>
    <?=f()?>
    <label>Full name<input name="name" required></label>
    <label>Email<input type="email" name="email" required></label>
    <label>Password<input type="password" name="password" minlength="6" required></label>
    <label>I am a<select name="role"><option value="student">Student</option><option value="teacher">Teacher</option></select></label>
    <button class="btn">Create account</button>
    <p class="auth-foot">Already have an account? <a href="login.php">Log in</a></p>
  </form>
</div>
<?php include 'footer.php';
