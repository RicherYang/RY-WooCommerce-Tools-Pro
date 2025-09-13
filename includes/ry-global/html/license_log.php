<style>
    #ry-license-log {
        background: #fff;
        border: 1px solid #8c8f94;
    }
    #ry-license-log .log {
        padding: .4em .8em;
    }
    #ry-license-log .log-type {
        padding: 0 .2em;
        line-height: 1;
        border: 2px solid #2271b1;
        font-weight: bold;
    }
    #ry-license-log .log-type-error {
        border-color: #d63638;
    }
</style>
<h2>授權日誌紀錄</h2>
<div id="ry-license-log">
    <?php foreach ($license_logs as $log) {
        printf(
            '<div class="log">%s <span class="log-type log-type-%s">%s</span> %s</div>',
            esc_html($log['date']),
            esc_attr(strtolower($log['type'])),
            esc_html($log['type']),
            esc_html($log['message'])
        );
    } ?>
</div>
