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

function local_send($_eqLogic, $_source, $_cible, $_file) {
    if(!file_exists($_cible) || !is_dir($_cible)){
        throw new Exception(__('Répertoire cible innexistant : ',__FILE__).$_cible);        
    }
    
    if (isUserRead($_source.'/'.$_file) == 0)
        throw new Exception(__('La copie de : ',__FILE__).$_source . '/' . $_file.__(' vers : ',__FILE__).$_cible.'/'.$_file.__("a échoué, il n'y a pas de droits en lecture sur la source",__FILE__));
    if (isUserWrite($_cible) == 0)
        throw new Exception(__('La copie de : ',__FILE__).$_source . '/' . $_file.__(' vers : ',__FILE__).$_cible.'/'.$_file.__("a échoué, il n'y a pas de droits en écriture sur le répertoire cible",__FILE__));
    if (file_exists($_cible.'/'.$_file))
       if (isUserWrite($_cible.'/'.$_file) == 0)
           throw new Exception(__('La copie de : ',__FILE__).$_source . '/' . $_file.__(' vers : ',__FILE__).$_cible.'/'.$_file.__("a échoué, il n'y a pas de droits en écriture sur le fichier cible, effacez le manuellement avant",__FILE__));
    if(!copy($_source . '/' . $_file,$_cible.'/'.$_file)) {
        $errors= error_get_last();
        throw new Exception(__('La copie de : ',__FILE__).$_source . '/' . $_file.__(' vers : ',__FILE__).$_cible.'/'.$_file.__("a échoué",__FILE__).' '.$errors['type'].' '.$errors['message']);
    }
}
