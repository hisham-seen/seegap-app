<?php defined('SEEGAP') || die() ?>

<div class="modal fade" id="create_microsite_countdown" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#microsite_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('microsite_countdown.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_microsite_countdown" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \SeeGap\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="countdown" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="countdown_counter_end_date"><i class="fas fa-fw fa-clock fa-sm text-muted mr-1"></i> <?= l('microsite_countdown.end_date') ?></label>
                        <input
                                id="countdown_counter_end_date"
                                type="text"
                                class="form-control"
                                name="counter_end_date"
                                value=""
                                autocomplete="off"
                                data-daterangepicker
                        />
                    </div>

                    <div class="form-group">
                        <label for="countdown_style"><i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= l('microsite_countdown.style') ?></label>
                        <select id="countdown_style" name="style" class="custom-select">
                            <optgroup label="Digital Styles">
                                <option value="digital-led">LED Display</option>
                                <option value="digital-lcd">LCD Display</option>
                                <option value="neon-style">Neon Style</option>
                                <option value="matrix-style">Matrix Style</option>
                            </optgroup>
                            <optgroup label="Analog/Visual Styles">
                                <option value="circular-progress">Circular Progress</option>
                                <option value="gauge-style">Gauge Style</option>
                                <option value="card-flip">Card Flip</option>
                                <option value="slide-animation">Slide Animation</option>
                            </optgroup>
                            <optgroup label="Modern Styles">
                                <option value="glassmorphism">Glassmorphism</option>
                                <option value="neumorphism">Neumorphism</option>
                                <option value="gradient">Gradient</option>
                                <option value="minimalist">Minimalist</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="countdown_theme"><i class="fas fa-fw fa-sun fa-sm text-muted mr-1"></i> <?= l('microsite_countdown.theme') ?></label>
                        <select id="countdown_theme" name="theme" class="custom-select">
                            <option value="light"><?= l('global.theme_style_light') ?></option>
                            <option value="dark"><?= l('global.theme_style_dark') ?></option>
                        </select>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
