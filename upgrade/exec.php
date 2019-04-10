<?php 
if(empty($_SESSION['login'])) header('Location: ../index.php?c=login&m=login_proktor');
if($_SESSION['akses'] != 'proktor') header('Location: ../index.php?c=login&m=login_proktor');
ob_end_flush(); ob_implicit_flush(); sleep(1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Upgrade sistem CBT</title>
</head>
<body>
  
<?php

// display output buffer
function display($echo){
  echo $echo; 
}

// set time limit
ini_set('max_execution_time', 0); 

$dir_git = dirname(__FILE__) . '/CBT/';

// bersihkan folder upgrade yang lama
if(file_exists($dir_git)){
  display('membersihkan sisa upgrade yang lama ...<br/>');
  display(shell_exec('rm ' . $dir_git . ' -rf 2>&1'));
}

display('mengunduh pembaruan, mohon ditunggu, proses ini bergantung dari kecepatan koneksi internet ...<br/>');
shell_exec('git clone https://github.com/akhmadzaini/CBT.git 2>&1' );

display('hapus sistem sebelumnya ...<br/>');
display(shell_exec('rm /var/www/application/controllers -rf'));
display(shell_exec('rm /var/www/application/helpers -rf'));
display(shell_exec('rm /var/www/theme -rf'));

// memindah controller
display('memindah controller ke lokasi baru ...<br/>');
display(shell_exec('mv '. $dir_git .'application/controllers /var/www/application/controllers'));

// memindah helper
display('memindah helper ke lokasi baru ...<br/>');
display(shell_exec('mv '. $dir_git .'application/helpers /var/www/application/helpers'));

// memindah theme
display('memindah theme ke lokasi baru ...<br/>');
display(shell_exec('mv '. $dir_git .'theme /var/www/theme'));


?>
</body>
</html>

<script>
  window.location.href = "../index.php?d=proktor&c=dashboard";
</script>