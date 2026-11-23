=== WeRo Core ===
Contributors: wero
Tags: blocks, custom post types, acf, seo, abilities
Requires at least: 6.9
Tested up to: 7.1
Requires PHP: 8.2
Stable tag: 0.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Content structure, fields, blocks, settings and SEO for the WeRo website, independent of the theme.

== Description ==

WeRo Core holds everything that must survive a theme change:

* Custom post types (Project, Report, Partner, Person) and the World taxonomy.
* Advanced Custom Fields (free version) field groups stored as JSON in the acf-json folder.
* 14 dynamic blocks: video hero, project sphere, report timeline, partner logos, player, chapters and more.
* "Worlds" and "Voices" block styles for Home sections built with core blocks.
* Settings › WeRo page: organization data, global colors and font, animations, partner form.
* Block Bindings sources wero/site and wero/field.
* Yoast SEO schema extensions: Organization, VideoObject, PodcastEpisode.
* Read-only REST routes (wero/v1) and four Abilities API abilities for AI agents.

Advanced Custom Fields is needed to edit the fields. Yoast SEO, Contact Form 7, Site Mailer, Cookie Consent and Image Optimizer are used by the site but are not required for the plugin to run.

== Installation ==

1. Upload the wero-core folder to wp-content/plugins.
2. Activate the plugin.
3. Open Settings › WeRo and review the data.

== Changelog ==

= 0.1.0 =
* First release.
