=== WeRo Core ===
Contributors: wero
Tags: blocks, custom post types, acf, seo, abilities
Requires at least: 6.9
Tested up to: 7.1
Requires PHP: 8.2
Stable tag: 0.2.1
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

= 0.2.1 =
* Compatibility with older browsers: block scripts compiled for Safari 12+ / Chrome 70+, logo row measured in pixels (no container units) and white logos without canvas filters (Safari), aspect-ratio fallbacks for the video player and cards.

= 0.2.0 =
* Report download page: Contact Form 7 form with automatic list of downloadable reports, PDF links on the page and by email, notification to the site owner.
* Report timeline buttons lead to the download page with the report preselected.
* Home "Voices" section removed (now video projects): group style, REST route and schema removed.

= 0.1.0 =
* First release.
