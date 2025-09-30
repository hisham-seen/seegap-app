<?php defined('SEEGAP') || die() ?>

<input type="hidden" name="section" value="logistics" />

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">
        <i class="fas fa-truck text-primary mr-2"></i>
        <?= l('products.gs1_logistics_section') ?>
    </h5>
    <small class="text-muted"><?= l('products.gs1_logistics_description') ?></small>
</div>

<!-- Ship To / Deliver To -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-shipping-fast fa-sm mr-2"></i>
        <?= l('products.shipping_information') ?>
    </h6>
    <div class="row">
        <!-- Ship To / Deliver To (AI 410) -->
        <div class="col-lg-6 mb-3">
            <label for="ship_to_loc" class="form-label">
                <i class="fas fa-fw fa-map-marker-alt fa-sm text-muted mr-1"></i>
                <?= l('products.ship_to_loc') ?>
                <span class="text-muted small">(AI 410)</span>
            </label>
            <input 
                type="text" 
                id="ship_to_loc" 
                name="ship_to_loc" 
                class="form-control" 
                value="<?= $data->product->ship_to_loc ?? '' ?>"
                placeholder="<?= l('products.ship_to_loc_placeholder') ?>"
                maxlength="13"
            >
            <div class="form-text"><?= l('products.ship_to_loc_help') ?></div>
        </div>

        <!-- Bill To / Invoice To (AI 411) -->
        <div class="col-lg-6 mb-3">
            <label for="bill_to" class="form-label">
                <i class="fas fa-fw fa-file-invoice fa-sm text-muted mr-1"></i>
                <?= l('products.bill_to') ?>
                <span class="text-muted small">(AI 411)</span>
            </label>
            <input 
                type="text" 
                id="bill_to" 
                name="bill_to" 
                class="form-control" 
                value="<?= $data->product->bill_to ?? '' ?>"
                placeholder="<?= l('products.bill_to_placeholder') ?>"
                maxlength="13"
            >
            <div class="form-text"><?= l('products.bill_to_help') ?></div>
        </div>

        <!-- Purchased From (AI 412) -->
        <div class="col-lg-6 mb-3">
            <label for="purchased_from" class="form-label">
                <i class="fas fa-fw fa-shopping-cart fa-sm text-muted mr-1"></i>
                <?= l('products.purchased_from') ?>
                <span class="text-muted small">(AI 412)</span>
            </label>
            <input 
                type="text" 
                id="purchased_from" 
                name="purchased_from" 
                class="form-control" 
                value="<?= $data->product->purchased_from ?? '' ?>"
                placeholder="<?= l('products.purchased_from_placeholder') ?>"
                maxlength="13"
            >
            <div class="form-text"><?= l('products.purchased_from_help') ?></div>
        </div>

        <!-- Ship For / Deliver For (AI 413) -->
        <div class="col-lg-6 mb-3">
            <label for="ship_for_loc" class="form-label">
                <i class="fas fa-fw fa-truck-loading fa-sm text-muted mr-1"></i>
                <?= l('products.ship_for_loc') ?>
                <span class="text-muted small">(AI 413)</span>
            </label>
            <input 
                type="text" 
                id="ship_for_loc" 
                name="ship_for_loc" 
                class="form-control" 
                value="<?= $data->product->ship_for_loc ?? '' ?>"
                placeholder="<?= l('products.ship_for_loc_placeholder') ?>"
                maxlength="13"
            >
            <div class="form-text"><?= l('products.ship_for_loc_help') ?></div>
        </div>
    </div>
</div>

