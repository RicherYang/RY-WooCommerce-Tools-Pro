<h2>
    RY Tools (Pro) for WooCommerce <?php esc_html_e('Tools', 'ry-woocommerce-tools-pro') ?>
</h2>

<p>配合 <a href="https://tw.wordpress.org/plugins/ry-wc-city-select/" target="_black">RY City Select for WooCommerce</a>
    的預設設定值進行使用者地址中 <strong>縣市</strong> 與 <strong>鄉鎮市</strong> 資料的轉算。</p>
<p>請注意！這會修改使用者所選填寫的資料，進行資料轉換前請備份你的資料，以確保當發生任何意外之時你有辦法回復到原本的狀態。</p>

<?php if (defined('RY_WCS_VERSION')) { ?>
<button name="change_address" class="button-primary" type="submit" value="change_address">
    <?php esc_html_e('Change address', 'woocommerce'); ?></button>
<?php }
?>
