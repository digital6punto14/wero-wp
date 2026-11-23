<?php
/**
 * Blocco wero/projects-explorer: sfera, pannello, filtri, lista e griglia.
 *
 * La lista #projectList è il contenuto vero (senza JavaScript, per screen reader,
 * motori di ricerca e agenti AI); la sfera e le viste ne sono una rappresentazione.
 *
 * @var array  $attributes Attributi.
 * @var string $content    Titolo (blocco interno).
 *
 * @package WeroCore
 */

defined( 'ABSPATH' ) || exit;

use Wero\Core\Fields;
use Wero\Core\Taxonomies;

$wero_projects = get_posts(
	array(
		'post_type'      => 'progetto',
		'post_status'    => 'publish',
		'posts_per_page' => max( 1, (int) ( $attributes['max'] ?? 40 ) ),
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'no_found_rows'  => true,
	)
);
$wero_worlds   = Taxonomies::worlds();
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => 'wero-explorer' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
		<defs>
			<linearGradient id="weroIco" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#ff3f7a"/><stop offset="1" stop-color="#ff00c7"/></linearGradient>
			<radialGradient id="icoBody" cx=".32" cy=".22" r="1"><stop offset="0" stop-color="#fff"/><stop offset=".45" stop-color="#ffe4ee"/><stop offset="1" stop-color="#ff9fc0"/></radialGradient>
			<linearGradient id="icoPart" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ff6f9c"/><stop offset="1" stop-color="#ff2e78"/></linearGradient>
			<linearGradient id="icoScreen" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#ff4a7d"/><stop offset=".6" stop-color="#ff1aa8"/><stop offset="1" stop-color="#e800b8"/></linearGradient>
			<linearGradient id="icoGloss" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#fff" stop-opacity=".55"/><stop offset=".55" stop-color="#fff" stop-opacity="0"/></linearGradient>
			<filter id="icoGlow" x="-50%" y="-50%" width="200%" height="200%"><feGaussianBlur stdDeviation="1.6" result="b"/><feMerge><feMergeNode in="b"/><feMergeNode in="SourceGraphic"/></feMerge></filter>
			<filter id="icoShadow" x="-50%" y="-200%" width="200%" height="500%"><feGaussianBlur stdDeviation="2.4"/></filter>
		</defs>
	</svg>

	<div class="projects__head">
		<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- titolo (blocco interno). ?>
		<div class="filters" role="group" aria-label="<?php esc_attr_e( 'Filtra per mondo', 'wero-core' ); ?>">
			<button type="button" class="chip is-on" data-filter="all" aria-pressed="true"><?php esc_html_e( 'Tutti', 'wero-core' ); ?></button>
			<?php foreach ( $wero_worlds as $wero_w ) : ?>
				<button type="button" class="chip" data-filter="<?php echo esc_attr( $wero_w->slug ); ?>" aria-pressed="false"><?php echo esc_html( $wero_w->name ); ?></button>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="projects">
		<div class="sphere" id="sphere" aria-hidden="true" data-spin="<?php echo ! empty( $attributes['spin'] ) ? '1' : '0'; ?>"></div>
		<aside class="pinfo" id="pinfo" aria-label="<?php esc_attr_e( 'Progetto in primo piano', 'wero-core' ); ?>">
			<div class="pinfo__body">
				<p class="pinfo__desc" id="pinfoDesc" aria-live="polite"></p>
				<a class="btn btn--ink pinfo__link" id="pinfoLink" href="#"><?php esc_html_e( 'Scopri il progetto', 'wero-core' ); ?></a>
			</div>
			<div class="pinfo__nav">
				<button type="button" class="round" id="pPrev" aria-label="<?php esc_attr_e( 'Progetto precedente', 'wero-core' ); ?>">←</button>
				<span class="pinfo__count" id="pinfoCount"></span>
				<button type="button" class="round" id="pNext" aria-label="<?php esc_attr_e( 'Progetto successivo', 'wero-core' ); ?>">→</button>
			</div>
		</aside>
	</div>

	<ul class="pgrid" id="pgrid" hidden></ul>

	<div class="pviews">
		<div class="pviews__bar">
			<p class="pviews__count" id="pviewsCount" aria-live="polite"></p>
			<button class="btn btn--line" id="viewToggle" type="button" aria-pressed="false"><?php esc_html_e( 'Guarda come griglia', 'wero-core' ); ?></button>
		</div>
		<ol class="prow-list" id="prowList"></ol>
		<nav class="pager" id="pager" aria-label="<?php esc_attr_e( 'Pagine dei progetti', 'wero-core' ); ?>"></nav>
	</div>

	<ol class="plist sr-only" id="projectList" data-per-page="<?php echo esc_attr( (string) (int) ( $attributes['perPage'] ?? 5 ) ); ?>">
		<?php
		foreach ( $wero_projects as $wero_p ) :
			$wero_world = Taxonomies::world_of( $wero_p->ID );
			$wero_img   = Fields::image_url( Fields::post( $wero_p->ID, 'immagine_card', 0 ), 'medium_large' );
			if ( ! $wero_img ) {
				$wero_img = (string) get_the_post_thumbnail_url( $wero_p, 'medium_large' );
			}
			$wero_type = (string) Fields::post( $wero_p->ID, 'tipo' );
			$wero_year = (string) Fields::post( $wero_p->ID, 'anno' );
			$wero_desc = (string) Fields::post( $wero_p->ID, 'descrizione_breve', get_the_excerpt( $wero_p ) );
			$wero_meta = implode( ', ', array_filter( array( $wero_type, $wero_year, $wero_world ? $wero_world->name : '' ) ) );
			?>
			<li class="plist__item"
				data-world="<?php echo esc_attr( $wero_world ? $wero_world->slug : '' ); ?>"
				data-world-name="<?php echo esc_attr( $wero_world ? $wero_world->name : '' ); ?>"
				data-world-style="<?php echo esc_attr( $wero_world ? (string) Fields::term( $wero_world, 'stile', 'scuro' ) : 'scuro' ); ?>"
				data-type="<?php echo esc_attr( $wero_type ); ?>"
				data-year="<?php echo esc_attr( $wero_year ); ?>"
				data-icon="<?php echo esc_attr( (string) Fields::post( $wero_p->ID, 'icona', 'robot' ) ); ?>"
				<?php echo $wero_img ? 'data-image="' . esc_url( $wero_img ) . '"' : ''; ?>>
				<h3 class="plist__title"><?php echo esc_html( get_the_title( $wero_p ) ); ?></h3>
				<p class="plist__meta"><?php echo esc_html( $wero_meta ); ?></p>
				<p class="plist__desc"><?php echo esc_html( $wero_desc ); ?></p>
				<a class="plist__link" href="<?php echo esc_url( get_permalink( $wero_p ) ); ?>"><?php esc_html_e( 'Scopri il progetto', 'wero-core' ); ?></a>
			</li>
		<?php endforeach; ?>
	</ol>
</div>
