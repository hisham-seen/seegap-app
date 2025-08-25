<?php defined('SEEGAP') || die() ?>

<section class="container">
    <?= \SeeGap\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate">
                <a href="<?= url('data') ?>" class="text-muted mr-2"><i class="fas fa-fw fa-arrow-left"></i></a>
                <i class="fas fa-fw fa-xs fa-database mr-1"></i> 
                <?= l('data.submissions_header') ?>: 
                <span class="text-primary"><?= $data->form['form_name'] ?></span>
            </h1>

            <div class="ml-2">
                <span class="badge badge-light">
                    <i class="<?= $data->microsite_blocks[$data->form['type']]['icon'] ?> fa-fw fa-sm mr-1"></i>
                    <?= l('link.microsite.blocks.' . $data->form['type']) ?>
                </span>
            </div>
        </div>

        <div class="col-12 col-lg-auto d-flex d-print-none">
            <div>
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple <?= count($data->form['submissions']) ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <?php 
                        // Create export URL without duplicate microsite_block_id
                        $export_url_base = url('data?microsite_block_id=' . $data->form['microsite_block_id']);
                        // Add any other filters except microsite_block_id
                        $filters_get = $data->filters->get_get();
                        if(!empty($filters_get)) {
                            $filters_array = [];
                            parse_str($filters_get, $filters_array);
                            // Remove microsite_block_id if it exists
                            if(isset($filters_array['microsite_block_id'])) {
                                unset($filters_array['microsite_block_id']);
                            }
                            // Rebuild the query string
                            $filters_get = http_build_query($filters_array);
                            if(!empty($filters_get)) {
                                $export_url_base .= '&' . $filters_get;
                            }
                        }
                        ?>
                        <a href="<?= $export_url_base . '&export=csv' ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->csv ? null : 'disabled' ?>">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= $export_url_base . '&export=json' ?>" target="_blank" class="dropdown-item <?= $this->user->plan_settings->export->json ? null : 'disabled' ?>">
                            <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                        </a>
                        <a href="#" onclick="window.print();return false;" class="dropdown-item <?= $this->user->plan_settings->export->pdf ? null : 'disabled' ?>">
                            <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                        </a>
                    </div>
                </div>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-dark' : 'btn-light' ?> filters-button dropdown-toggle-simple <?= count($data->form['submissions']) || $data->filters->has_applied_filters ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-filter"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                            <?php if($data->filters->has_applied_filters): ?>
                                <a href="<?= url('data?microsite_block_id=' . $data->form['microsite_block_id']) ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                            <?php endif ?>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="" method="get" role="form">
                            <input type="hidden" name="microsite_block_id" value="<?= $data->form['microsite_block_id'] ?>" />

                            <div class="form-group px-4">
                                <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                    <option value="form_submission_id" <?= $data->filters->order_by == 'form_submission_id' ? 'selected="selected"' : null ?>><?= l('global.id') ?></option>
                                    <option value="submitted_at" <?= $data->filters->order_by == 'submitted_at' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_type" class="small"><?= l('global.filters.order_type') ?></label>
                                <select name="order_type" id="filters_order_type" class="custom-select custom-select-sm">
                                    <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                    <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_results_per_page" class="small"><?= l('global.filters.results_per_page') ?></label>
                                <select name="results_per_page" id="filters_results_per_page" class="custom-select custom-select-sm">
                                    <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                        <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4 mt-4">
                                <button type="submit" name="submit" class="btn btn-sm btn-primary btn-block"><?= l('global.submit') ?></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="ml-3">
                <button id="bulk_enable" type="button" class="btn btn-light" data-toggle="tooltip" title="<?= l('global.bulk_actions') ?>"><i class="fas fa-fw fa-sm fa-list"></i></button>

                <div id="bulk_group" class="btn-group d-none" role="group">
                    <div class="btn-group dropdown" role="group">
                        <button id="bulk_actions" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                            <?= l('global.bulk_actions') ?> <span id="bulk_counter" class="d-none"></span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="bulk_actions">
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#bulk_delete_modal"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                        </div>
                    </div>

                    <button id="bulk_disable" type="button" class="btn btn-secondary" data-toggle="tooltip" title="<?= l('global.close') ?>"><i class="fas fa-fw fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="h6 m-0"><?= l('data.submissions_count') ?>: <span class="text-primary"><?= $data->form['submissions_count'] ?></span></h2>
                </div>
                
                <div>
                    <?php if(!empty($data->form['submissions'])): ?>
                    <a href="<?= url('link/' . $data->form['submissions'][0]->link_id . '?tab=blocks') ?>" class="btn btn-sm btn-outline-secondary" data-toggle="tooltip" title="<?= l('data.microsite') ?>">
                        <i class="fas fa-fw fa-hashtag mr-1"></i> <?= l('data.microsite') ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if(count($data->form['submissions'])): ?>
        <form id="table" action="<?= SITE_URL . 'data/bulk' ?>" method="post" role="form">
            <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" />
            <input type="hidden" name="type" value="" data-bulk-type />
            <input type="hidden" name="original_request" value="<?= base64_encode(\SeeGap\Router::$original_request) ?>" />
            <input type="hidden" name="original_request_query" value="<?= base64_encode(\SeeGap\Router::$original_request_query) ?>" />

            <?php
            // Collect all unique questions from all submissions to create dynamic columns
            $all_questions = [];
            foreach($data->form['submissions'] as $submission) {
                // Decode JSON responses if needed
                $responses = $submission->responses;
                if(is_string($responses)) {
                    $responses = json_decode($responses, true);
                }
                
                if(is_array($responses)) {
                    foreach($responses as $key => $response) {
                        if(is_array($response) && isset($response['question'])) {
                            $question = $response['question'];
                        } else {
                            $question = is_string($key) ? ucfirst($key) : 'Question';
                        }
                        if(!in_array($question, $all_questions)) {
                            $all_questions[] = $question;
                        }
                    }
                } elseif(is_object($responses)) {
                    foreach($responses as $key => $value) {
                        $question = ucfirst($key);
                        if(!in_array($question, $all_questions)) {
                            $all_questions[] = $question;
                        }
                    }
                }
            }
            ?>

            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                    <tr>
                        <th data-bulk-table class="d-none">
                            <div class="custom-control custom-checkbox">
                                <input id="bulk_select_all" type="checkbox" class="custom-control-input" />
                                <label class="custom-control-label" for="bulk_select_all"></label>
                            </div>
                        </th>
                        <th><?= l('global.id') ?></th>
                        <th><?= l('global.type') ?></th>
                        <?php foreach($all_questions as $question): ?>
                            <th class="text-truncate" style="max-width: 200px;" data-toggle="tooltip" title="<?= htmlspecialchars($question) ?>">
                                <?= htmlspecialchars($question) ?>
                            </th>
                        <?php endforeach; ?>
                        <th><?= l('global.datetime') ?></th>
                        <th><?= l('global.location') ?></th>
                        <th><?= l('global.actions') ?></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($data->form['submissions'] as $row): ?>
                        <?php
                        // Create a mapping of questions to answers for this submission
                        $question_answers = [];
                        
                        // Decode JSON responses if needed
                        $responses = $row->responses;
                        if(is_string($responses)) {
                            $responses = json_decode($responses, true);
                        }
                        
                        if(is_array($responses)) {
                            foreach($responses as $key => $response) {
                                if(is_array($response) && isset($response['question'])) {
                                    $question = $response['question'];
                                    $question_answers[$question] = $response;
                                } else {
                                    $question = is_string($key) ? ucfirst($key) : 'Question';
                                    $question_answers[$question] = $response;
                                }
                            }
                        } elseif(is_object($responses)) {
                            foreach($responses as $key => $value) {
                                $question = ucfirst($key);
                                $question_answers[$question] = $value;
                            }
                        }
                        ?>
                        <tr>
                            <td data-bulk-table class="d-none">
                                <div class="custom-control custom-checkbox">
                                    <input id="selected_form_submission_id_<?= $row->form_submission_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->form_submission_id ?>" />
                                    <label class="custom-control-label" for="selected_form_submission_id_<?= $row->form_submission_id ?>"></label>
                                </div>
                            </td>

                            <td class="text-nowrap">
                                <span class="badge badge-light">
                                    #<?= $row->form_submission_id ?>
                                </span>
                            </td>

                            <td class="text-nowrap">
                                <?php if(!empty($row->form_type)): ?>
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-fw fa-sm fa-form mr-1"></i>
                                        <?= ucfirst($row->form_type) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-light">
                                        <i class="fas fa-fw fa-sm fa-form mr-1"></i>
                                        Form
                                    </span>
                                <?php endif; ?>
                            </td>

                            <?php foreach($all_questions as $question): ?>
                                <td class="text-truncate" style="max-width: 200px;">
                                    <?php if(isset($question_answers[$question])): ?>
                                        <?php 
                                        $answer = $question_answers[$question];
                                        
                                        /* Check if this is a file upload response - handle both arrays and objects */
                                        $is_file_upload = false;
                                        if(is_array($answer)) {
                                            $is_file_upload = isset($answer['files']) && is_array($answer['files']) && !empty($answer['files']);
                                        } elseif(is_object($answer)) {
                                            $is_file_upload = isset($answer->files) && is_array($answer->files) && !empty($answer->files);
                                        }
                                        
                                        if($is_file_upload): ?>
                                            <?php 
                                            // Handle both array and object types for file data
                                            $files = is_array($answer) ? $answer['files'] : $answer->files;
                                            $response_text = is_array($answer) ? ($answer['response'] ?? count($files) . ' files') : ($answer->response ?? count($files) . ' files');
                                            ?>
                                            <div class="file-attachments">
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="fas fa-paperclip text-primary mr-1"></i>
                                                    <small class="text-muted"><?= $response_text ?></small>
                                                </div>
                                                <div class="file-thumbnails">
                                                    <?php foreach($files as $file): ?>
                                                        <?php 
                                                        // Handle both array and object types for individual file data
                                                        $file_mime_type = is_array($file) ? ($file['mime_type'] ?? '') : ($file->mime_type ?? '');
                                                        $file_id = is_array($file) ? ($file['file_id'] ?? '') : ($file->file_id ?? '');
                                                        $file_name = is_array($file) ? ($file['original_name'] ?? 'File') : ($file->original_name ?? 'File');
                                                        ?>
                                                        <?php if(!empty($file_mime_type) && strpos($file_mime_type, 'image/') === 0): ?>
                                                            <div class="file-thumbnail-item d-inline-block mr-1 mb-1">
                                                                <img src="<?= url('file-access/thumbnail?file_id=' . urlencode($file_id) . '&size=50') ?>" 
                                                                     class="img-thumbnail receipt-image-thumbnail" 
                                                                     style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                                                     data-toggle="tooltip" 
                                                                     title="<?= htmlspecialchars($file_name) ?>"
                                                                     data-full-image="<?= url('file-access?file_id=' . urlencode($file_id)) ?>"
                                                                     data-image-title="<?= htmlspecialchars($file_name) ?>">
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="file-item d-inline-block mr-1 mb-1">
                                                                <a href="<?= url('file-access?file_id=' . urlencode($file_id)) ?>" 
                                                                   target="_blank" 
                                                                   class="btn btn-sm btn-outline-secondary"
                                                                   data-toggle="tooltip" 
                                                                   title="<?= htmlspecialchars($file_name) ?>">
                                                                    <i class="fas fa-file"></i>
                                                                </a>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                                <?php 
                                                // Handle AI analysis for both array and object types
                                                $ai_analysis = is_array($answer) ? ($answer['ai_analysis'] ?? null) : ($answer->ai_analysis ?? null);
                                                if($ai_analysis): ?>
                                                    <div class="ai-analysis-indicator mt-1">
                                                        <small class="badge badge-info">
                                                            <i class="fas fa-robot mr-1"></i>
                                                            AI Analysis: <?= is_array($ai_analysis) ? ($ai_analysis['status'] ?? 'pending') : ($ai_analysis->status ?? 'pending') ?>
                                                        </small>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <?php
                                            // Handle different answer types safely
                                            $display_value = '';
                                            $tooltip_value = '';
                                            
                                            if(is_array($answer)) {
                                                if(isset($answer['response'])) {
                                                    $display_value = $answer['response'];
                                                    $tooltip_value = json_encode($answer);
                                                } else {
                                                    $display_value = json_encode($answer);
                                                    $tooltip_value = $display_value;
                                                }
                                            } elseif(is_object($answer)) {
                                                $display_value = json_encode($answer);
                                                $tooltip_value = $display_value;
                                            } else {
                                                $display_value = (string)$answer;
                                                $tooltip_value = $display_value;
                                            }
                                            ?>
                                            <span data-toggle="tooltip" title="<?= htmlspecialchars($tooltip_value) ?>">
                                                <?= htmlspecialchars($display_value) ?>
                                            </span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>

                            <td class="text-nowrap text-muted">
                                <span data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \SeeGap\Date::get($row->submitted_at, 2) . '<br /><small>' . \SeeGap\Date::get($row->submitted_at, 3) . '</small>' . '<br /><small>(' . \SeeGap\Date::get_timeago($row->submitted_at) . ')</small>') ?>">
                                    <i class="fas fa-fw fa-calendar text-muted mr-1"></i>
                                    <?= \SeeGap\Date::get($row->submitted_at, 1) ?>
                                </span>
                            </td>

                            <td class="text-nowrap">
                                <?php if(!empty($row->ip)): ?>
                                    <div class="d-flex flex-column">
                                        <span class="badge badge-light" data-toggle="tooltip" title="IP Address">
                                            <i class="fas fa-fw fa-sm fa-globe mr-1"></i>
                                            <?= htmlspecialchars($row->ip) ?>
                                        </span>
                                        <?php if(!empty($row->country) || !empty($row->city)): ?>
                                            <small class="text-muted mt-1">
                                                <i class="fas fa-fw fa-map-marker-alt mr-1"></i>
                                                <?= !empty($row->city) ? htmlspecialchars($row->city) : '' ?>
                                                <?= !empty($row->country) ? (!empty($row->city) ? ', ' : '') . htmlspecialchars($row->country) : '' ?>
                                            </small>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">
                                        <i class="fas fa-fw fa-question-circle mr-1"></i>
                                        Unknown
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <div class="d-flex justify-content-end">
                                    <?= include_view(THEME_PATH . 'views/data/datum_dropdown_button.php', ['id' => $row->form_submission_id]) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>

                    </tbody>
                </table>
            </div>
        </form>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>
        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'data',
            'has_secondary_text' => false,
        ]); ?>
    <?php endif ?>

