<?php

/**
 * Copyright (C) 2008-2012 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

// The contents of this file are very much inspired by the file search.php
// from the phpBB Group forum software phpBB2 (http://www.phpbb.com)

define('PUN_ROOT', dirname(__FILE__).'/');
require PUN_ROOT.'include/common.php';

// Load the search.php language file
require PUN_ROOT.'lang/'.$pun_user['language'].'/search.php';
require PUN_ROOT.'lang/'.$pun_user['language'].'/forum.php';
if ($pun_user['g_id'] != 1){
    message($lang_search['No visit permission'], false, '403 Forbidden');
}

if ($pun_user['g_read_board'] == '0')
    message($lang_common['No view'], false, '403 Forbidden');
else if ($pun_user['g_search'] == '0')
    message($lang_search['No search permission'], false, '403 Forbidden');
else if ($pun_user['is_bot'] && (isset($_GET['search_id']) || !isset($_GET['action']) || $_GET['action'] == 'search')) // Visman - запрет поиска ботам
    message($lang_search['No search permission'], false, '403 Forbidden');


$page_title = array(pun_htmlspecialchars($pun_config['o_board_title']), $lang_search['Search']);
$focus_element = array('search', 'keywords');
define('PUN_ACTIVE_PAGE', 'search');
require PUN_ROOT.'header.php';

?>
    <div id="searchform" class="blocktable">
        <h2><span><?php echo $lang_search['Page Visit'] ?></span></h2>
        <div class="box">
            <div class="inbox">
                <table>
                    <thead>
                        <tr>
                            <th class="tc1"><?php echo $lang_search['Username'] ?></th>
                            <th class="tc1"><?php echo $lang_search['Visit Url'] ?></th>
                            <th class="tc1"><?php echo $lang_search['Method'] ?></th>
                            <th class="tc1"><?php echo $lang_search['Visit Time'] ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $result = $db->query('SELECT page_visits.*,users.username FROM page_visits INNER JOIN users WHERE users.id = page_visits.user_id ORDER BY page_visits.created DESC');
                            while ($temp = $db->fetch_assoc($result)):
                        ?>
                        <tr>
                            <td class="tc1"><?= $temp['username'] ?></td>
                            <td class="tc1"><?= $temp['url'] ?></td>
                            <td class="tc1"><?= $temp['request_method'] ?></td>
                            <td class="tc1"><?= date('d-m-Y  h:s a',strtotime($temp['created'])) ?></td>
                        </tr>
                        <?php endwhile;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php

require PUN_ROOT.'footer.php';
