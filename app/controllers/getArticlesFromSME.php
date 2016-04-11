<?php

include('./app/controllers/configRSS.php');

class GetArticlesFromSME
{
	const ARTICLE_ID_REGEXP = '/(\/c|\/r-rss)\/(?<article_id>[0-9]+)\//';

	public function run()
	{
		#$this->initialize();
                global $config;

		foreach ($config["feeds"] as $feed)
		{
			if (($feed = file_get_contents($feed)) === FALSE)
			{
				echo "chyba, nedal sa stiahnut\n";
				continue;
			}
		
			$feed = simplexml_load_string($feed);
		
			foreach ($feed->channel->item as $article)
			{
				$articles[] = $article;
			}
		}
                return $articles;
	}
}
?>