<!-- Physical Location -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-map-marker-alt fa-sm mr-2"></i>
        <?= l('products.physical_location') ?>
    </h6>
    <div class="row">
        <!-- Identification of a Physical Location (AI 414) -->
        <div class="col-lg-6 mb-3">
            <label for="phy_loc" class="form-label">
                <i class="fas fa-fw fa-building fa-sm text-muted mr-1"></i>
                <?= l('products.phy_loc') ?>
                <span class="text-muted small">(AI 414)</span>
            </label>
            <input 
                type="text" 
                id="phy_loc" 
                name="phy_loc" 
                class="form-control" 
                value="<?= $data->product->phy_loc ?? '' ?>"
                placeholder="<?= l('products.phy_loc_placeholder') ?>"
                maxlength="13"
            >
            <div class="form-text"><?= l('products.phy_loc_help') ?></div>
        </div>

        <!-- RTI Location (AI 415) -->
        <div class="col-lg-6 mb-3">
            <label for="rti_loc" class="form-label">
                <i class="fas fa-fw fa-warehouse fa-sm text-muted mr-1"></i>
                <?= l('products.rti_loc') ?>
                <span class="text-muted small">(AI 415)</span>
            </label>
            <input 
                type="text" 
                id="rti_loc" 
                name="rti_loc" 
                class="form-control" 
                value="<?= $data->product->rti_loc ?? '' ?>"
                placeholder="<?= l('products.rti_loc_placeholder') ?>"
                maxlength="13"
            >
            <div class="form-text"><?= l('products.rti_loc_help') ?></div>
        </div>
    </div>
</div>

