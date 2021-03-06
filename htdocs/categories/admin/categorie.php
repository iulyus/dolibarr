<?php
/* Copyright (C) 2008-2011 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2013 		Florian Henry  <florian.henry@open-concept.pro>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	    \file       htdocs/categories/admin/categorie.php
 *      \ingroup    categories
 *      \brief      Categorie admin pages
 */

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/categories.lib.php';


if (!$user->admin)
accessforbidden();

$langs->load("categories");

$action=GETPOST("action");

/*
 *	Actions
 */
if (preg_match('/set_(.*)/',$action,$reg))
{
    $code=$reg[1];
    if (dolibarr_set_const($db, $code, 1, 'chaine', 0, '', $conf->entity) > 0)
    {
        header("Location: ".$_SERVER["PHP_SELF"]);
        exit;
    }
    else
    {
        setEventMessage($db->lasterror(),'errors');
    }
}

if (preg_match('/del_(.*)/',$action,$reg))
{
    $code=$reg[1];
    if (dolibarr_del_const($db, $code, $conf->entity) > 0)
    {
        header("Location: ".$_SERVER["PHP_SELF"]);
        exit;
    }
    else
    {
         setEventMessage($db->lasterror(),'errors');
    }
}



/*
 * View
 */

$help_url='EN:Module Categories|FR:Module Catégories|ES:Módulo Categorías';
$linkback='<a href="'.DOL_URL_ROOT.'/admin/modules.php">'.$langs->trans("BackToModuleList").'</a>';

llxHeader('',$langs->trans("Categories"),$help_url);

print_fiche_titre($langs->trans("CategoriesSetup"),'','setup');


$head=categoriesadmin_prepare_head();

dol_fiche_head($head, 'setup', $langs->trans("Categories"), 0, 'category');


print '<table class="noborder" width="100%">';
print '<tr class="liste_titre">';
print '<td>'.$langs->trans("Description").'</td>';
print '<td align="center" width="20">&nbsp;</td>';
print '<td align="center" width="100">'.$langs->trans("Value").'</td>'."\n";
print '</tr>';

$var=true;
$form = new Form($db);

// Mail required for members
$var=!$var;
print '<tr '.$bc[$var].'>';
print '<td>'.$langs->trans("CategorieRecursiv").'</td>';
print '<td align="center" width="20">'. $form->textwithpicto('',$langs->trans("CategorieRecursivHelp"),1,'help').'</td>';

print '<td align="center" width="100">';
if ($conf->use_javascript_ajax)
{
	print ajax_constantonoff('CATEGORIE_RECURSIV_ADD');
}
else
{
	if($conf->global->CATEGORIE_RECURSIV_ADD == 0)
	{
		print '<a href="'.$_SERVER['PHP_SELF'].'?action=set_CATEGORIE_RECURSIV_ADD">'.img_picto($langs->trans("Disabled"),'off').'</a>';
	}
	else if($conf->global->CATEGORIE_RECURSIV_ADD == 1)
	{
		print '<a href="'.$_SERVER['PHP_SELF'].'?action=del_CATEGORIE_RECURSIV_ADD">'.img_picto($langs->trans("Enabled"),'on').'</a>';
	}
}
print '</td></tr>';

print '</table>';

$db->close();
llxFooter();