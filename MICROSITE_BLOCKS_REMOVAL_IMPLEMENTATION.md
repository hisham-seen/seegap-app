# Microsite Blocks Removal Implementation Guide

## 🎯 PROJECT OVERVIEW

**Goal:** Remove all microsite block restrictions so all blocks are available to all users regardless of their subscription plan.

**Approach:** Option A - Complete removal of all controls and restrictions

**Current Status:** ⏳ IMPLEMENTATION IN PROGRESS

---

## 📋 IMPLEMENTATION PHASES

### ✅ PHASE 1: CORE BLOCK AVAILABILITY (CRITICAL - PRIORITY 1)

#### File 1: `app/includes/enabled_microsite_blocks.php`
**Status:** ⏳ PENDING
**Purpose:** Make all blocks available without settings filtering

**BEFORE:**
```php
$enabled_microsite_blocks = [];
$available_blocks = settings()->links->available_microsite_blocks ?? new \stdClass();

foreach(require APP_PATH . 'includes/microsite_blocks.php' as $type => $value) {
    if (!isset($available_blocks->{$type})) {
        $available_blocks->{$type} = ($type === 'text') ? true : false;
    }
    
    if($available_blocks->{$type}) {
        $enabled_microsite_blocks[$type] = $value;
    }
}

return $enabled_microsite_blocks;
```

**AFTER:**
```php
// Return all blocks without any filtering
return require APP_PATH . 'includes/microsite_blocks.php';
```

#### File 2: `themes/phoenix/views/link/settings/microsite_link_create_modal.php`
**Status:** ⏳ PENDING
**Purpose:** Remove plan permission checks in modal

**CHANGES NEEDED:**
1. Remove plan permission check: `($this->user->plan_settings->enabled_microsite_blocks->{$key} ?? null) || ($key === 'text')`
2. Remove disabled block rendering logic
3. Make all blocks render as enabled buttons
4. Remove strikethrough styling and "no access" tooltips

**SEARCH FOR:**
```php
<?php if(($this->user->plan_settings->enabled_microsite_blocks->{$key} ?? null) || ($key === 'text')): ?>
```

**REPLACE WITH:**
```php
<?php // All blocks are now available to all users ?>
```

**ALSO REMOVE:** The entire `<?php else: ?>` block that renders disabled buttons

---

### ✅ PHASE 2: ADMIN INTERFACE CLEANUP (PRIORITY 2)

#### File 3: `themes/phoenix/views/admin/settings/partials/links.php`
**Status:** ⏳ PENDING
**Purpose:** Remove "Available Microsite Blocks" admin controls

**SECTION TO REMOVE:** Lines containing "available_microsite_blocks" section:
```php
<div class="form-group mt-5">
    <?php $microsite_blocks = require APP_PATH . 'includes/microsite_blocks.php'; ?>
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="h5"><?= l('admin_settings.links.available_microsite_blocks') . ' (' . count($microsite_blocks) . ')' ?></h3>
        // ... select all/deselect all buttons
    </div>
    
    <div class="row">
        <?php foreach($microsite_blocks as $key => $value): ?>
            // ... checkboxes for each block
        <?php endforeach ?>
    </div>
</div>
```

#### File 4: `themes/phoenix/views/admin/plan-create/index.php`
**Status:** ⏳ PENDING
**Purpose:** Remove microsite block controls from plan creation

**SECTIONS TO REMOVE:**
1. `microsite_blocks_limit` input field
2. "Enabled Microsite Blocks" section with checkboxes

#### File 5: `themes/phoenix/views/admin/plan-update/index.php`
**Status:** ⏳ PENDING
**Purpose:** Remove microsite block controls from plan updates

**SECTIONS TO REMOVE:**
1. `microsite_blocks_limit` input field
2. "Enabled Microsite Blocks" section with checkboxes

#### File 6: `themes/phoenix/views/admin/user-update/index.php`
**Status:** ⏳ PENDING
**Purpose:** Remove microsite block controls from user management

