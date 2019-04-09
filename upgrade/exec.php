<?php ob_end_flush(); ob_implicit_flush(); sleep(1);?>
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

// system recursive delete
function rrmdir($dir) {
  if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'){
    display(shell_exec(sprintf("rd /s /q %s 2>&1", escapeshellarg('CBT'))));
  }else{
    display(shell_exec(sprintf("rm -rf %s 2>&1", escapeshellarg('CBT'))));
  }
}

// copy entire folder
function xcopy($src, $dest) {
  if (file_exists ( $dest )) rrmdir ( $dest );
  if (is_dir ( $src )) {
    mkdir ( $dest );
    $files = scandir ( $src );
    foreach ( $files as $file )
    if ($file != "." && $file != "..")
    xcopy ( "$src/$file", "$dest/$file" );
  } else if (file_exists ( $src ))
  copy ( $src, $dest );
}

// set time limit
ini_set('max_execution_time', 0); 

$dir_upgrade = dirname(__FILE__);
$dir_git = $dir_upgrade . '/CBT';

// bersihkan folder upgrade yang lama
if(file_exists($dir_git)){
  display('membersihkan sisa upgrade yang lama ...<br/>');
  shell_exec('rm ' . $dir_git . ' -rf 2>&1');
}

display('mengunduh pembaruan, mohon ditunggu, proses ini bergantung dari kecepatan koneksi internet ...<br/>');
shell_exec('git clone https://github.com/akhmadzaini/CBT.git 2>&1' );

// pindah directory
display('pindah direktori ...<br/>');
shell_exec('cd ' . $dir_git . ' 2>&1' );

// menyalin gambar
display('menyalin controller ...<br/>');
shell_exec('cp application/controllers ../../application/controllers  -R');

// menyalin gambar
display('menyalin theme ...<br/>');
shell_exec('cp theme ../../theme  -R');



?>
</body>
</html>
