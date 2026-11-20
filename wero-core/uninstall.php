<?php
/**
 * Disinstallazione di WeRo Core.
 *
 * Elimina solo le Impostazioni sito. I contenuti (progetti, report, partner,
 * voci, persone, mondi) e i loro campi restano nel database: se il plugin
 * viene reinstallato, tornano visibili.
 *
 * @package WeroCore
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

delete_option( 'wero_settings' );
