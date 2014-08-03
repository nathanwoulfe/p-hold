<?php
    error_reporting(0);
    $api_key = "501e9de949f3e5b3090fab7f9a0eb48b"; 
    if ($_SERVER['QUERY_STRING'] != NULL) {
            $qs = $_SERVER['QUERY_STRING'];   
            parse_str($qs, $d);   
            $id = $d[i];
            $url = 'https://api.flickr.com/services/rest/?method=flickr.photos.getInfo&api_key='.$api_key.'&photo_id='.$id.'&format=json&nojsoncallback=1';            $response = json_decode(file_get_contents($url));            $photo = $response->photo;
            $image_url = 'http://farm'.$photo->farm.'.staticflickr.com/'.$photo->server.'/'.$photo->id.'_'.$photo->secret.'_z.jpg';
            $url = 'https://api.flickr.com/services/rest/?method=flickr.people.getInfo&api_key='.$api_key.'&user_id='.$photo->owner->nsid.'&format=json&nojsoncallback=1';            $response = json_decode(file_get_contents($url));
            $user = $response->person;            $username = '';            if ($user->realname->_content != '') {                $username = '('.$user->realname->_content.')';            }            
            $by = 'Attribution License (CC BY 2.0)';
            $by_url = 'http://creativecommons.org/licenses/by/2.0/';
            $by_sa = 'Attribution-ShareAlike License (CC BY-SA 2.0)';
            $by_sa_url = 'http://creativecommons.org/licenses/by-sa/2.0/';
            $license = $by;
            $license_url = $by_url;
            if ($photo->license == '5') {
                $license = $by_sa;
                $license_url = $by_sa_url;
            }
     }
?>
<!DOCTYPE html>
    <!--
        Twenty 1.0 by HTML5 UP
        html5up.net | @n33co        Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
    -->
<html>
    <head>
        <?php include_once('head.php') ?>
    </head>
    <body class="index loading single">
        <section id="banner">
            <div class="inner">
                <a href="<?= $photo->urls->url[0]->_content ?>">
                    <img src="<?= $image_url ?>" alt="<?= $photo->title->_content ?>" />
                </a>
                <header>
                    <h2><?= $photo->title->_content ?></h2>
                </header>
                <p>Photographer: <?= $user->username->_content ?> <?= $username ?></p>
                <p class="more-photos"><a href="<?= $user->photos->_content ?>">More photos</a> | <a href="<?= $user->profileurl->_content ?>">Profile</a></p>            </div>        </section>
        <article id="main">
            <header class="special container">
                <p>The above image has been used in line with the terms of the Creative Commons <?= $license ?>.</p>                <p>For more information about the license permissions, refer to the <a href="<?= $license_url ?>">license description</a> on the <a href="http://creativecommons.org">Creative Commons website</a>.</p>            </header>
        </article>        <?php include_once('foot.php') ?>
        <?php include_once('ga.php') ?>
    </body>
</html>