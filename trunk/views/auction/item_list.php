    <?php if (!isset($this->auction_items) || count($this->auction_items) == 0): ?>
    <div class="auction-table-row auction-table-row-noItem">
		<div class="auction-table-noItem"><?php echo _s("NO_AUCTIONS"); ?></div>
    </div>
    <?php else: ?>
    <?php foreach ($this->auction_items as $item): ?>
    <div class="auction-table-row" data-auction-lot-id="<?php echo $item['auction_id']; ?>">
        <div class="auction-table-img">
            <img class="auction-table-enchant<?php echo $item['enchant']; ?>" src="/media/item/<?php echo $item['icon']; ?>">
        </div>
        <div class="auction-table-name">
            <span>
                <?php echo $item['name']; ?>
                <?php if ($item['grade'] != "none"): ?>
                <img src="/media/grade/<?php echo $item['grade']; ?>-grade.jpg" alt="">
                <?php endif; ?>
                <span class="auction-item-sa"> <?php echo $item['alt_name']; ?></span>
                <span class="auction-item-enchant"><?php echo $item['enchant'] == 0 ? "" : "+".$item['enchant']; ?></span>
                <?php echo $item['count'] > 1 ? "(".$item['count'].")" : ""; ?>
            </span>
        </div>
        <div class="auction-table-nowPrice"><span><?php echo $item['current_bid']; ?></span> <?php echo _s("CURRENCY"); ?></div>
        <div class="auction-table-buyPrice"><?php echo $item['buynow_price']; ?> <?php echo _s("CURRENCY"); ?></div>
        <div class="auction-table-stepPrice"><span><?php echo $item['step']; ?></span> <?php echo _s("CURRENCY"); ?></div>
        <?php if ($this->auction_page == "unsold" || $this->auction_page == "bought"): ?>
        <div class="auction-table-timer-end"><?php echo _s("ZERO_TIMER"); ?></div>
        <?php else: ?>
        <div class="auction-table-timer" data-auction-end="<?php echo $item['end']; ?>"></div>
        <?php endif; ?>
        <div class="auction-table-actions">
            <?php if ($this->auction_page == "all"): ?>
            <button class="auction-button" onclick="buyAuction(this);" data-auction-btn-type="place_bet"><?php echo _s("DO_BID"); ?></button>
            <button class="auction-button" onclick="buyAuction(this);" data-auction-btn-type="buy_now"><?php echo _s("BUY_NOW_AUCTION"); ?></button>
            <?php elseif ($this->auction_page == "created"): ?>
            <button class="auction-button auction-button-get" onclick="buyAuction(this)" data-auction-btn-type="get_lot"><?php echo _s("REMOVE_LOT"); ?></button>
            <?php elseif ($this->auction_page == "unsold"): ?>
            <button class="auction-button auction-button-get" onclick="buyAuction(this)" data-auction-btn-type="get_lot"><?php echo _s("GET_ITEM"); ?></button>
            <?php elseif ($this->auction_page == "bought"): ?>
            <button class="auction-button auction-button-get" onclick="buyAuction(this)" data-auction-btn-type="get_bought"><?php echo _s("GET_ITEM"); ?></button>
            <?php elseif ($this->auction_page == "bids"): ?>
            <button class="auction-button" onclick="buyAuction(this);" data-auction-btn-type="buy_now"><?php echo _s("BUY_NOW_AUCTION"); ?></button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
    <div class="auction-table-row auction-nav-row">
        <div class="auction-nav">
            <?php if ($this->total_pages > 4): ?>
            <div class="auction-nav-prev" onclick="navigationAuc(1);"><i class="fa fa-chevron-left"></i></div>
            <?php endif; ?>
            <?php for ($i=max(1,$this->current_page-3); $i<=min($this->total_pages,$this->current_page+3); $i++): ?>
            <?php if ($i==$this->current_page): ?>
            <div class="auction-nav-el active"><?php echo $i; ?></div>
            <?php else: ?>
            <div class="auction-nav-el" onclick="navigationAuc(<?php echo $i; ?>);"><?php echo $i; ?></div>
            <?php endif; ?>
            <?php endfor; ?>


			<?php if ($this->total_pages > 4): ?>
			<div class="auction-nav-next" onclick="navigationAuc(<?php echo $this->total_pages; ?>);"><i class="fa fa-chevron-right"></i></div>
			<?php endif; ?>
		</div>
	</div>
	<?php endif; ?>