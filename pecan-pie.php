<?php
/*
Plugin Name: Pecan Pie
Plugin URI: https://github.com/Duarte-Htag/pecan-pie
Description: Intègre facilement Tarteaucitron.js avec Google Consent Mode.
Version: 1.0
Author: Htag Digital
Author URI: https://htag-digital.fr
License: GPL2

GitHub Plugin URI: https://github.com/Duarte-Htag/pecan-pie
GitHub Branch: main
*/


// Créer le menu d'administration
add_action('admin_menu', function() {
    add_options_page(
        'Pecan Pie - Tarteaucitron', 
        'Pecan Pie', 
        'manage_options', 
        'pecan-pie', 
        'pecan_pie_settings_page'
    );
});

// Enregistrer les options
add_action('admin_init', function() {
    register_setting('pecan_pie_options_group', 'pecan_pie_privacy_url');
    register_setting('pecan_pie_options_group', 'pecan_pie_theme');
});

// Afficher la page d'administration
function pecan_pie_settings_page() {
    ?>
    <div class="wrap">
        <h1>Paramètres de Pecan Pie</h1>
        <form method="post" action="options.php">
            <?php settings_fields('pecan_pie_options_group'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">URL de la politique de confidentialité</th>
                    <td><input type="text" name="pecan_pie_privacy_url" value="<?php echo esc_attr(get_option('pecan_pie_privacy_url', '/politique-de-confidentialite')); ?>" size="60" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Thème du bandeau</th>
                    <td>
                        <select name="pecan_pie_theme">
                            <option value="light" <?php selected(get_option('pecan_pie_theme'), 'light'); ?>>Clair</option>
                            <option value="dark" <?php selected(get_option('pecan_pie_theme'), 'dark'); ?>>Sombre</option>
                        </select>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Injecter le script dans le head
add_action('wp_head', function() {
    $privacy_url = esc_js(get_option('pecan_pie_privacy_url', '/politique-de-confidentialite'));
    $theme = esc_attr(get_option('pecan_pie_theme', 'light'));
    $plugin_url = plugin_dir_url(__FILE__);

    echo '<script src="' . $plugin_url . 'tarteaucitron/tarteaucitron.min.js"></script>';
    echo "<script>
tarteaucitron.init({
  privacyUrl: '" . $privacy_url . "',
  bodyPosition: 'top',
  hashtag: '#cookies',
  cookieName: 'tarteaucitron',
  orientation: 'middle',
  groupServices: true,
  showDetailsOnClick: true,
  serviceDefaultState: 'wait',
  showAlertSmall: false,
  cookieslist: false,
  closePopup: true,
  showIcon: true,
  iconPosition: 'BottomLeft',
  adblocker: false,
  DenyAllCta: true,
  AcceptAllCta: true,
  highPrivacy: true,
  alwaysNeedConsent: false,
  handleBrowserDNTRequest: false,
  removeCredit: true,
  moreInfoLink: true,
  useExternalCss: false,
  useExternalJs: false,
  readmoreLink: '',
  mandatory: true,
  mandatoryCta: false,
  googleConsentMode: true,
  bingConsentMode: true,
  softConsentMode: false,
  dataLayer: false,
  serverSide: false,
  partnersList: true
});
(tarteaucitron.job = tarteaucitron.job || []).push('gcmadstorage');
(tarteaucitron.job = tarteaucitron.job || []).push('gcmanalyticsstorage');
(tarteaucitron.job = tarteaucitron.job || []).push('gcmfunctionality');
(tarteaucitron.job = tarteaucitron.job || []).push('gcmpersonalization');
(tarteaucitron.job = tarteaucitron.job || []).push('gcmadsuserdata');
(tarteaucitron.job = tarteaucitron.job || []).push('gcmsecurity');
</script>";
echo '<link rel="stylesheet" href="' . $plugin_url . $theme . '.css" />';
});



// Gestion des mises à jour
require plugin_dir_path(__FILE__) . 'plugin-update-checker/plugin-update-checker.php';

$pecanPieUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://github.com/htag-digital/pecan-pie/',
    __FILE__,
    'pecan-pie'
);

// Facultatif : si tu veux restreindre à une branche
$pecanPieUpdateChecker->setBranch('main');
