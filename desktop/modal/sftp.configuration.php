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

if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div class="form-group">
    <label class="col-sm-3 control-label">{{Serveur}}</label>
    <div class="col-sm-4">
        <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="server"/>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">{{Port}}</label>
    <div class="col-sm-4">
        <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="port" value="22" />
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">{{Nom d'utilisateur}}</label>
    <div class="col-sm-4">
        <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="username"/>
    </div>
</div>
<div class="form-group">
    <label class="col-sm-3 control-label">{{Mot de passe}}</label>
    <div class="col-sm-4">
        <input type="password" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password"/>
    </div>
</div>

