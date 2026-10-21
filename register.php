<?php
require_once __DIR__.'/includes/bootstrap.php';
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 verify_csrf(); $name=trim($_POST['name']??''); $email=strtolower(trim($_POST['email']??'')); $phone=trim($_POST['phone']??''); $pass=$_POST['password']??'';
 if(strlen($name)<2 || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($pass)<8) $error='Enter a name, valid email and password of at least 8 characters.';
 else {
  try{$st=$pdo->prepare("INSERT INTO users(name,email,phone,password_hash,role,is_active) VALUES(?,?,?,?, 'customer',1)");$st->execute([$name,$email,$phone?:null,password_hash($pass,PASSWORD_DEFAULT)]);flash('Account created. You can now sign in.');redirect('/lakshmi-electricals/login.php');}
  catch(PDOException $e){$error=$e->getCode()==='23000'?'An account with this email already exists.':'Could not create account. Please try again.';}
 }
}
$pageTitle='Create account | Lakshmi Electricals';require __DIR__.'/includes/header.php';
?>
<section class="auth-wrap"><div class="auth-intro"><span class="brand-mark large">LEH</span><span class="eyebrow">CUSTOMER ACCOUNT</span><h1>Make shopping simpler.</h1><p>Create an account to submit order requests and view their status.</p></div><form class="form-card" method="post"><h2>Create account</h2><?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label>Full name<input name="name" required maxlength="120" autocomplete="name"></label><label>Email address<input type="email" name="email" required autocomplete="email"></label><label>Phone number <span class="muted">(optional)</span><input name="phone" maxlength="30" autocomplete="tel"></label><label>Password <span class="muted">(8+ characters)</span><input type="password" name="password" minlength="8" required autocomplete="new-password"></label><button class="btn btn-gold full">Create account</button><p class="form-foot">Already registered? <a href="/lakshmi-electricals/login.php">Sign in</a></p></form></section>
<?php require __DIR__.'/includes/footer.php'; ?>
