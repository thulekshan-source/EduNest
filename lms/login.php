<?php require 'config.php';$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){$r=q('SELECT * FROM users WHERE email=?',[trim($_POST['email'])])->fetch();
 if($r&&password_verify($_POST['password'],$r['password'])){session_regenerate_id(true);$_SESSION['u']=['id'=>$r['id'],'name'=>$r['name'],'role'=>$r['role']];header('Location: dashboard.php');exit;}
 $err='Email or password is incorrect.';}
$title='Log in';include 'header.php';?>
<div class="auth-wrap">
  <form class="card auth-card" method="post">
    <div class="auth-header">
      <span class="eyebrow">Welcome back</span>
      <h2>Log in</h2>
    </div>
    <?php if(isset($_GET['ok'])):?><p class="ok">Account created. Log in to continue.</p><?php endif;?>
    <?php if($err):?><p class="err"><?=e($err)?></p><?php endif;?>
    <?=f()?>
    <label>Email<input type="email" name="email" required></label>
    <label>Password<input type="password" name="password" required></label>
    <button class="btn">Log in</button>
    <p class="auth-foot">Need an account? <a href="register.php">Create one</a></p>
  </form>
</div>
<?php include 'footer.php';
