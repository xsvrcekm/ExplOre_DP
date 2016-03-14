<?php
/**
 * Konfiguacny subor s nastaveniami specifickymi pre projekt.
 *
 * Vseobecne nastavenia:
 *
 * validate_email_strictly 	Ma sa email overovat cez platnost DNS zaznamu
 * 							domeny? Toto nastavenie ma vplyv iba ak je
 * 							aplikacia v inom stave ako development.
 */

$config = [
	'name' 						=> 'pollster',

	'site_name' 				=> 'pollster',
	'site_description' 			=> '',
	'site_keywords' 			=> '',

	'support_email' 			=> '',
	'support_phone' 			=> '',

	'validate_email_strictly' 	=> FALSE,

	'feeds' 					=> [
		'http://rss.sme.sk/rss/rss.asp?id=frontpage',
		'http://rss.sme.sk/rss/rss.asp?id=smenajcit4',
		'http://rss.sme.sk/rss/rss.asp?id=smenajcit24',
		'http://rss.sme.sk/rss/rss.asp?sek=smeonline&rub=online_zdom',
		'http://rss.sme.sk/rss/rss.asp?sek=smeonline&rub=online_zahr',
		'http://rss.sme.sk/rss/rss.asp?sek=ekon',
		'http://rss.sme.sk/rss/rss.asp?sek=kult',
		'http://rss.sme.sk/rss/rss.asp?sek=romov',
		'http://rss.sme.sk/rss/rss.asp?sek=koment',
		'http://rss.sme.sk/rss/rss.asp?sek=sport',
		'http://rss.sme.sk/rss/rss.asp?sek=sport&rub=sport_futbal',
		'http://rss.sme.sk/rss/rss.asp?sek=sport&rub=sport_hokej',
		'http://rss.sme.sk/rss/rss.asp?sek=tech',
		'http://rss.sme.sk/rss/rss.asp?sek=auto',
		'http://rss.sme.sk/rss/rss.asp?sek=domac',
		'http://rss.sme.sk/rss/rss.asp?sek=zena',
		'http://rss.sme.sk/rss/rss.asp?sek=primar',
		'http://rss.sme.sk/rss/rss.asp?sek=cest'
	]
];
