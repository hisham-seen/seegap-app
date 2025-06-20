<?php
if(
    !empty(settings()->ads->header_microsite)
    && !$data->user->plan_settings->no_ads
): ?>
    <div class="container my-3 d-print-none"><?= settings()->ads->header_microsite ?></div>
<?php endif ?>
