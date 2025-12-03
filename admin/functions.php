<?php

function rywtp_multiselect($field)
{
    $field = wp_parse_args($field, [
        'class' => 'wc-enhanced-select short',
        'style' => '',
        'wrapper_class' => '',
        'name' => $field['id'] . '[]',
        'desc_tip' => false,
        'custom_attributes' => [],
    ]);

    $wrapper_attributes = [
        'class' => $field['wrapper_class'] . " form-field {$field['id']}_field",
    ];
    $label_attributes = [
        'for' => $field['id'],
    ];

    $field_attributes = (array) $field['custom_attributes'];
    $field_attributes['style'] = $field['style'];
    $field_attributes['id'] = $field['id'];
    $field_attributes['name'] = $field['name'];
    $field_attributes['class'] = $field['class'];

    $tooltip = ! empty($field['description']) && false !== $field['desc_tip'] ? $field['description'] : '';
    $description = ! empty($field['description']) && false === $field['desc_tip'] ? $field['description'] : '';
    ?>
<p <?php echo wc_implode_html_attributes($wrapper_attributes); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>>
    <label <?php echo wc_implode_html_attributes($label_attributes); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>><?php echo wp_kses_post($field['label']); ?></label>
    <?php if ($tooltip) { ?>
    <?php echo wc_help_tip($tooltip); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>
    <?php } ?>
    <select multiple="multiple" <?php echo wc_implode_html_attributes($field_attributes); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped?>>
        <?php foreach ((array) $field['options'] as $option_key => $option_value) { ?>
        <?php if (is_array($option_value)) { ?>
        <optgroup label="<?php echo esc_attr($option_key); ?>">
            <?php foreach ($option_value as $option_key_inner => $option_value_inner) { ?>
            <option value="<?php echo esc_attr($option_key_inner); ?>" <?php selected(in_array((string) $option_key_inner, $field['value'], true), true); ?>><?php echo esc_html($option_value_inner); ?></option>
            <?php } ?>
        </optgroup>
        <?php } else { ?>
        <option value="<?php echo esc_attr($option_key); ?>" <?php selected(in_array((string) $option_key, $field['value'], true), true); ?>><?php echo esc_html($option_value); ?></option>
        <?php } ?>
        <?php } ?>
    </select>
    <?php if ($description) { ?>
    <span class="description"><?php echo wp_kses_post($description); ?></span>
    <?php } ?>
</p>
<?php
}
?>
