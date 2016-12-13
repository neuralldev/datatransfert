<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';


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
function sftp_send($_eqLogic, $_source, $_cible, $_file) {
    if (isUserRead($_source.'/'.$_file) == 0)
        throw new Exception(__('La copie de : ',__FILE__).$_source . '/' . $_file.__(' vers : ',__FILE__).$_cible.'/'.$_file.__("a échoué, il n'y a pas de droits en lecture sur la source",__FILE__));
    $connection = ssh2_connect($_eqLogic->getConfiguration('server'), $_eqLogic->getConfiguration('port', 22));
    if (!$connection) {
        throw new Exception("Impossible d'ouvrir le port " . $_eqLogic->getConfiguration('port', 22) . " (ssh) du serveur ".$_eqLogic->getConfiguration('server'));
    }
    if (!ssh2_auth_password($connection, $_eqLogic->getConfiguration('username'), $_eqLogic->getConfiguration('password'))) {
        throw new Exception('Authentification ssh impossible avec le nom d\'utilisateur' . $_eqLogic->getConfiguration('username'));
    }
    $sftp = @ssh2_sftp($connection);
    if (!$sftp) {
        throw new Exception("Impossible d\'initialiser le sous-système SFTP");
    }
    $fstream = fopen("ssh2.sftp://$sftp$_cible/$_file", 'w');
    if (!$fstream) {
        throw new Exception('Impossible d\'ouvrir la cible :' . $_cible);
    }
    $data_to_send = @file_get_contents($_source . '/' . $_file);
    if ($data_to_send === false) {
        throw new Exception('Impossible d\'ouvrir le fichier : ' . $_source . '/' . $_file);
    }
    if (@fwrite($fstream, $data_to_send) === false) {
        throw new Exception('Impossible d\'envoyer le fichier : ' . $_source . '/' . $_file);
    }
    @fclose($fstream);
}
