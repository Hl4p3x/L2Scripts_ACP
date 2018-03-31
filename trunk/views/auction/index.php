<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- BEGIN SIDEBAR -->
    <?php include ROOT_PATH."/views/sidebar.php"; ?>
    <!-- END SIDEBAR -->
    <!-- BEGIN MODALS -->
    <?php include ROOT_PATH."/views/modals.php"; ?>
    <script>var currentAucPage = <?php echo $this->current_page; ?>;</script>
    <script type="text/javascript" src="/templates/kertas/assets/jspages/auction.js"></script>
    <div id="global-content">
        <aside class="right-side">
            <!-- BEGIN CONTENT HEADER -->
            <section class="content-header">
                <i class="fa fa-gavel"></i>
                <span><?php echo _s("AUCTION"); ?></span>
                <ol class="breadcrumb">
                    <li class="active"><?php echo _s("AUCTION"); ?></li>
                </ol>
                <div class="available-auc">
                    <div class="available-auc-text">
                        <div class="available-auc-balance">
                            <?php echo _s("AVAIL_FOR_WITHDRAW"); ?>: <span><?php echo is_numeric($this->pending_withdraw) ? $this->pending_withdraw : 0; ?> <?php echo _s("CURRENCY"); ?></span>
                        </div>
                        <div class="available-auc-info">
                            <i class="fa fa-question-circle"></i>
                            <div class="available-auc-info-text">
                                <?php echo _s("WITHDRAW_MSG1"); ?> </div>
                        </div>
                    </div>
                    <div class="available-auc-btn">
                        <a href="/auction/withdraw"><?php echo _s("WITHDRAW"); ?></a>
                    </div>
                </div>
            </section>
            <!-- END CONTENT HEADER -->

            <!-- BEGIN MAIN CONTENT -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="auction-tool-left">
                            <div class="type-filter ">
                                <div class="type-filter-title"><?php echo _s("TYPE"); ?>:</div>
                                <div data-auc-type-el="all" class="type-filter-el active"><?php echo _s("ALL"); ?></div>
                                <div data-auc-type-el="weapon" class="type-filter-el"><?php echo _s("WEAPON"); ?></div>
                                <div data-auc-type-el="armor" class="type-filter-el"><?php echo _s("ARMOR"); ?></div>
                                <div data-auc-type-el="accessary" class="type-filter-el"><?php echo _s("JEWERLY"); ?></div>
                                <div data-auc-type-el="other" class="type-filter-el"><?php echo _s("OTHER"); ?></div>
                            </div>


                            <div class="nav-auction-page  ">
                                <div class="nav-auction-title"><?php echo _s("NAVIGATION"); ?>:</div>
                                <a<?php if ($this->auction_page == "all") echo " class=\"active\""; ?> href="/auction/index"><?php echo _s("ALL_LOTS"); ?></a>
                                <a<?php if ($this->auction_page == "created") echo " class=\"active\""; ?> href="/auction/created"><?php echo _s("MY_LOTS"); ?></a>
                                <a<?php if ($this->auction_page == "unsold") echo " class=\"active\""; ?> href="/auction/unsold"><?php echo _s("UNSOLD_LOTS"); ?></a>
                                <a<?php if ($this->auction_page == "bought") echo " class=\"active\""; ?> href="/auction/bought"><?php echo _s("BOUGHT_LOTS"); ?></a>
                                <a href="/auction/withdraw"><?php echo _s("BUY_COIN_OF_LUCK"); ?></a>
                                <a href="#" class="set-lot-modal"><?php echo _s("NEW_LOT"); ?></a>
                            </div>
                        </div>

                        <div class="auction-tool-center">

                            <div class="auction-tool-top">
                                <div class="auction-filter-top">
                                    <div class="grade-filter ">
                                        <div data-auc-grade-el="all" class="grade-filter-el active"><?php echo _s("ALL_GRADES"); ?></div>
                                        <div data-auc-grade-el="b" class="grade-filter-el">B-grade</div>
                                        <div data-auc-grade-el="c" class="grade-filter-el">C-grade</div>
                                        <div data-auc-grade-el="d" class="grade-filter-el">D-grade</div>
                                        <div data-auc-grade-el="none" class="grade-filter-el">No grade</div>
                                    </div>

                                    <div class="enchant-filter ">
                                        <span><?php echo _s("ENCHANT"); ?> +</span>
                                        <input type="text" name="enchant" maxlength="2">
                                    </div>

                                    <div class="sort-filter">
                                        <?php echo _s("SORT_BY"); ?>: <a href="#" data-sort-type="endtime" style="color: #d5cfab"><?php echo _s("DATE"); ?></a>,
                                        <a href="#" data-sort-type="price" style="color: #d5cfab"><?php echo _s("COST"); ?></a>
                                    </div>

                                </div>

                                <div class="auction-table-head">
                                    <div class="auction-table-row-head">
                                        <div class="auction-table-head-item"><?php echo _s("ITEM_NAME"); ?></div>
                                        <div class="auction-table-head-nowPrice"><?php echo _s("NOW_PRICE"); ?></div>
                                        <div class="auction-table-head-buyPrice"><?php echo _s("BUY_NOW_AUCTION"); ?></div>
                                        <div class="auction-table-head-stepPrice"><?php echo _s("STEP"); ?></div>
                                        <div class="auction-table-head-timer"><?php echo _s("TIME_LEFT"); ?></div>
                                        <div class="auction-table-head-actions"><?php echo _s("ACTION"); ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="auction-table">
                                <div class="auction-table-body">
                                    <?php include "item_list.php"; ?>
                                </div>
                            </div>
                        </div>

                        <div style="display: none;">
                            <div class="box-modal" id="modal-set-lot">
                                <div class="modal-l2-wp modal-l2-place-ber">
                                    <div class="modal-l2-delete-items-info">
                                        <div class="delete-l2-items-info_left">
                                            <img src="/templates/kertas/assets/img/l2/attention-l2-modal.jpg" />
                                        </div>
                                        <div class="delete-l2-items-info_right">
                                            <?php echo _s("CHOOSE_ITEMS_AUCTION"); ?> </div>
                                    </div>

                                    <select id="set-lot-char" name="set-lot-char">
                                        <?php foreach ($this->game_accounts as $account => $characters): ?>
                                        <?php if (count($characters) > 0): ?>
                                        <optgroup label="<?php echo $account; ?>">
                                        <?php foreach ($characters as $char): ?>
                                            <option value="<?php echo $char['char_name']; ?>"><?php echo $char['char_name']; ?></option>
                                        <?php endforeach; ?>
                                        </optgroup>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
    								</select>

                                    <div class="modal-l2-button modal-l2-place-ber-button">
                                        <div class="modal-l2-confirm">
                                            <a href="" class="l2-button"><?php echo _s("CONFIRM"); ?></a>
                                        </div>
                                        <div class="modal-l2-cancel">
                                            <a href="#" class="l2-button"><?php echo _s("CANCEL"); ?></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        	<div class="box-modal" id="modal-pace-ber">
                        		<div class="modal-l2-wp modal-l2-place-ber">
                        			<div class="modal-l2-delete-items-info">
                        				<div class="delete-l2-items-info_left">
                        					<img src="/templates/kertas/assets/img/l2/attention-l2-modal.jpg"/>
                        				</div>
                        				<div class="delete-l2-items-info_right">
                        					<?php echo _s("CONFIRM_BID"); ?> <span class="put-cost"></span> <?php echo _s("FOR"); ?> <span class="put-item"></span>?
                        				</div>
                        			</div>

                        			<div class="modal-l2-button modal-l2-place-ber-button">
                        				<div class="modal-l2-confirm">
                        					<a href="#" class="l2-button" ><?php echo _s("CONFIRM"); ?></a>
                        				</div>
                        				<div class="modal-l2-cancel">
                        					<a href="#" class="l2-button"><?php echo _s("CANCEL"); ?></a>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="box-modal" id="modal-buy-now">
                        		<div class="modal-l2-wp modal-l2-place-ber">
                        			<div class="modal-l2-delete-items-info">
                        				<div class="delete-l2-items-info_left">
                        					<img src="/templates/kertas/assets/img/l2/attention-l2-modal.jpg"/>
                        				</div>
                        				<div class="delete-l2-items-info_right">
                        					<?php echo _s("CONFIRM_BUY"); ?> <span class="buy-cost"></span> <?php echo _s("FOR"); ?> <span class="buy-item"></span>?
                        				</div>
                        			</div>

                        			<div class="modal-l2-button modal-l2-place-ber-button">
                        				<div class="modal-l2-confirm">
                        					<a href="#" class="l2-button" ><?php echo _s("CONFIRM"); ?></a>
                        				</div>
                        				<div class="modal-l2-cancel">
                        					<a href="#" class="l2-button"><?php echo _s("CANCEL"); ?></a>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

                        	<div class="box-modal" id="modal-get-lot">
                        		<div class="modal-l2-wp modal-l2-place-ber">
                        			<div class="modal-l2-delete-items-info">
                        				<div class="delete-l2-items-info_left">
                        					<img src="/templates/kertas/assets/img/l2/attention-l2-modal.jpg"/>
                        				</div>
                        				<div class="delete-l2-items-info_right">
                        					<?php echo _s("CONFIRM_GET"); ?> <span class="get-item"></span>?
                        				</div>
                        			</div>

                        			<div class="modal-l2-button modal-l2-place-ber-button">
                        				<div class="modal-l2-confirm">
                        					<a href="#" class="l2-button" ><?php echo _s("CONFIRM"); ?></a>
                        				</div>
                        				<div class="modal-l2-cancel">
                        					<a href="#" class="l2-button"><?php echo _s("CANCEL"); ?></a>
                        				</div>
                        			</div>
                        		</div>
                        	</div>

            				<div class="box-modal" id="win-item-auction">
                    			<div class="win-item-header">
                    				<div class="win-item-title"><?php echo _s("GET_BOUGHT_LOT"); ?></div>
                    				<div class="win-item-auction-close box-modal_close arcticmodal-close"></div>
                    			</div>
                    			<div class="win-item-body">
                    				<div class="win-item-list-title"><?php echo _s("GET_BOUGHT_LOT"); ?> <span class="get-item"></span></div>
                    				<div class="win-char-list-title"><?php echo _s("CHOOSE_CHAR2"); ?></div>

                    				<div class="win-char-list">
                    					<select id="win-char" name="win-char" class="form-control" >
                                            <?php foreach ($this->game_accounts as $account => $characters): ?>
                                            <?php if (count($characters) > 0): ?>
                                            <optgroup label="<?php echo $account; ?>">
                                            <?php foreach ($characters as $char): ?>
                                                <option value="<?php echo $char['char_name']; ?>"><?php echo $char['char_name']; ?></option>
                                            <?php endforeach; ?>
                                            </optgroup>
                                            <?php endif; ?>
                                            <?php endforeach; ?>
										</select>
                    				</div>

                    				<div class="win-lot-button"><?php echo _s("GET_ITEM"); ?></div>
                    			</div>
                    		</div>

                        </div>
                    </div>
                </div>
            </section>
        </aside>
    </div>
</div>