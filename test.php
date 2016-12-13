<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function isUserRead($_file) {
$userinfo = posix_getpwuid(posix_geteuid());
$perms = fileperms($_file);
$bPerm =0;
if ($userinfo['gid'] == filegroup($_file))
    $bPerm += (($perms & 0x0020) ? 1: 0);
if ($userinfo['uid'] == fileowner($_file))
    $bPerm += (($perms & 0x0100) ? 1: 0);
return (($bPerm > 0) ? 1 : 0);
}

function isUserWrite($_file) {
$userinfo = posix_getpwuid(posix_geteuid());
$perms = fileperms($_file);
$bPerm =0;
if ($userinfo['gid'] == filegroup($_file))
    $bPerm += (($perms & 0x0010) ? 1: 0);
if ($userinfo['uid'] == fileowner($_file))
    $bPerm += (($perms & 0x0080) ? 1: 0);
return (($bPerm > 0) ? 1 : 0);
}




$filename = '/var/backups/jeedom/backup-neurall-2.4.6-2016-11-09-04h22.tar.gz';
echo '*'.isUserRead($filename).'*'.
 isUserWrite($filename).'*';
/*
$user = fileowner($filename);
$group = filegroup($filename);

$userinfo = posix_getpwuid($user);
$groupinfo = posix_getpwuid($group);

echo '<pre>'.print_r($userinfo, true).'</pre>';

$perms = fileperms($filename);
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
            (($perms & 0x0800) ? 's' : 'x' ) :
            (($perms & 0x0800) ? 'S' : '-'));
$info .= ' ';
// Groupe
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
            (($perms & 0x0400) ? 's' : 'x' ) :
            (($perms & 0x0400) ? 'S' : '-'));
$info .= ' ';
// Tout le monde
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
            (($perms & 0x0200) ? 't' : 'x' ) :
            (($perms & 0x0200) ? 'T' : '-'));

echo "This script is run with " . $userinfo["name"] . "'s privileges.";
echo '<pre>'.print_r($userinfo, true).'</pre>';

echo $info;
 */



?>