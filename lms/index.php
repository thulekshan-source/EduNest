<?php require 'config.php';$title='Home';include 'header.php';
$n=q('SELECT COUNT(*) c FROM courses')->fetch()['c'];?>
<section class="hero">
  <div class="hero-copy">
    <span class="eyebrow">Smart learning platform</span>
    <h1>One place for every course, assignment and result.</h1>
    <p>Teachers publish lessons, students enroll quickly, and everyone tracks progress in one clean portal.</p>
    <div class="hero-actions">
      <a class="btn big" href="<?=me()?'courses.php':'register.php'?>"><?=me()?'Browse courses':'Start learning'?></a>
      <a class="btn alt" href="login.php">Log in</a>
    </div>
    <ul class="feature-list">
      <li>Course Materials</li>
      <li>Assignments</li>
      <li>Results & Feedback</li>
    </ul>
  </div>
  <div class="panel">
    <div class="panel-top">
      <span class="mini-label">Live overview</span>
      <b><?=$n?></b>
    </div>
    <div class="panel-text">courses available now</div>
    <hr>
    <p>Materials · Assignments · Grades · Announcements</p>
  </div>
</section>

<section class="home-grid">
  <div class="info-card">
    <h3>For students</h3>
    <p>Discover courses, join classes, submit work, and follow your marks in one place.</p>
  </div>
  <div class="info-card">
    <h3>For teachers</h3>
    <p>Publish lessons, create assignments, grade submissions, and keep students informed.</p>
  </div>
  <div class="info-card">
    <h3>For admins</h3>
    <p>Manage user access and keep the learning environment organized and active.</p>
  </div>
</section>
<?php include 'footer.php';
