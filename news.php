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
require PUN_ROOT.'lang/'.$pun_user['language'].'/forum.php';

if ($pun_user['is_guest'] == 1){
    message($lang_search['No visit permission'], false, '403 Forbidden');
}

require PUN_ROOT.'header.php';
$today = date('Y-m-d H:i:s');
$userId = $pun_user['id'];
$result = $db->query("SELECT * FROM news_download  WHERE user_id = '$userId'  AND DATE_ADD(created, INTERVAL 1 MONTH) > '$today' LIMIT 1");
$temp = $result->fetch_row();

if (!empty(@$temp)):
    $result = $db->query("SELECT *,DATE_ADD(created,INTERVAL 1 MONTH) as next_download FROM news_download  WHERE user_id = '$userId' ORDER BY created DESC LIMIT 1");
    $temp = $result->fetch_row();
    ?>
<p><?= $lang_forum['You already download the news']; ?>. <?= $lang_forum['Next download available is']; ?> <?= $temp[3]; ?></p>
<?php else: ?>
    <form action="#" method="POST" id="form">
        <input type="hidden" name="id" value="<?= $userId; ?>">
        <button type="submit"><?= $lang_forum['Click here to download']; ?></button>
    </form>
    <script>
        document.getElementById('form').addEventListener('submit',function (event){
            event.preventDefault();
            var xhr = new XMLHttpRequest();
            var url = 'news_download.php'; // Replace with your API endpoint
            xhr.open('POST', url, true);
            xhr.setRequestHeader('Content-Type', 'application/json');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var anchor = document.createElement('a');
                        anchor.setAttribute('href', 'file/news.zip');
                        anchor.setAttribute('download', 'news.zip');
                        anchor.style.display = 'none';
                        document.body.appendChild(anchor);
                        anchor.click();
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    } else {
                        window.location.reload();
                    }
                }
            };
            var data = {
                id: '<?=$userId?>'
            };
            xhr.send(JSON.stringify(data));
        });
    </script>
<?php endif; ?>
<?php
require PUN_ROOT.'footer.php';
?>
