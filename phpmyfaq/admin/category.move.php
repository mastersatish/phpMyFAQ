<?php
/**
 * Select a category to move
 * 
 * PHP Version 5.2
 *
 * The contents of this file are subject to the Mozilla Public License
 * Version 1.1 (the "License"); you may not use this file except in
 * compliance with the License. You may obtain a copy of the License at
 * http://www.mozilla.org/MPL/
 *
 * Software distributed under the License is distributed on an "AS IS"
 * basis, WITHOUT WARRANTY OF ANY KIND, either express or implied. See the
 * License for the specific language governing rights and limitations
 * under the License.
 *
 * @category  phpMyFAQ
 * @package   Administration
 * @author    Thorsten Rinne <thorsten@phpmyfaq.de>
 * @copyright 2004-2010 phpMyFAQ Team
 * @license   http://www.mozilla.org/MPL/MPL-1.1.html Mozilla Public License Version 1.1
 * @link      http://www.phpmyfaq.de
 * @since     2004-04-29
 */

if (!defined('IS_VALID_PHPMYFAQ')) {
    header('Location: http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['SCRIPT_NAME']));
    exit();
}

if ($permission["editcateg"]) {
    
    $categoryNode         = new PMF_Category_Node();
    $categoryDataProvider = new PMF_Category_Tree_DataProvider_SingleQuery($LANGCODE);
    $categoryTreeHelper   = new PMF_Category_Tree_Helper(new PMF_Category_Tree($categoryDataProvider));
    
    $categoryId   = PMF_Filter::filterInput(INPUT_GET, 'cat', FILTER_VALIDATE_INT);
    $parentId     = PMF_Filter::filterInput(INPUT_GET, 'parent_id', FILTER_VALIDATE_INT);
    $categoryData = $categoryNode->fetch($categoryId);
    $header       = sprintf('%s: <em>%s</em>', $PMF_LANG['ad_categ_move'], $categoryData->name);
    
    printf('<h2>%s</h2>', $header);
?>
    <form action="?action=changecategory" method="post">
    <fieldset>
        <legend><?php print $PMF_LANG["ad_categ_change"]; ?></legend>
        <input type="hidden" name="cat" value="<?php print $categoryId; ?>" />
        <input type="hidden" name="csrf" value="<?php print $user->getCsrfTokenFromSession(); ?>" />
        <div class="row">
               <select name="change" size="1">
<?php
    foreach ($categoryTreeHelper as $catId => $categoryName) {
        $parent = $categoryTreeHelper->getInnerIterator()->current()->getParentId();
        if ($categoryId != $catId && $parentId == $parent) {
            printf("        <option value=\"%s\">%s</option>\n", $catId, $categoryName);
        }
    }
?>
        </select>&nbsp;&nbsp;
        <input class="submit" type="submit" name="submit" value="<?php print $PMF_LANG["ad_categ_updatecateg"]; ?>" />
        </div>
    </fieldset>
    </form>
<?php
    printf('<p>%s</p>', $PMF_LANG['ad_categ_remark_move']);
} else {
    print $PMF_LANG["err_NotAuth"];
}