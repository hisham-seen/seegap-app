<?php defined('SEEGAP') || die() ?>

<?php
/* Load the product update wrapper with sidebar, header, and footer */
$wrapper_data = [
    'product' => $data->product,
    'section' => $data->section,
    'valid_sections' => $data->valid_sections,
    'projects' => $data->projects,
    'gs1_links' => $data->gs1_links,
];

/* Include the wrapper which will handle the layout and load the appropriate section */
include_view(THEME_PATH . 'views/partials/product_update_wrapper.php', $wrapper_data);
?>

<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/product_delete_modal.php'), 'modals') ?>
