<?php
$parentPid  =  getmygid();
$childPid = pcntl_fork();
switch ($parentPid) {
    case -1:   print_r('创建子进程失败'.PHP_EOL);  exit;
    case 0:    print_r('这是子进程，ID='.$childPid.PHP_EOL); break;
    default:   print_r('父进程，ID='.$parentPid.'>>>子进程ID='.$childPid.PHP_EOL);
}




