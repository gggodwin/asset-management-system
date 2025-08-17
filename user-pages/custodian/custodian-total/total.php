<?php
$modid = $_SESSION['modid'];
$totalItemsLabel = ($modid == 1) ? 'List of Tagged Items' : 'List of Items';

$cards = [
    [
        'id' => 'totalItems',
        'bg' => 'primary',
        'icon' => '<path d="M12 21l-8 -4.5v-9l8 -4.5l8 4.5v4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12v9" /><path d="M12 12l-8 -4.5" /><path d="M22 18h-7" /><path d="M18 15l-3 3l3 3" />',
        'label' => $totalItemsLabel,
        'filter' => ''
    ],
    [
        'id' => 'totalCategories',
        'bg' => 'info',
        'icon' => '<path d="M4 4h6v6h-6z" /><path d="M14 4h6v6h-6z" /><path d="M4 14h6v6h-6z" /><path d="M14 14h6v6h-6z" />',
        'label' => 'List of Categories',
        'filter' => ''
    ],
    [
        'id' => ($_SESSION['modid'] == 6) ? 'rrList' : 'eqTagging',
        'bg' => 'success',
        'icon' => '<path d="M9 12l2 2l4 -4" /><path d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />',
        'label' => ($_SESSION['modid'] == 6) ? 'Record of Items Received' : 'Inventory of Tagged Equipment',
        'filter' => ($_SESSION['modid'] == 6) ? 'show-rr-modal' : 'show-eq-modal'
    ],
    [
        'id' => 'prsList',
        'bg' => 'primary',
        'icon' => '<path d="M17 17v-13a2 2 0 0 0 -2 -2h-10a2 2 0 0 0 -2 2v13a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2z" /><path d="M7 7h6" /><path d="M7 11h6" /><path d="M7 15h4" /><path d="M17 9l4 4l-4 4" />',
        'label' => 'Incoming & Completed Orders',
        'filter' => 'prs'
    ],
    [
        'id' => 'dfList',
        'bg' => 'info',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-rocket"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 13a8 8 0 0 1 7 7a6 6 0 0 0 3 -5a9 9 0 0 0 6 -8a3 3 0 0 0 -3 -3a9 9 0 0 0 -8 6a6 6 0 0 0 -5 3" /><path d="M7 14a6 6 0 0 0 -3 6a6 6 0 0 0 6 -3" /><path d="M15 9m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" /></svg>',
        'label' => 'List of Claim Forms',
        'filter' => 'show-df-modal'
    ],
    [
        'id' => 'transferList',
        'bg' => 'dark',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-transfer"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M20 10h-16l5.5 -6" /><path d="M4 14h16l-5.5 6" /></svg>',
        'label' => 'Transfer Records',
        'filter' => 'show-transfer-modal'
    ],
    [
        'id' => 'expensesList',
        'bg' => 'warning',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-currency-peso"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 19v-14h3.5a4.5 4.5 0 1 1 0 9h-3.5" /><path d="M18 8h-12" /><path d="M18 11h-12" /></svg>',
        'label' => 'Detailed Expenses',
        'filter' => 'expenses'
    ],
];
?>

<div class="row g-3 mb-3">
<h4 class="fw-bold text-muted mb-1">📋 List of Available Records</h4>
<hr class="mt-2 mb-3" style="border-top: 2px solid black;">
    <?php foreach ($cards as $card) :
        if ($card['id'] === 'transferList' && $_SESSION['modid'] != 1) {
            continue;
        }
        if ($card['id'] === 'totalCategories' && $_SESSION['modid'] != 6) {
            continue;
        }
        $id = htmlspecialchars($card['id']);
        $label = htmlspecialchars($card['label']);
    ?>
        <div class="col-sm-6 col-lg-3">
            <div class="card card-sm cursor-pointer" id="<?= $id ?>Card" data-filter="<?= htmlspecialchars($card['filter']) ?>">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <span class="bg-<?= htmlspecialchars($card['bg']) ?> text-white avatar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <?= $card['icon'] ?>
                                </svg>
                            </span>
                        </div>
                        <div class="col">
                            <div class="fs-3 fw-semi-bold">
                                <?= $label ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
