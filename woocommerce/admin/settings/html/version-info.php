<tr valign="top">
    <th scope="row" class="titledesc">
        <?php esc_html_e('version info', 'ry-woocommerce-tools-pro'); ?>
    </th>
    <td class="forminp">
        <?php esc_html_e('Now Version:', 'ry-woocommerce-tools-pro'); ?> <?php echo esc_html($version); ?>
        <?php if ($version_info && version_compare($version, $version_info['version'], '<')) { ?>
        <?php set_site_transient('update_plugins', []); ?>
        <br><span style="color:blue"><?php esc_html_e('New Version:', 'ry-woocommerce-tools-pro'); ?></span> <?php echo esc_html($version_info['version']); ?>
        <a href="<?php echo esc_url(wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=' . RY_WTP_PLUGIN_BASENAME), 'upgrade-plugin_' . RY_WTP_PLUGIN_BASENAME)); ?>">
            <?php esc_html_e('update plugin', 'ry-woocommerce-tools-pro'); ?>
        </a>
        <?php } ?>
    </td>
</tr>
