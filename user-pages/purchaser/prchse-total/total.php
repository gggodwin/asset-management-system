<style>
    .clickable-user-card {
    cursor: pointer;
    transition: box-shadow 0.2s ease;
}
.clickable-user-card:hover {
    box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
}
.active-filter-card {
    border: 2px solid #007bff;
}

</style>
<?php

$purchaseStats = $purchaser->getPurchaseSummary($db);

$purchaseCards = [
    [
        'id' => 'totalApprovedPRs',
        'bg' => 'success',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-check"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 13l4 4l8 -8" /></svg>',
        'label' => 'Approved PRs w/ Price',
        'value' => $purchaseStats['total_approved_prs'] ?? 0,
        'scroll_target' => '#prTableBody' // Add the scroll target here
    ],
    [
        'id' => 'pendingPriceUpdates',
        'bg' => 'warning',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-alert"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 8v4m0 4v.01m-7 4h14a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2z" /></svg>',
        'label' => 'Pending Price Updates',
        'value' => $purchaseStats['pending_price_updates'] ?? 0,
        'filter' => '',
        'scroll_target' => '#prTableBody'
    ],
    [
        'id' => 'upcomingPRDeadlines',
        'bg' => 'primary',
        'icon' => '<svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-checklist"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.615 20h-2.615a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h8a2 2 0 0 1 2 2v8" /><path d="M14 19l2 2l4 -4" /><path d="M9 8h4" /><path d="M9 12h2" /></svg>',
        'label' => 'All Approved PRS',
        'value' => $purchaseStats['upcoming_pr_deadlines'] ?? 0,
        'filter' => '',
        'scroll_target' => '#prTableBody'
    ],
    [
        'id' => 'supplierCount',
        'bg' => 'info',
        'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 19c-3 0 -3 -3 -3 -3s0 -3 3 -3c3 0 3 3 3 3s0 3 -3 3zm-6 0c-3 0 -3 -3 -3 -3s0 -3 3 -3c3 0 3 3 3 3s0 3 -3 3zm6 -9c0 -4.418 -3.582 -8 -8 -8s-8 3.582 -8 8h16z" /></svg>',
        'label' => 'Supplier Count',
        'value' => $purchaseStats['supplier_count'] ?? 0,
        'filter' => '',
        'modal_target' => '#supplierModal',
    ]
];

foreach ($purchaseCards as $card) :
    $id = htmlspecialchars($card['id']); // Ensure ID is safe
    $label = htmlspecialchars($card['label']); // Prevent any unwanted output
    $value = (int) $card['value']; // Ensure numeric value
    ?>
<div class="col-sm-6 col-lg-3">
    <div class="card card-sm <?= $card['id'] !== 'supplierCount' ? 'clickable-user-card' : '' ?>"
        id="<?= $id ?>Card"
        data-id="<?= $id ?>Count"
        data-scroll-target="<?= $card['scroll_target'] ?? '' ?>"
        data-bs-toggle="<?= isset($card['modal_target']) ? 'modal' : '' ?>"
        data-bs-target="<?= $card['modal_target'] ?? '' ?>"
        data-title="<?= htmlspecialchars($card['label']) ?>">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-auto">
                    <span class="bg-<?= $card['bg'] ?> text-white avatar">
                        <?= $card['icon'] ?>
                    </span>
                </div>
                <div class="col">
                    <div class="h1 mb-0 me-2">
                        <span id="<?= $id ?>Count"><?= $value ?></span>
                    </div>
                    <div class="text-secondary">
                        <?= $label ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php endforeach; ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.clickable-user-card');
    const titleElement = document.getElementById('purchaseCardTitle');

    cards.forEach(card => {
        card.addEventListener('click', () => {
            // Remove active class from all cards
            cards.forEach(c => c.classList.remove('active-filter-card'));

            // Add active class to the clicked card
            card.classList.add('active-filter-card');

            // Update the title
            const newTitle = card.getAttribute('data-title');
            if (titleElement && newTitle) {
                titleElement.textContent = newTitle;
            }

            // Scroll to target if specified
            const target = card.getAttribute('data-scroll-target');
            if (target) {
                const targetElement = document.querySelector(target);
                if (targetElement) {
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });
});
</script>



