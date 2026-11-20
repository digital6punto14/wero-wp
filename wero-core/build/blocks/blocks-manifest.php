<?php
// This file is generated. Do not modify it manually.
return array(
	'chapter' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/chapter',
		'version' => '0.1.0',
		'category' => 'wero',
		'textdomain' => 'wero-core',
		'title' => 'Capitolo',
		'icon' => 'clock',
		'parent' => array(
			'wero/chapters'
		),
		'description' => 'Un capitolo del video: tempo (mm:ss) e titolo.',
		'attributes' => array(
			'time' => array(
				'type' => 'string',
				'default' => '00:00'
			),
			'seconds' => array(
				'type' => 'number',
				'default' => 0
			),
			'label' => array(
				'type' => 'string',
				'default' => ''
			)
		),
		'supports' => array(
			'html' => false,
			'reusable' => false
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php'
	),
	'chapters' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/chapters',
		'version' => '0.1.0',
		'category' => 'wero',
		'textdomain' => 'wero-core',
		'title' => 'Capitoli del video',
		'icon' => 'editor-ol',
		'description' => 'Elenco dei capitoli: un clic porta il video a quel punto. Aggiungi un blocco “Capitolo” per ogni voce.',
		'allowedBlocks' => array(
			'wero/chapter'
		),
		'supports' => array(
			'html' => false
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php'
	),
	'hero-video' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/hero-video',
		'version' => '0.1.0',
		'title' => 'Hero con video',
		'category' => 'wero',
		'icon' => 'format-video',
		'description' => 'Copertina a tutto schermo con titolo, testo e pulsanti. Il video (file, YouTube o Vimeo) parte solo al clic.',
		'keywords' => array(
			'hero',
			'video',
			'copertina'
		),
		'textdomain' => 'wero-core',
		'attributes' => array(
			'poster' => array(
				'type' => 'string',
				'default' => ''
			),
			'posterId' => array(
				'type' => 'number',
				'default' => 0
			),
			'mediaUrl' => array(
				'type' => 'string',
				'default' => ''
			),
			'mediaId' => array(
				'type' => 'number',
				'default' => 0
			),
			'videoLabel' => array(
				'type' => 'string',
				'default' => 'The future of robotics'
			)
		),
		'supports' => array(
			'html' => false,
			'align' => array(
				'full'
			),
			'anchor' => true
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'intro' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/intro',
		'version' => '0.1.0',
		'title' => 'Intro del logo',
		'category' => 'wero',
		'icon' => 'controls-play',
		'description' => 'Animazione del logo WeRo all\'apertura della home (5 secondi, con “Salta”). Si attiva e disattiva da Impostazioni › WeRo › Animazioni.',
		'keywords' => array(
			'intro',
			'logo',
			'animazione'
		),
		'textdomain' => 'wero-core',
		'supports' => array(
			'html' => false,
			'multiple' => false,
			'reusable' => false,
			'inserter' => false
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'listen-links' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/listen-links',
		'version' => '0.1.0',
		'category' => 'wero',
		'textdomain' => 'wero-core',
		'usesContext' => array(
			'postId',
			'postType'
		),
		'title' => 'Ascolta su',
		'icon' => 'share',
		'description' => 'Link alle piattaforme del podcast (campi Spotify, Apple Podcasts, YouTube, altra).',
		'supports' => array(
			'html' => false
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php'
	),
	'logo-row' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/logo-row',
		'version' => '0.1.0',
		'title' => 'Riga curva dei loghi',
		'category' => 'wero',
		'icon' => 'images-alt2',
		'description' => 'I loghi dei Partner (non founder) scorrono su una riga curva, con l\'etichetta Business o Ricerca. Per aggiungere un logo basta creare un Partner.',
		'keywords' => array(
			'loghi',
			'partner',
			'collaboratori',
			'slider'
		),
		'textdomain' => 'wero-core',
		'attributes' => array(
			'speed' => array(
				'type' => 'number',
				'default' => 3.2
			),
			'curve' => array(
				'type' => 'number',
				'default' => 72
			),
			'tileHeight' => array(
				'type' => 'number',
				'default' => 34
			),
			'showLegend' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'supports' => array(
			'html' => false,
			'align' => array(
				'wide',
				'full'
			)
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'partners' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/partners',
		'version' => '0.1.0',
		'title' => 'Loghi dei partner',
		'category' => 'wero',
		'icon' => 'groups',
		'description' => 'Loghi dei Partner con scheda (nome, descrizione, link) che si apre al passaggio o al tocco. I dati si modificano in Partner.',
		'keywords' => array(
			'partner',
			'founder',
			'loghi'
		),
		'textdomain' => 'wero-core',
		'attributes' => array(
			'group' => array(
				'type' => 'string',
				'enum' => array(
					'founder',
					'business',
					'ricerca',
					'all'
				),
				'default' => 'founder'
			)
		),
		'supports' => array(
			'html' => false,
			'align' => array(
				'wide',
				'full'
			)
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'people' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/people',
		'version' => '0.1.0',
		'category' => 'wero',
		'textdomain' => 'wero-core',
		'usesContext' => array(
			'postId',
			'postType'
		),
		'title' => 'Persone',
		'icon' => 'admin-users',
		'description' => 'Ospiti e relatori collegati al progetto (campo “Persone”), con il loro titolo. Se il progetto non ha persone, il blocco non compare.',
		'supports' => array(
			'html' => false
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'attributes' => array(
			'heading' => array(
				'type' => 'string',
				'default' => 'Intervengono'
			)
		)
	),
	'podcast-cover' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/podcast-cover',
		'version' => '0.1.0',
		'category' => 'wero',
		'textdomain' => 'wero-core',
		'usesContext' => array(
			'postId',
			'postType'
		),
		'title' => 'Copertina del podcast',
		'icon' => 'microphone',
		'description' => 'Copertina a sfumatura con logo, etichetta dell’episodio e titolo. Se il progetto ha un’immagine della card, si usa quella.',
		'supports' => array(
			'html' => false
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php'
	),
	'project-facts' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/project-facts',
		'version' => '0.1.0',
		'category' => 'wero',
		'textdomain' => 'wero-core',
		'usesContext' => array(
			'postId',
			'postType'
		),
		'title' => 'Scheda del progetto',
		'icon' => 'list-view',
		'description' => 'Scheda laterale: Mondo, Tipo, Anno, Con. Si compila dai campi del progetto.',
		'supports' => array(
			'html' => false
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php'
	),
	'project-media' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/project-media',
		'version' => '0.1.0',
		'category' => 'wero',
		'textdomain' => 'wero-core',
		'usesContext' => array(
			'postId',
			'postType'
		),
		'title' => 'Player del progetto',
		'icon' => 'controls-play',
		'description' => 'Video o audio del progetto dal campo “Link del video o dell’audio” (file, YouTube, Vimeo, Spotify, SoundCloud, Apple Podcasts). I servizi esterni si caricano solo al clic.',
		'attributes' => array(
			'mode' => array(
				'type' => 'string',
				'enum' => array(
					'video',
					'audio'
				),
				'default' => 'video'
			)
		),
		'supports' => array(
			'html' => false
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php',
		'viewScript' => 'file:./view.js'
	),
	'project-meta' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/project-meta',
		'version' => '0.1.0',
		'category' => 'wero',
		'textdomain' => 'wero-core',
		'usesContext' => array(
			'postId',
			'postType'
		),
		'title' => 'Meta del progetto',
		'icon' => 'tag',
		'description' => 'Mondo, tipo e anno del progetto. Variante “barra” con il link “Tutti i progetti” (testata) o “card” (liste).',
		'attributes' => array(
			'variant' => array(
				'type' => 'string',
				'enum' => array(
					'bar',
					'card'
				),
				'default' => 'bar'
			)
		),
		'supports' => array(
			'html' => false
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php'
	),
	'projects-explorer' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/projects-explorer',
		'version' => '0.1.0',
		'title' => 'Sfera dei progetti',
		'category' => 'wero',
		'icon' => 'admin-site-alt3',
		'description' => 'Sfera 3D dei progetti con filtri per mondo, pannello informativo, lista paginata e vista a griglia. I progetti arrivano dal tipo di contenuto Progetti.',
		'keywords' => array(
			'progetti',
			'sfera',
			'portfolio'
		),
		'textdomain' => 'wero-core',
		'attributes' => array(
			'perPage' => array(
				'type' => 'number',
				'default' => 5
			),
			'spin' => array(
				'type' => 'boolean',
				'default' => true
			),
			'max' => array(
				'type' => 'number',
				'default' => 40
			)
		),
		'supports' => array(
			'html' => false,
			'multiple' => false,
			'align' => array(
				'wide'
			)
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'report-timeline' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/report-timeline',
		'version' => '0.1.0',
		'title' => 'Timeline dei report',
		'category' => 'wero',
		'icon' => 'media-document',
		'description' => 'Timeline orizzontale dei Report: su desktop scorre con la pagina, su mobile con il dito. I report si modificano nel menu Report.',
		'keywords' => array(
			'report',
			'timeline',
			'pdf'
		),
		'textdomain' => 'wero-core',
		'attributes' => array(
			'sticky' => array(
				'type' => 'boolean',
				'default' => true
			)
		),
		'supports' => array(
			'html' => false,
			'multiple' => false,
			'align' => array(
				'full'
			)
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'voices' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/voices',
		'version' => '0.1.0',
		'title' => 'Le voci dei progetti',
		'category' => 'wero',
		'icon' => 'video-alt3',
		'description' => 'Video della sezione “Le voci dei progetti” (copertina, titolo, testo). Il video parte al clic. Le voci si modificano nel menu Voci dei progetti.',
		'keywords' => array(
			'video',
			'voci'
		),
		'textdomain' => 'wero-core',
		'attributes' => array(
			'max' => array(
				'type' => 'number',
				'default' => 3
			)
		),
		'supports' => array(
			'html' => false,
			'align' => array(
				'wide'
			)
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	),
	'worlds' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'wero/worlds',
		'version' => '0.1.0',
		'title' => 'I tre mondi',
		'category' => 'wero',
		'icon' => 'columns',
		'description' => 'Card dei mondi (immagine, titolo, testo, link che filtra i progetti). I contenuti si modificano in Progetti › Mondi. Su mobile scorrono in orizzontale.',
		'keywords' => array(
			'mondi',
			'card'
		),
		'textdomain' => 'wero-core',
		'supports' => array(
			'html' => false,
			'align' => array(
				'wide'
			)
		),
		'editorScript' => 'file:./index.js',
		'style' => 'file:./style-index.css',
		'viewScript' => 'file:./view.js',
		'render' => 'file:./render.php'
	)
);
