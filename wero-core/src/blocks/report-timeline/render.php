<?php
/**
 * Blocco wero/report-timeline.
 *
 * @var array $attributes Attributi.
 *
 * @package WeroCore
 */

defined( 'ABSPATH' ) || exit;

use Wero\Core\Blocks;
use Wero\Core\Fields;
use Wero\Core\Taxonomies;

$wero_reports = get_posts(
	array(
		'post_type'      => 'report',
		'post_status'    => 'publish',
		'posts_per_page' => 30,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'ASC',
		),
		'no_found_rows'  => true,
	)
);
if ( ! $wero_reports ) {
	return;
}
usort(
	$wero_reports,
	static function ( WP_Post $a, WP_Post $b ): int {
		$by_year = (int) Fields::post( $a->ID, 'anno', 0 ) <=> (int) Fields::post( $b->ID, 'anno', 0 );
		return 0 !== $by_year ? $by_year : ( $a->menu_order <=> $b->menu_order );
	}
);
$wero_total = count( $wero_reports );
$wero_class = 'wero-rtl-block' . ( empty( $attributes['sticky'] ) ? ' is-static' : '' );
?>
<div <?php echo get_block_wrapper_attributes( array( 'class' => $wero_class ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>>
	<div class="rtl" id="rtl">
		<div class="rtl__sticky">
			<div class="rtl__line" aria-hidden="true"><span class="rtl__progress"></span></div>
			<div class="rtl__nav">
				<button type="button" class="round" id="rtlPrev" aria-label="<?php esc_attr_e( 'Report precedente', 'wero-core' ); ?>">←</button>
				<span class="rtl__count" id="rtlCount" aria-live="polite">
					<?php
					/* translators: %d: numero dei report */
					echo esc_html( sprintf( __( '1 di %d', 'wero-core' ), $wero_total ) );
					?>
				</span>
				<button type="button" class="round" id="rtlNext" aria-label="<?php esc_attr_e( 'Report successivo', 'wero-core' ); ?>">→</button>
			</div>
			<ol class="rtl__track" aria-label="<?php esc_attr_e( 'Timeline dei report', 'wero-core' ); ?>">
				<?php
				foreach ( $wero_reports as $wero_i => $wero_r ) :
					$wero_year   = (string) Fields::post( $wero_r->ID, 'anno' );
					$wero_state  = (string) Fields::post( $wero_r->ID, 'stato', 'pubblicato' );
					$wero_next   = 'in-arrivo' === $wero_state;
					$wero_world  = Taxonomies::world_of( $wero_r->ID );
					$wero_pdf    = Fields::file_url( Fields::post( $wero_r->ID, 'pdf', 0 ) );
					$wero_label  = (string) Fields::post( $wero_r->ID, 'testo_pulsante', $wero_next ? __( 'Avvisami', 'wero-core' ) : __( 'Scarica il PDF', 'wero-core' ) );
					$wero_link   = (string) Fields::post( $wero_r->ID, 'link_pulsante', '/#partner' );
					$wero_proj   = Fields::relation( $wero_r->ID, 'progetto' );
					$wero_cover  = get_the_post_thumbnail(
						$wero_r,
						'large',
						array(
							'class' => 'rtl__img',
							'alt'   => '',
						)
					);
					$wero_cclass = $wero_next ? 'report__cover--next' : ( 0 === $wero_i % 2 ? 'report__cover--a' : 'report__cover--b' );
					?>
					<li class="rtl__item">
						<span class="rtl__dot" aria-hidden="true"></span>
						<h3 class="rtl__title">
						<?php
						if ( $wero_year ) :
							?>
							<time datetime="<?php echo esc_attr( $wero_year ); ?>"><?php echo esc_html( $wero_year ); ?></time> <?php endif; ?><?php echo esc_html( get_the_title( $wero_r ) ); ?></h3>
						<div class="rtl__body">
							<div class="rtl__cover report__cover <?php echo esc_attr( $wero_cclass ); ?>" aria-hidden="true">
								<?php if ( $wero_cover ) : ?>
									<?php echo $wero_cover; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<?php else : ?>
									<img src="<?php echo esc_url( Blocks::mark_url() ); ?>" alt="" loading="lazy"><span><?php echo esc_html( get_the_title( $wero_r ) ); ?></span>
								<?php endif; ?>
							</div>
							<div class="rtl__text">
								<p class="rtl__meta"><?php echo Blocks::world_tag( $wero_world, 'tag-mini' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo $wero_next ? esc_html__( 'In arrivo', 'wero-core' ) : esc_html__( 'Report', 'wero-core' ); ?></p>
								<p class="rtl__desc"><?php echo esc_html( (string) Fields::post( $wero_r->ID, 'descrizione' ) ); ?></p>
								<div class="rtl__actions report">
									<?php if ( $wero_next ) : ?>
										<a class="btn btn--line" href="<?php echo esc_url( $wero_link ); ?>"><?php echo esc_html( $wero_label ); ?></a>
									<?php else : ?>
										<a class="btn btn--grad" href="<?php echo $wero_pdf ? esc_url( $wero_pdf ) : '#'; ?>" download><?php echo esc_html( $wero_label ); ?></a>
									<?php endif; ?>
									<?php if ( $wero_proj ) : ?>
										<a class="report__more" href="<?php echo esc_url( get_permalink( $wero_proj[0] ) ); ?>"><?php esc_html_e( 'Scopri il progetto', 'wero-core' ); ?></a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</div>
</div>
