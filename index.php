<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Afficher les dernières publications d'une page Facebook sur son site</title>
</head>
<body>

	<h1>Afficher les dernières publications d'une page Facebook sur son site</h1>


	<?php
	$nbr_posts_max = 6;
	$id_page_fb = '325923990763881';
	$token = '104209816294440|xVZhfKIU2_TsCnsimmUoaM0YU68';

                    //recupération derniers posts
	$contenu_fichier_json = file_get_contents('https://graph.facebook.com/v3.0/'.$id_page_fb.'/?fields=id%2Cfeed.limit('.$nbr_posts_max.')%7Bpermalink_url%2Ccreated_time%2Cfull_picture%2Cactions%2Cdescription%2Cmessage%7D&access_token='.$token.'');
	$tr = json_decode($contenu_fichier_json, true);
	$lg_max = 200;
	$fin = ' ...';

	foreach ($tr['feed']['data'] as $i => $post):

        //traduction date en fr
		setlocale(LC_TIME, 'french');
		$date_post = ucfirst(strftime('%A %d %B %Y', strtotime($post['created_time'])));

        //description
		if (isset($post['description']) && strlen($post['description'])>$lg_max)
			$description=substr($post['description'], 0, $lg_max-strlen($fin)).$fin;
		else if (isset($post['description']) && strlen($post['description'])<=$lg_max)
			$description = $post['description'];
		else
			$description = "";

		if (isset($post['message']) && strlen($post['message'])>$lg_max)
			$message=substr($post['message'], 0, $lg_max-strlen($fin)).$fin;
		else if (isset($post['message']) && strlen($post['message'])<=$lg_max)
			$message = $post['message'];
		else
			$message = "";

		if(!isset($post['permalink_url']))
			$permalink_url = "https://www.facebook.com/".$id_page_fb;
		else
			$permalink_url = $post['permalink_url'];

		if($description!=="" || $message!=="" || isset($post['full_picture'])):
			?>


			<div class="event">
				<div class="event_image">
					<img src="<?php echo $post['full_picture']; ?>" alt="">
				</div>

				<div class="event_title">
					<a target="_blank" href="<?php echo $permalink_url; ?>"><?php echo utf8_encode($date_post); ?></a>
				</div>

				<div class="event_desc">
					<p>
						<?php 
						echo $message;
						if($message !="" && $description != ""){echo "<br>";}
						echo $description ?>
					</p>
					<a target="_blank" href="<?php echo $permalink_url; ?>">Lire la suite</a>
				</div>


			</div>

		<?php endif;
	endforeach; ?>

</body>
</html>