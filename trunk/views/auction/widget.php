<div class="auction-widget-wp">
    <div class="auction-widget-title"><?php echo _s("AUCTION"); ?></div>
    <div class="auction-widget-overflow">
        <?php foreach ($this->auction_items as $auction): ?>
        <div class="auction-widget-row">
            <div class="auction-widget-box">
                <div class="auction-widget-image">
                    <img src="/media/item/<?php echo $auction['item_icon']; ?>">
                </div>
                <div class="auction-widget-name">
                    <span><?php echo $auction['item_name']; ?>
                    <?php if ($auction['item_grade'] != "none"): ?>
                    <img src="/media/grade/<?php echo $auction['item_grade']; ?>-grade.jpg" alt="">
                    <?php endif; ?>
                    <span class="auction-item-sa"><?php echo $auction['item_altname']; ?></span>
                    <span class="auction-item-enchant"><?php echo $auction['item_enchant']; ?></span>
                    </span>
                </div>
                <div class="auction-widget-cost"><span><?php echo $auction['current_bid']; ?></span> <?php echo _s("CURRENCY"); ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <div class="auction-widget-button">
        <a href="/auction/index"><?php echo _s("GO_TO_AUCTION"); ?></a>
    </div>
</div>