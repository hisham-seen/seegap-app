<?php defined('SEEGAP') || die() ?>

<?php

/* Create the statistics object */
$partial = new \stdClass();
$partial->has_datepicker = true;

/* Get the products statistics */
$products_chart = [];
$products_chart_labels = [];

/* Generate the chart data */
for($i = 0; $i < $data->total_days; $i++) {
    $current_date = (new \DateTime($data->datetime['start_date']))->modify('+' . $i . ' day')->format('Y-m-d');
    
    $products_chart_labels[] = $current_date;
    $products_chart[$current_date] = [
        'total_products' => 0,
        'enabled_products' => 0,
        'disabled_products' => 0,
    ];
}

/* Get products data from database */
$result = database()->query("
    SELECT 
        DATE(`datetime`) as `date`,
        COUNT(*) as `total_products`,
        SUM(CASE WHEN `is_enabled` = 1 THEN 1 ELSE 0 END) as `enabled_products`,
        SUM(CASE WHEN `is_enabled` = 0 THEN 1 ELSE 0 END) as `disabled_products`
    FROM 
        `products` 
    WHERE 
        (`datetime` BETWEEN '{$data->datetime['query_start_date']}' AND '{$data->datetime['query_end_date']}')
    GROUP BY 
        DATE(`datetime`)
    ORDER BY 
        `datetime`
");

while($row = $result->fetch_object()) {
    if(array_key_exists($row->date, $products_chart)) {
        $products_chart[$row->date]['total_products'] = (int) $row->total_products;
        $products_chart[$row->date]['enabled_products'] = (int) $row->enabled_products;
        $products_chart[$row->date]['disabled_products'] = (int) $row->disabled_products;
    }
}

/* Get totals */
$total_products = database()->query("SELECT COUNT(*) as `total` FROM `products` WHERE (`datetime` BETWEEN '{$data->datetime['query_start_date']}' AND '{$data->datetime['query_end_date']}')")->fetch_object()->total ?? 0;
$enabled_products = database()->query("SELECT COUNT(*) as `total` FROM `products` WHERE `is_enabled` = 1 AND (`datetime` BETWEEN '{$data->datetime['query_start_date']}' AND '{$data->datetime['query_end_date']}')")->fetch_object()->total ?? 0;
$disabled_products = database()->query("SELECT COUNT(*) as `total` FROM `products` WHERE `is_enabled` = 0 AND (`datetime` BETWEEN '{$data->datetime['query_start_date']}' AND '{$data->datetime['query_end_date']}')")->fetch_object()->total ?? 0;
$products_with_gs1_links = database()->query("SELECT COUNT(*) as `total` FROM `products` WHERE `gs1_link_id` IS NOT NULL AND (`datetime` BETWEEN '{$data->datetime['query_start_date']}' AND '{$data->datetime['query_end_date']}')")->fetch_object()->total ?? 0;

/* Get category breakdown */
$categories_result = database()->query("
    SELECT 
        `category`,
        COUNT(*) as `total`
    FROM 
        `products` 
    WHERE 
        (`datetime` BETWEEN '{$data->datetime['query_start_date']}' AND '{$data->datetime['query_end_date']}')
        AND `category` IS NOT NULL 
        AND `category` != ''
    GROUP BY 
        `category`
    ORDER BY 
        `total` DESC
    LIMIT 10
");

$categories_chart = [];
while($row = $categories_result->fetch_object()) {
    $categories_chart[] = [
        'category' => $row->category,
        'total' => (int) $row->total
    ];
}

/* Get brand breakdown */
$brands_result = database()->query("
    SELECT 
        `brand_name`,
        COUNT(*) as `total`
    FROM 
        `products` 
    WHERE 
        (`datetime` BETWEEN '{$data->datetime['query_start_date']}' AND '{$data->datetime['query_end_date']}')
        AND `brand_name` IS NOT NULL 
        AND `brand_name` != ''
    GROUP BY 
        `brand_name`
    ORDER BY 
        `total` DESC
    LIMIT 10
");

$brands_chart = [];
while($row = $brands_result->fetch_object()) {
    $brands_chart[] = [
        'brand' => $row->brand_name,
        'total' => (int) $row->total
    ];
}

/* Get recent products */
$recent_products_result = database()->query("
    SELECT 
        `product_id`,
        `gtin`,
        `product_name`,
        `brand_name`,
        `category`,
        `is_enabled`,
        `datetime`,
        `user_id`
    FROM 
        `products` 
    WHERE 
        (`datetime` BETWEEN '{$data->datetime['query_start_date']}' AND '{$data->datetime['query_end_date']}')
    ORDER BY 
        `datetime` DESC
    LIMIT 10
");

$recent_products = [];
while($row = $recent_products_result->fetch_object()) {
    $recent_products[] = $row;
}

$partial->html = '
<div class="row mb-4">
    <div class="col-12 col-xl-3 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex">
                <div class="d-flex flex-column">
                    <span class="h3">' . nr($total_products) . '</span>
                    <span class="text-muted">' . l('admin_statistics.products.total_products') . '</span>
                </div>
                <div class="ml-auto">
                    <div class="icon-container bg-primary-100 text-primary-600">
                        <i class="fas fa-fw fa-box"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-3 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex">
                <div class="d-flex flex-column">
                    <span class="h3">' . nr($enabled_products) . '</span>
                    <span class="text-muted">' . l('admin_statistics.products.enabled_products') . '</span>
                </div>
                <div class="ml-auto">
                    <div class="icon-container bg-success-100 text-success-600">
                        <i class="fas fa-fw fa-check-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-3 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex">
                <div class="d-flex flex-column">
                    <span class="h3">' . nr($disabled_products) . '</span>
                    <span class="text-muted">' . l('admin_statistics.products.disabled_products') . '</span>
                </div>
                <div class="ml-auto">
                    <div class="icon-container bg-gray-100 text-gray-600">
                        <i class="fas fa-fw fa-times-circle"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-3 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex">
                <div class="d-flex flex-column">
                    <span class="h3">' . nr($products_with_gs1_links) . '</span>
                    <span class="text-muted">' . l('admin_statistics.products.with_gs1_links') . '</span>
                </div>
                <div class="ml-auto">
                    <div class="icon-container bg-info-100 text-info-600">
                        <i class="fas fa-fw fa-barcode"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12 col-xl-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h3 class="h5 mb-0">' . l('admin_statistics.products.products_chart') . '</h3>
            </div>
            <div class="card-body">
                <canvas id="products_chart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h3 class="h5 mb-0">' . l('admin_statistics.products.categories_chart') . '</h3>
            </div>
            <div class="card-body">
                <canvas id="categories_chart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-12 col-xl-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h3 class="h5 mb-0">' . l('admin_statistics.products.brands_chart') . '</h3>
            </div>
            <div class="card-body">
                <canvas id="brands_chart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0">
                <h3 class="h5 mb-0">' . l('admin_statistics.products.recent_products') . '</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>' . l('products.table.gtin') . '</th>
                                <th>' . l('products.table.product_name') . '</th>
                                <th>' . l('products.table.brand_name') . '</th>
                                <th>' . l('products.table.category') . '</th>
                                <th>' . l('products.table.status') . '</th>
                                <th>' . l('products.table.datetime') . '</th>
                            </tr>
                        </thead>
                        <tbody>';

if(count($recent_products)) {
    foreach($recent_products as $product) {
        $partial->html .= '
                            <tr>
                                <td><span class="badge badge-light">' . $product->gtin . '</span></td>
                                <td>' . ($product->product_name ?: '-') . '</td>
                                <td>' . ($product->brand_name ?: '-') . '</td>
                                <td>' . ($product->category ?: '-') . '</td>
                                <td>
                                    <span class="badge badge-' . ($product->is_enabled ? 'success' : 'secondary') . '">
                                        ' . ($product->is_enabled ? l('global.active') : l('global.disabled')) . '
                                    </span>
                                </td>
                                <td><span class="text-muted" data-toggle="tooltip" title="' . \SeeGap\Date::get($product->datetime, 1) . '">' . \SeeGap\Date::get($product->datetime, 2) . '</span></td>
                            </tr>';
    }
} else {
    $partial->html .= '
                            <tr>
                                <td colspan="6" class="text-center text-muted">' . l('admin_statistics.products.no_data') . '</td>
                            </tr>';
}

$partial->html .= '
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>';

$partial->javascript = '
<script src="' . ASSETS_FULL_URL . 'js/libraries/Chart.bundle.min.js?v=' . PRODUCT_CODE . '"></script>

<script>
    "use strict";

    Chart.defaults.global.elements.line.borderWidth = 4;
    Chart.defaults.global.elements.point.radius = 3;
    Chart.defaults.global.elements.point.borderWidth = 0;

    let products_chart_labels = ' . json_encode($products_chart_labels) . ';
    let products_chart_total = ' . json_encode(array_column($products_chart, 'total_products')) . ';
    let products_chart_enabled = ' . json_encode(array_column($products_chart, 'enabled_products')) . ';
    let products_chart_disabled = ' . json_encode(array_column($products_chart, 'disabled_products')) . ';

    let categories_chart_labels = ' . json_encode(array_column($categories_chart, 'category')) . ';
    let categories_chart_data = ' . json_encode(array_column($categories_chart, 'total')) . ';

    let brands_chart_labels = ' . json_encode(array_column($brands_chart, 'brand')) . ';
    let brands_chart_data = ' . json_encode(array_column($brands_chart, 'total')) . ';

    /* Products Chart */
    let products_chart = document.getElementById("products_chart").getContext("2d");

    new Chart(products_chart, {
        type: "line",
        data: {
            labels: products_chart_labels,
            datasets: [
                {
                    label: "' . l('admin_statistics.products.total_products') . '",
                    data: products_chart_total,
                    backgroundColor: "rgba(54, 162, 235, 0.1)",
                    borderColor: "rgba(54, 162, 235, 1)",
                    fill: true
                },
                {
                    label: "' . l('admin_statistics.products.enabled_products') . '",
                    data: products_chart_enabled,
                    backgroundColor: "rgba(75, 192, 192, 0.1)",
                    borderColor: "rgba(75, 192, 192, 1)",
                    fill: true
                },
                {
                    label: "' . l('admin_statistics.products.disabled_products') . '",
                    data: products_chart_disabled,
                    backgroundColor: "rgba(255, 99, 132, 0.1)",
                    borderColor: "rgba(255, 99, 132, 1)",
                    fill: true
                }
            ]
        },
        options: chart_options
    });

    /* Categories Chart */
    let categories_chart = document.getElementById("categories_chart").getContext("2d");

    new Chart(categories_chart, {
        type: "doughnut",
        data: {
            labels: categories_chart_labels,
            datasets: [{
                data: categories_chart_data,
                backgroundColor: [
                    "rgba(54, 162, 235, 0.8)",
                    "rgba(255, 99, 132, 0.8)",
                    "rgba(255, 205, 86, 0.8)",
                    "rgba(75, 192, 192, 0.8)",
                    "rgba(153, 102, 255, 0.8)",
                    "rgba(255, 159, 64, 0.8)",
                    "rgba(199, 199, 199, 0.8)",
                    "rgba(83, 102, 255, 0.8)",
                    "rgba(255, 99, 255, 0.8)",
                    "rgba(99, 255, 132, 0.8)"
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: "bottom"
            }
        }
    });

    /* Brands Chart */
    let brands_chart = document.getElementById("brands_chart").getContext("2d");

    new Chart(brands_chart, {
        type: "bar",
        data: {
            labels: brands_chart_labels,
            datasets: [{
                label: "' . l('admin_statistics.products.products_count') . '",
                data: brands_chart_data,
                backgroundColor: "rgba(54, 162, 235, 0.8)",
                borderColor: "rgba(54, 162, 235, 1)",
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        precision: 0
                    }
                }]
            },
            legend: {
                display: false
            }
        }
    });
</script>';

return $partial;
?>