</section>

<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \SeeGap\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals'); ?>

<!-- Image Lightbox Modal -->
<div class="modal fade" id="imageLightboxModal" tabindex="-1" role="dialog" aria-labelledby="imageLightboxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageLightboxModalLabel">Receipt Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="lightboxImage" src="" alt="" class="img-fluid" style="max-height: 70vh;">
            </div>
            <div class="modal-footer">
                <a id="downloadImageLink" href="" target="_blank" class="btn btn-primary">
                    <i class="fas fa-download mr-1"></i> Download Original
                </a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle receipt image thumbnail clicks
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('receipt-image-thumbnail')) {
            e.preventDefault();
            
            const fullImageUrl = e.target.getAttribute('data-full-image');
            const imageTitle = e.target.getAttribute('data-image-title');
            
            if (fullImageUrl) {
                // Set modal content
                document.getElementById('lightboxImage').src = fullImageUrl;
                document.getElementById('lightboxImage').alt = imageTitle || 'Receipt Image';
                document.getElementById('imageLightboxModalLabel').textContent = imageTitle || 'Receipt Image';
                document.getElementById('downloadImageLink').href = fullImageUrl;
                
                // Show modal
                $('#imageLightboxModal').modal('show');
            }
        }
    });
    
    // Handle modal close to reset image src
    $('#imageLightboxModal').on('hidden.bs.modal', function() {
        document.getElementById('lightboxImage').src = '';
    });
});
</script>
