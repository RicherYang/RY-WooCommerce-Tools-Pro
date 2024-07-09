<?php

use Automattic\WooCommerce\Admin\Notes\Note;
use Automattic\WooCommerce\Admin\Notes\Notes;

class RY_WTP_LicenseAutoDeactivate
{
    public const NOTE_NAME = 'ry-wtp-license-autoend';

    public static function add_note()
    {
        $deactivate_date = RY_WTP::get_option('license_auto_deactivate_date');

        Notes::delete_notes_with_name(self::NOTE_NAME);

        $note = self::get_note_with_data($deactivate_date);
        $note->save();
    }

    public static function get_note_with_data($deactivate_date)
    {
        $content = sprintf(
            /* translators: %s: Date */
            __('License has auto deactivate at %s.', 'ry-woocommerce-tools-pro'),
            $deactivate_date,
        );

        $content_data = (object) [
            'deactivate_date' => $deactivate_date,
        ];

        $report_url = '?page=wc-settings&tab=rytools&section=ry_key';

        $note = new Note();
        $note->set_title(__('License Auto Deactivate!', 'ry-woocommerce-tools-pro'));
        $note->set_content($content);
        $note->set_content_data($content_data);
        $note->set_type(Note::E_WC_ADMIN_NOTE_ERROR);
        $note->set_name(self::NOTE_NAME);
        $note->set_source('ry-wei');
        $note->add_action('ry-view-license', __('License key', 'ry-woocommerce-tools-pro'), $report_url);

        return $note;
    }

    public static function get_note()
    {
        $note = Notes::get_note_by_name(self::NOTE_NAME);
        if (!$note) {
            return false;
        }
        $content_data = $note->get_content_data();

        return self::get_note_with_data(
            $content_data->deactivate_date,
        );
    }
}
