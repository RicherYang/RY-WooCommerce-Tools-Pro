<h2><?php echo esc_html($name); ?></h2>
<table class="form-table">
    <tbody>
        <tr>
            <th>
                <label for="key-<?php echo hash('sha1', $basename); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>">授權金鑰</label>
            </th>
            <td>
                <input class="regular-text" name="key-<?php echo hash('sha1', $basename); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>" id="key-<?php echo hash('sha1', $basename); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>" type="text" value="<?php echo esc_attr($key); ?>">
                <?php if (!empty($expire)) {
                    printf(
                        '<p class="description">授權到期日 %s</p>',
                        esc_html(date_i18n(get_option('date_format'), $expire)),
                    );
                } ?>
            </td>
        </tr>
        <tr>
            <th>版本資訊</th>
            <td>
                目前版本 <?php echo esc_html($version); ?>
                <?php if (version_compare($version, $version_info['version'], '<')) { ?>
                <br><span style="color:blue">最新版本 <?php echo esc_html($version_info['version']); ?></span>
                <a href="<?php echo esc_url(wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=' . $basename), 'upgrade-plugin_' . $basename)); ?>">更新外掛</a>
                <?php } ?>
            </td>
        </tr>
    </tbody>
</table>
