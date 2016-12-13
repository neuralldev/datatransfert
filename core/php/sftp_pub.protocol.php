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

function sftp_send($_eqLogic, $_source, $_cible, $_file) {
    $connection = ssh2_connect($_eqLogic->getConfiguration('server'), $_eqLogic->getConfiguration('port', 22));
    if (!$connection) {
        throw new Exception("Impossible d'ouvrir le port " . $_eqLogic->getConfiguration('port', 22) . " (ssh) du serveur ".$_eqLogic->getConfiguration('server'));
    }
            
    if (!ssh2_auth_pubkey_file($connection, $_eqLogic->getConfiguration('username'), $_eqLogic->getConfiguration('pubkey'), $_eqLogic->getConfiguration('privkey'), '')) 
    {
        throw new Exception('Authentification ssh impossible avec la clé publique/privée fournie');
    }
    $sftp = @ssh2_sftp($connection);
    if (!$sftp) {
        throw new Exception("Impossible d\'initialiser le sous-système SFTP");
    }
    $stream = fopen("ssh2.sftp://$sftp$_cible/$_file", 'w');
    if (!$stream) {
        throw new Exception('Impossible d\'ouvrir la cible :' . $_cible);
    }
    $data_to_send = @file_get_contents($_source . '/' . $_file);
    if ($data_to_send === false) {
        throw new Exception('Impossible d\'ouvrir le fichier : ' . $_source . '/' . $_file);
    }
    if (@fwrite($stream, $data_to_send) === false) {
        throw new Exception('Impossible d\'envoyer le fichier : ' . $_source . '/' . $_file);
    }
    @fclose($stream);
}