<!-- Postal Information -->
<div class="mb-4">
    <h6 class="mb-3">
        <i class="fas fa-envelope fa-sm mr-2"></i>
        <?= l('products.postal_information') ?>
    </h6>
    <div class="row">
        <!-- Ship To / Deliver To Postal Code (AI 420) -->
        <div class="col-lg-6 mb-3">
            <label for="ship_to_post" class="form-label">
                <i class="fas fa-fw fa-mail-bulk fa-sm text-muted mr-1"></i>
                <?= l('products.ship_to_post') ?>
                <span class="text-muted small">(AI 420)</span>
            </label>
            <input 
                type="text" 
                id="ship_to_post" 
                name="ship_to_post" 
                class="form-control" 
                value="<?= $data->product->ship_to_post ?? '' ?>"
                placeholder="<?= l('products.ship_to_post_placeholder') ?>"
                maxlength="20"
            >
            <div class="form-text"><?= l('products.ship_to_post_help') ?></div>
        </div>

        <!-- Ship To / Deliver To Postal Code with ISO Country Code (AI 421) -->
        <div class="col-lg-6 mb-3">
            <label for="ship_to_post_iso" class="form-label">
                <i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i>
                <?= l('products.ship_to_post_iso') ?>
                <span class="text-muted small">(AI 421)</span>
            </label>
            <input 
                type="text" 
                id="ship_to_post_iso" 
                name="ship_to_post_iso" 
                class="form-control" 
                value="<?= $data->product->ship_to_post_iso ?? '' ?>"
                placeholder="<?= l('products.ship_to_post_iso_placeholder') ?>"
                maxlength="15"
            >
            <div class="form-text"><?= l('products.ship_to_post_iso_help') ?></div>
        </div>

        <!-- Country of Origin (AI 422) -->
        <div class="col-lg-6 mb-3">
            <label for="origin" class="form-label">
                <i class="fas fa-fw fa-flag fa-sm text-muted mr-1"></i>
                <?= l('products.origin') ?>
                <span class="text-muted small">(AI 422)</span>
            </label>
            <select 
                id="origin" 
                name="origin" 
                class="form-control"
            >
                <option value=""><?= l('products.origin_placeholder') ?></option>
                <option value="IE" <?= ($data->product->origin ?? '') === 'IE' ? 'selected' : '' ?>>Ireland (IE)</option>
                <option value="GB" <?= ($data->product->origin ?? '') === 'GB' ? 'selected' : '' ?>>United Kingdom (GB)</option>
                <option value="FR" <?= ($data->product->origin ?? '') === 'FR' ? 'selected' : '' ?>>France (FR)</option>
                <option value="DE" <?= ($data->product->origin ?? '') === 'DE' ? 'selected' : '' ?>>Germany (DE)</option>
                <option value="ES" <?= ($data->product->origin ?? '') === 'ES' ? 'selected' : '' ?>>Spain (ES)</option>
                <option value="IT" <?= ($data->product->origin ?? '') === 'IT' ? 'selected' : '' ?>>Italy (IT)</option>
                <option value="NL" <?= ($data->product->origin ?? '') === 'NL' ? 'selected' : '' ?>>Netherlands (NL)</option>
                <option value="BE" <?= ($data->product->origin ?? '') === 'BE' ? 'selected' : '' ?>>Belgium (BE)</option>
                <option value="US" <?= ($data->product->origin ?? '') === 'US' ? 'selected' : '' ?>>United States (US)</option>
                <option value="CA" <?= ($data->product->origin ?? '') === 'CA' ? 'selected' : '' ?>>Canada (CA)</option>
                <option value="AU" <?= ($data->product->origin ?? '') === 'AU' ? 'selected' : '' ?>>Australia (AU)</option>
                <option value="JP" <?= ($data->product->origin ?? '') === 'JP' ? 'selected' : '' ?>>Japan (JP)</option>
                <option value="CN" <?= ($data->product->origin ?? '') === 'CN' ? 'selected' : '' ?>>China (CN)</option>
            </select>
            <div class="form-text"><?= l('products.origin_help') ?></div>
        </div>

        <!-- Country of Initial Processing (AI 423) -->
        <div class="col-lg-6 mb-3">
            <label for="country_initial_process" class="form-label">
                <i class="fas fa-fw fa-industry fa-sm text-muted mr-1"></i>
                <?= l('products.country_initial_process') ?>
                <span class="text-muted small">(AI 423)</span>
            </label>
            <select 
                id="country_initial_process" 
                name="country_initial_process" 
                class="form-control"
            >
                <option value=""><?= l('products.country_initial_process_placeholder') ?></option>
                <option value="IE" <?= ($data->product->country_initial_process ?? '') === 'IE' ? 'selected' : '' ?>>Ireland (IE)</option>
                <option value="GB" <?= ($data->product->country_initial_process ?? '') === 'GB' ? 'selected' : '' ?>>United Kingdom (GB)</option>
                <option value="FR" <?= ($data->product->country_initial_process ?? '') === 'FR' ? 'selected' : '' ?>>France (FR)</option>
                <option value="DE" <?= ($data->product->country_initial_process ?? '') === 'DE' ? 'selected' : '' ?>>Germany (DE)</option>
                <option value="ES" <?= ($data->product->country_initial_process ?? '') === 'ES' ? 'selected' : '' ?>>Spain (ES)</option>
                <option value="IT" <?= ($data->product->country_initial_process ?? '') === 'IT' ? 'selected' : '' ?>>Italy (IT)</option>
                <option value="NL" <?= ($data->product->country_initial_process ?? '') === 'NL' ? 'selected' : '' ?>>Netherlands (NL)</option>
                <option value="BE" <?= ($data->product->country_initial_process ?? '') === 'BE' ? 'selected' : '' ?>>Belgium (BE)</option>
                <option value="US" <?= ($data->product->country_initial_process ?? '') === 'US' ? 'selected' : '' ?>>United States (US)</option>
                <option value="CA" <?= ($data->product->country_initial_process ?? '') === 'CA' ? 'selected' : '' ?>>Canada (CA)</option>
                <option value="AU" <?= ($data->product->country_initial_process ?? '') === 'AU' ? 'selected' : '' ?>>Australia (AU)</option>
                <option value="JP" <?= ($data->product->country_initial_process ?? '') === 'JP' ? 'selected' : '' ?>>Japan (JP)</option>
                <option value="CN" <?= ($data->product->country_initial_process ?? '') === 'CN' ? 'selected' : '' ?>>China (CN)</option>
            </select>
            <div class="form-text"><?= l('products.country_initial_process_help') ?></div>
        </div>

        <!-- Country of Processing (AI 424) -->
        <div class="col-lg-6 mb-3">
            <label for="country_process" class="form-label">
                <i class="fas fa-fw fa-cogs fa-sm text-muted mr-1"></i>
                <?= l('products.country_process') ?>
                <span class="text-muted small">(AI 424)</span>
            </label>
            <select 
                id="country_process" 
                name="country_process" 
                class="form-control"
            >
                <option value=""><?= l('products.country_process_placeholder') ?></option>
                <option value="IE" <?= ($data->product->country_process ?? '') === 'IE' ? 'selected' : '' ?>>Ireland (IE)</option>
                <option value="GB" <?= ($data->product->country_process ?? '') === 'GB' ? 'selected' : '' ?>>United Kingdom (GB)</option>
                <option value="FR" <?= ($data->product->country_process ?? '') === 'FR' ? 'selected' : '' ?>>France (FR)</option>
                <option value="DE" <?= ($data->product->country_process ?? '') === 'DE' ? 'selected' : '' ?>>Germany (DE)</option>
                <option value="ES" <?= ($data->product->country_process ?? '') === 'ES' ? 'selected' : '' ?>>Spain (ES)</option>
                <option value="IT" <?= ($data->product->country_process ?? '') === 'IT' ? 'selected' : '' ?>>Italy (IT)</option>
                <option value="NL" <?= ($data->product->country_process ?? '') === 'NL' ? 'selected' : '' ?>>Netherlands (NL)</option>
                <option value="BE" <?= ($data->product->country_process ?? '') === 'BE' ? 'selected' : '' ?>>Belgium (BE)</option>
                <option value="US" <?= ($data->product->country_process ?? '') === 'US' ? 'selected' : '' ?>>United States (US)</option>
                <option value="CA" <?= ($data->product->country_process ?? '') === 'CA' ? 'selected' : '' ?>>Canada (CA)</option>
                <option value="AU" <?= ($data->product->country_process ?? '') === 'AU' ? 'selected' : '' ?>>Australia (AU)</option>
                <option value="JP" <?= ($data->product->country_process ?? '') === 'JP' ? 'selected' : '' ?>>Japan (JP)</option>
                <option value="CN" <?= ($data->product->country_process ?? '') === 'CN' ? 'selected' : '' ?>>China (CN)</option>
            </select>
            <div class="form-text"><?= l('products.country_process_help') ?></div>
        </div>

        <!-- Country of Disassembly (AI 425) -->
        <div class="col-lg-6 mb-3">
            <label for="country_disassembly" class="form-label">
                <i class="fas fa-fw fa-tools fa-sm text-muted mr-1"></i>
                <?= l('products.country_disassembly') ?>
                <span class="text-muted small">(AI 425)</span>
            </label>
            <select 
                id="country_disassembly" 
                name="country_disassembly" 
                class="form-control"
            >
                <option value=""><?= l('products.country_disassembly_placeholder') ?></option>
                <option value="IE" <?= ($data->product->country_disassembly ?? '') === 'IE' ? 'selected' : '' ?>>Ireland (IE)</option>
                <option value="GB" <?= ($data->product->country_disassembly ?? '') === 'GB' ? 'selected' : '' ?>>United Kingdom (GB)</option>
                <option value="FR" <?= ($data->product->country_disassembly ?? '') === 'FR' ? 'selected' : '' ?>>France (FR)</option>
                <option value="DE" <?= ($data->product->country_disassembly ?? '') === 'DE' ? 'selected' : '' ?>>Germany (DE)</option>
                <option value="ES" <?= ($data->product->country_disassembly ?? '') === 'ES' ? 'selected' : '' ?>>Spain (ES)</option>
                <option value="IT" <?= ($data->product->country_disassembly ?? '') === 'IT' ? 'selected' : '' ?>>Italy (IT)</option>
                <option value="NL" <?= ($data->product->country_disassembly ?? '') === 'NL' ? 'selected' : '' ?>>Netherlands (NL)</option>
                <option value="BE" <?= ($data->product->country_disassembly ?? '') === 'BE' ? 'selected' : '' ?>>Belgium (BE)</option>
                <option value="US" <?= ($data->product->country_disassembly ?? '') === 'US' ? 'selected' : '' ?>>United States (US)</option>
                <option value="CA" <?= ($data->product->country_disassembly ?? '') === 'CA' ? 'selected' : '' ?>>Canada (CA)</option>
                <option value="AU" <?= ($data->product->country_disassembly ?? '') === 'AU' ? 'selected' : '' ?>>Australia (AU)</option>
                <option value="JP" <?= ($data->product->country_disassembly ?? '') === 'JP' ? 'selected' : '' ?>>Japan (JP)</option>
                <option value="CN" <?= ($data->product->country_disassembly ?? '') === 'CN' ? 'selected' : '' ?>>China (CN)</option>
            </select>
            <div class="form-text"><?= l('products.country_disassembly_help') ?></div>
        </div>

        <!-- Country of Full Process Chain (AI 426) -->
        <div class="col-lg-6 mb-3">
            <label for="country_full_process" class="form-label">
                <i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i>
                <?= l('products.country_full_process') ?>
                <span class="text-muted small">(AI 426)</span>
            </label>
            <select 
                id="country_full_process" 
                name="country_full_process" 
                class="form-control"
            >
                <option value=""><?= l('products.country_full_process_placeholder') ?></option>
                <option value="IE" <?= ($data->product->country_full_process ?? '') === 'IE' ? 'selected' : '' ?>>Ireland (IE)</option>
                <option value="GB" <?= ($data->product->country_full_process ?? '') === 'GB' ? 'selected' : '' ?>>United Kingdom (GB)</option>
                <option value="FR" <?= ($data->product->country_full_process ?? '') === 'FR' ? 'selected' : '' ?>>France (FR)</option>
                <option value="DE" <?= ($data->product->country_full_process ?? '') === 'DE' ? 'selected' : '' ?>>Germany (DE)</option>
                <option value="ES" <?= ($data->product->country_full_process ?? '') === 'ES' ? 'selected' : '' ?>>Spain (ES)</option>
                <option value="IT" <?= ($data->product->country_full_process ?? '') === 'IT' ? 'selected' : '' ?>>Italy (IT)</option>
                <option value="NL" <?= ($data->product->country_full_process ?? '') === 'NL' ? 'selected' : '' ?>>Netherlands (NL)</option>
                <option value="BE" <?= ($data->product->country_full_process ?? '') === 'BE' ? 'selected' : '' ?>>Belgium (BE)</option>
                <option value="US" <?= ($data->product->country_full_process ?? '') === 'US' ? 'selected' : '' ?>>United States (US)</option>
                <option value="CA" <?= ($data->product->country_full_process ?? '') === 'CA' ? 'selected' : '' ?>>Canada (CA)</option>
                <option value="AU" <?= ($data->product->country_full_process ?? '') === 'AU' ? 'selected' : '' ?>>Australia (AU)</option>
                <option value="JP" <?= ($data->product->country_full_process ?? '') === 'JP' ? 'selected' : '' ?>>Japan (JP)</option>
                <option value="CN" <?= ($data->product->country_full_process ?? '') === 'CN' ? 'selected' : '' ?>>China (CN)</option>
            </select>
            <div class="form-text"><?= l('products.country_full_process_help') ?></div>
        </div>
    </div>
</div>

<!-- Additional Information -->
<div class="alert alert-info mb-4">
    <i class="fas fa-info-circle mr-2"></i>
    <strong><?= l('products.logistics_note_title') ?>:</strong>
    <?= l('products.logistics_note_description') ?>
</div>

<!-- Save Button -->
<div class="mt-4">
    <button type="submit" name="submit" class="btn btn-primary">
        <i class="fas fa-save fa-sm mr-1"></i>
        <?= l('global.update') ?>
    </button>
    <a href="<?= url('products') ?>" class="btn btn-outline-secondary ml-2">
        <i class="fas fa-times fa-sm mr-1"></i>
        <?= l('global.cancel') ?>
    </a>
</div>
