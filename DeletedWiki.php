<?php

use MediaWiki\MediaWikiServices;
use Miraheze\CreateWiki\CreateWikiJson;

global $wgDBname, $wgLocalDatabases;

if ( MW_ENTRY_POINT !== 'cli' ) {
	require_once __DIR__ . '/getTranslations.php';

	$getLanguageCode = 'getLanguageCode';
	$getTranslation = 'getTranslation';

	header( 'Cache-control: no-cache' );

	http_response_code( 410 );

	$output = <<<EOF

		<!DOCTYPE html>
		<html lang="en">
			<head>
				<meta charset="utf-8" />
				<meta name="viewport" content="width=device-width, initial-scale=1.0" />
				<meta name="description" content="{$getTranslation( 'deletedwiki' )}" />
				<title>{$getTranslation( 'deletedwiki' )}</title>
				<link rel="icon" type="image/x-icon" href="https://meta.kyoikuportal.com/favicon.ico" />
				<link rel="apple-touch-icon" href="https://meta.miraheze.org/apple-touch-icon.png" />
				<!-- Bootstrap core CSS -->
				<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
				<!-- Outfit font from Google Fonts -->
				<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit">
				<link href="/ErrorPages/assets/main.css" rel="stylesheet">
			</head>
			<div class="container" style="padding: 70px 0; text-align: center;">
				<!-- Jumbotron -->
				<div class="jumbotron">
					<p style="font-align: center; animation: fadein 1s;">
						<img src="https://meta.kyoikuportal.com/images/metawiki/d/df/%E6%95%99%E7%A7%91%E6%9B%B8.png" alt="教育ポータル" 
						style="width: 200px; height: 200px;">
					</p>
					<h1><b>{$getTranslation( 'deletedwiki' )}</b></h1>
					<p class="lead">{$getTranslation( 'deletedwiki-body' )}</p>
					<p>
						<a href="https://meta.miraheze.org/wiki/Special:MyLanguage/Deleted_wikis" class="btn btn-lg btn-outline-primary" role="button">{$getTranslation( 'page-not-found-learnmore' )}</a>
					</p>
				</div>
			</div>
			<div class="bottom-links">
				<a href="#" onClick="history.go(-1); return false;">&larr; {$getTranslation( 'wiki-not-found-goback' )}</a>
				<a href="https://meta.kyoikuportal.com">教育ポータル</a>
				<a href="https://meta.kyoikuportal.com/wiki/Special:WikiDiscover">{$getTranslation(
					'wiki-directory' )} &rarr;</a>
			</div>
		</html>
	EOF;
	header( 'Content-length: ' . strlen( $output ) );
	echo $output;

	if ( in_array( $wgDBname, $wgLocalDatabases ) ) {
		MediaWikiServices::allowGlobalInstance();
		$createWikiHookRunner = MediaWikiServices::getInstance()->get( 'CreateWikiHookRunner' );
		$cWJ = new CreateWikiJson( $wgDBname, $createWikiHookRunner );
		$cWJ->update();
	}

	die( 1 );
} else {
	// $wgDBname will always be set to a string, even if the --wiki parameter was not passed to a script.
	echo "The wiki database '{$wgDBname}' was not found." . PHP_EOL;
}
