<script type="text/template" id="tmpl-ry-modal-view-shipping-info">
    <div class="wc-backbone-modal wc-order-preview">
        <div class="wc-backbone-modal-content">
            <section class="wc-backbone-modal-main" role="main">
                <header class="wc-backbone-modal-header">
                    <?php /* translators: %s: order ID */ ?>
                    <h1><?php echo esc_html(sprintf(__('Order #%s', 'ry-woocommerce-tools-pro'), '{{ data.order_number }}')); ?></h1>
                    <button class="modal-close modal-close-link dashicons dashicons-no-alt">
                        <span class="screen-reader-text"><?php esc_html_e('Close modal panel', 'ry-woocommerce-tools-pro'); ?></span>
                    </button>
                </header>
                <article>
                    <div style="padding:1.5em">{{{ data.info_html }}}</div>
                </article>
            </section>
        </div>
    </div>
    <div class="wc-backbone-modal-backdrop modal-close"></div>
</script>
