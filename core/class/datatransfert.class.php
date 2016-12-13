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
require_once dirname(__FILE__) . '/../../core/php/datatransfert.inc.php';

class datatransfert extends eqLogic {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */

    public static function supportedProtocol() {
        $return = array();
        foreach (ls(dirname(__FILE__) . '/../php', '*.protocol.php') as $file) {
            $protocol = explode('.', $file);
            $return[] = $protocol[0];
        }
        return $return;
    }

    /*     * *********************Methode d'instance************************* */

    /*     * **********************Getteur Setteur*************************** */
}

class datatransfertCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */

    public static function orderFile($a, $b) {
        if ($a['datetime'] == $b['datetime']) {
            return 0;
        }
        return ($a['datetime'] < $b['datetime']) ? +1 : -1;
    }

    /*     * *********************Methode d'instance************************* */

    public function execute($_options = null) {
        $eqLogic = $this->getEqLogic();
        // lecture du protocole de transfert dans la configuration (local/sftp)
        $protocol = $eqLogic->getConfiguration('protocol');
        include_file('core', $protocol . '.protocol', 'php', 'datatransfert'); // chargement de la procédure de transfert adaptée
        $function = $protocol . '_send'; // la fonction d'envoi de fichier s'appelle <protocole>_send
        $cible = $this->getConfiguration('cible');
        $source = calculPath($this->getConfiguration('source'));
        $filelist = array();
        $filter_recentfile = $this->getConfiguration('filter_recentfile');
        if ($this->getConfiguration('filter_recentfile') != '') {
            foreach (ls($source, $this->getConfiguration('filter_file', '*')) as $file) {
                $filelist[] = array(
                    'file' => $file,
                    'datetime' => filemtime($source . '/' . $file)
                );
            }
            usort($filelist, 'datatransfertCmd::orderFile');
            $filelist = array_slice($filelist, 0, $this->getConfiguration('filter_recentfile'));
            foreach ($filelist as $file) {
                $function($eqLogic, $source, $cible, $file['file']);
            }
        } else {
            foreach (ls($source, $this->getConfiguration('filter_file', '*')) as $file) {
                $function($eqLogic, $source, $cible, $file);
            }
        }
    }

    /*     * **********************Getteur Setteur*************************** */
}

?>