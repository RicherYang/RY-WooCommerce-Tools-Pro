<tr valign="top">
    <th scope="row" class="titledesc">
        <?php _e('version info', 'ry-woocommerce-tools-pro'); ?>
    </th>
    <td class="forminp">
        <?php _e('Now Version:', 'ry-woocommerce-tools-pro') ?> <?=$version ?>
        <?php if ($version_info && version_compare($version, $version_info['version'], '<')) { ?>
        <?php set_site_transient('update_plugins', []); ?>
        <br><span style="color:blue"><?php _e('New Version:', 'ry-woocommerce-tools-pro') ?></span> <?=$version_info['version'] ?>
        <a href="<?=wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=' . RY_WTP_PLUGIN_BASENAME), 'upgrade-plugin_' . RY_WTP_PLUGIN_BASENAME); ?>">
            <?php _e('update plugin', 'ry-woocommerce-tools-pro') ?>
        </a>
        <?php } ?>
    </td>
</tr>