**SECTIONS TO REMOVE:**
1. `microsite_blocks_limit` input field
2. "Enabled Microsite Blocks" section with checkboxes

---

### ✅ PHASE 3: UI REFERENCES CLEANUP (PRIORITY 3)

#### File 7: `themes/phoenix/views/partials/plan_features.php`
**Status:** ⏳ PENDING
**Purpose:** Remove microsite block limit displays

**SEARCH FOR:**
- `microsite_blocks_limit`
- `enabled_microsite_blocks`

#### File 8: `themes/phoenix/views/partials/plans_plan_content.php`
**Status:** ⏳ PENDING
**Purpose:** Remove microsite block references from plan displays

**SEARCH FOR:**
- `microsite_blocks_limit`
- `enabled_microsite_blocks`

---

### ✅ PHASE 4: BACKEND VALIDATION CHECK (PRIORITY 4)

#### Controllers to Check:
**Status:** ⏳ PENDING

1. `app/controllers/MicrositeBlock.php`
2. `app/controllers/MicrositeBlockAjax.php`
3. Any block creation/update controllers

**LOOK FOR:**
- Plan permission checks
- Block limit validations
- Enabled block validations

---

## 🧪 TESTING CHECKLIST

### Frontend Testing:
- [ ] All blocks appear in "Add new block" modal
- [ ] No blocks show as disabled/strikethrough
- [ ] All blocks are clickable and open creation modals
- [ ] Block creation works for all types

### Admin Testing:
- [ ] Admin settings no longer show microsite block controls
- [ ] Plan creation/update no longer has block restrictions
- [ ] User management no longer has block restrictions

### Backend Testing:
- [ ] Block creation succeeds regardless of user plan
- [ ] No server-side validation errors
- [ ] All block types can be created and updated

---

## 🔄 ROLLBACK INSTRUCTIONS

If rollback is needed, restore these files from backup:
1. `app/includes/enabled_microsite_blocks.php`
2. `themes/phoenix/views/link/settings/microsite_link_create_modal.php`
3. `themes/phoenix/views/admin/settings/partials/links.php`
4. `themes/phoenix/views/admin/plan-create/index.php`
5. `themes/phoenix/views/admin/plan-update/index.php`
6. `themes/phoenix/views/admin/user-update/index.php`

---

## 📝 IMPLEMENTATION NOTES

**Current Implementation Status:**
- Started: 2025-01-23 18:41:45
- Phase 1: ✅ COMPLETED
- Phase 2: ✅ MOSTLY COMPLETED (need user-update file)
- Phase 3: ⏳ PENDING
- Phase 4: ⏳ PENDING

**COMPLETED FILES:**
✅ app/includes/enabled_microsite_blocks.php - All blocks now returned without filtering
✅ themes/phoenix/views/link/settings/microsite_link_create_modal.php - Plan checks removed
✅ themes/phoenix/views/admin/settings/partials/links.php - Available blocks section removed
✅ themes/phoenix/views/admin/plan-create/index.php - Block controls removed
✅ themes/phoenix/views/admin/plan-update/index.php - Block controls removed

**REMAINING FILES:**
⏳ themes/phoenix/views/admin/user-update/index.php - Remove block controls
⏳ Phase 3 & 4 cleanup files

**Next Steps for AI Agent:**
1. Complete Phase 1 files (critical for functionality)
2. Move through phases sequentially
3. Test after each phase
4. Remove this tracking document when complete

---

## 🎯 SUCCESS CRITERIA

✅ **Complete when:**
- All users can see and use all 34+ microsite blocks
- No plan-based restrictions in UI or backend
- Admin interface is cleaned up
- All testing passes
- This tracking document is removed

---

**⚠️ IMPORTANT:** Remove this file (`MICROSITE_BLOCKS_REMOVAL_IMPLEMENTATION.md`) after successful implementation and testing.
