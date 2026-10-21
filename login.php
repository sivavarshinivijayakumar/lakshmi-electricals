<?php
require_once __DIR__.'/includes/bootstrap.php';
if(current_user()) redirect(current_user()['role']==='admin'?'/lakshmi-electricals/admin/dashboard.php':'/lakshmi-electricals/index.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf();
 $email=strtolower(trim($_POST['email']??'')); $password=$_POST['password']??'';
 $st=$pdo->prepare("SELECT id,name,email,password_hash,role,is_active FROM users WHERE email=? LIMIT 1"); $st->execute([$email]); $user=$st->fetch();
 if($user && $user['is_active'] && password_verify($password,$user['password_hash'])){
   session_regenerate_id(true); unset($user['password_hash']); $_SESSION['user']=$user; csrf_token();
   redirect($user['role']==='admin'?'/lakshmi-electricals/admin/dashboard.php':'/lakshmi-electricals/index.php');
 } else $error='Invalid email or password, or the account is inactive.';
}
$pageTitle='Sign in | Lakshmi Electricals'; require __DIR__.'/includes/header.php';
?>
<section class="auth-wrap"><div class="auth-intro"><span class="brand-mark large">LEH</span><span class="eyebrow">WELCOME BACK</span><h1>Sign in to continue.</h1><p>Browse supplies, manage your requests and stay up to date.</p></div><form class="form-card" method="post"><h2>Sign in</h2><p class="muted">Use your registered account details.</p><?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Email address<input type="email" name="email" required autocomplete="email"></label><label>Password<input type="password" name="password" required autocomplete="current-password"></label><button class="btn btn-gold full" type="submit">Sign in</button><p class="form-foot">New here? <a href="/lakshmi-electricals/register.php">Create a customer account</a></p></form></section>
<?php require __DIR__.'/includes/footer.php'; ?>
