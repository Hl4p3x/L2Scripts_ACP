<script src="/templates/kertas/assets/jspages/inventory_show.js"></script>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- BEGIN SIDEBAR -->
    <?php include ROOT_PATH."/views/sidebar.php"; ?>
    <!-- END SIDEBAR -->
    <!-- BEGIN MODALS -->
    <?php include ROOT_PATH."/views/modals.php"; ?>
    <!-- END MODALS -->
    <div id="global-content">
        <!-- BEGIN CONTENT -->
        <aside class="right-side">
            <!-- BEGIN CONTENT HEADER -->
            <section class="content-header">
                <i class="fa fa-puzzle-piece"></i>
                <span><?php echo _s("INVENTORY"); ?></span>
                <ol class="breadcrumb">
                    <li><?php echo _s("CHARACTERS"); ?></li>
                    <li class="active"><?php echo _s("INVENTORY"); ?></li>
                </ol>
            </section>
            <!-- END CONTENT HEADER -->

            <!-- BEGIN MAIN CONTENT -->
            <section class="content">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-9">
                        <div id="inventory" class="inline" style="min-width: 748px">
                            <div id="char_img">
                                <img src="/media/characters/<?php echo $this->avatar; ?>"/>
                            </div>
                                <div class="mini-shells">
                                    <table class="inventory-table">
                                        <tbody><tr>
                                            <td class="first last">
                                                <img src="/templates/kertas/assets/images/inventory/inv_05.jpg" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="first last">
                                                <img src="/templates/kertas/assets/images/inventory/inv_05.jpg" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="first last">
                                                <img src="/templates/kertas/assets/images/inventory/inv_05.jpg" alt="">
                                            </td>
                                        </tr>
                                        </tbody></table>
                                </div>
                                <div class="foot-shells">
                                    <table class="inventory-table inventory-table-mini">
                                        <tbody><tr>
                                            <td class="first">
                                                <img src="/templates/kertas/assets/images/inventory/inv_46.png" alt="">
                                            </td>
                                            <td>
                                                <img src="/templates/kertas/assets/images/inventory/inv_46.png" alt="">
                                            </td>
                                            <td class="last">
                                                <img src="/templates/kertas/assets/images/inventory/inv_46.png" alt="">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="first">
                                                <img src="/templates/kertas/assets/images/inventory/inv_46.png" alt="">
                                            </td>
                                            <td>
                                                <img src="/templates/kertas/assets/images/inventory/inv_46.png" alt="">
                                            </td>
                                            <td class="last">
                                                <img src="/templates/kertas/assets/images/inventory/inv_46.png" alt="">
                                            </td>
                                        </tr>
                                        </tbody></table>
                                    <table class="inventory-table">
                                        <tbody><tr>
                                            <td class="first last">
                                                <img src="/templates/kertas/assets/images/inventory/inv_48.jpg" alt="">
                                            </td>
                                        </tr>
                                        </tbody></table>
                                </div>
                                <div class="left-shells pull-left">
                                    <table class="inventory-table">
                                        <tbody>
                                        <?php for ($i=0; $i<9; $i++): ?>
                                            <tr>
                                                <td class="first last">
                                                    <?php if (isset($this->paperdoll_left[$i])): ?>
                                                    <img
                                                        class="item_hover"
    													item-name="<?php echo $this->paperdoll_left[$i]['name']; ?>"
    													item-sa="<?php echo $this->paperdoll_left[$i]['sa']; ?>"
    													item-enchant="<?php echo $this->paperdoll_left[$i]['enchant']; ?>"
    													item-grade="<?php echo $this->paperdoll_left[$i]['grade']; ?>"
    													item-amount="<?php echo $this->paperdoll_left[$i]['amount']; ?>"
    													item-type="<?php echo $this->paperdoll_left[$i]['type']; ?>"
    													item-id="<?php echo $this->paperdoll_left[$i]['id']; ?>"
    													item-auctionable="<?php echo $this->paperdoll_left[$i]['auctionable']; ?>"
    													src="/media/item/<?php echo $this->paperdoll_left[$i]['icon']; ?>"/>
    												<?php else: ?>
                                                    <img src="/templates/kertas/assets/images/inventory/new/inv_l_<?php echo $i; ?>.jpg" alt="">
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </div><!-- right shells -->
                                <div class="right-shells pull-left">
                                    <table class="inventory-table">
                                        <tbody>
                                        <?php for ($i=0; $i<9; $i++): ?>
                                            <tr>
                                                <td class="first last">
                                                    <?php if (isset($this->paperdoll_right[$i])): ?>
                                                    <img
                                                        class="item_hover"
    													item-name="<?php echo $this->paperdoll_right[$i]['name']; ?>"
    													item-sa="<?php echo $this->paperdoll_right[$i]['sa']; ?>"
    													item-enchant="<?php echo $this->paperdoll_right[$i]['enchant']; ?>"
    													item-grade="<?php echo $this->paperdoll_right[$i]['grade']; ?>"
    													item-amount="<?php echo $this->paperdoll_right[$i]['amount']; ?>"
    													item-type="<?php echo $this->paperdoll_right[$i]['type']; ?>"
    													item-id="<?php echo $this->paperdoll_right[$i]['id']; ?>"
    													item-auctionable="<?php echo $this->paperdoll_right[$i]['auctionable']; ?>"
    													src="/media/item/<?php echo $this->paperdoll_right[$i]['icon']; ?>"/>
    												<?php else: ?>
                                                    <img src="/templates/kertas/assets/images/inventory/new/inv_r_<?php echo $i; ?>.jpg" alt="">
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endfor; ?>
                                        </tbody>
                                    </table>
                                </div><!-- right shells -->
                                <div class="inventory-main invent pull-left">
                                    <div class="inventory-head">
                                        <div class="inventory-name pull-left"><b><?php echo $this->char_name; ?></b> <?php echo _s("INVENTORY"); ?></div>
                                        <div class="inventory-cols pull-right"><b><?php echo count($this->inventory); ?></b>/100</div>
                                        <div class="clear"></div>
                                    </div>
                                    <table class="inventory-table">
                                        <tbody>
                                            <?php for ($i=0; $i<7; $i++): ?>
                                            <tr>
                                                <?php for ($o=0; $o<15; $o++): ?><td>
                                                    <?php $idx = ($i*15)+$o; if (isset($this->inventory[$idx])): ?>
                                                    <img
                                                        class="item_hover"
    													item-name="<?php echo $this->inventory[$idx]['name']; ?>"
    													item-sa="<?php echo $this->inventory[$idx]['sa']; ?>"
    													item-enchant="<?php echo $this->inventory[$idx]['enchant']; ?>"
    													item-grade="<?php echo $this->inventory[$idx]['grade']; ?>"
    													item-amount="<?php echo $this->inventory[$idx]['amount']; ?>"
    													item-type="<?php echo $this->inventory[$idx]['type']; ?>"
    													item-id="<?php echo $this->inventory[$idx]['id']; ?>"
    													item-auctionable="<?php echo $this->inventory[$idx]['auctionable']; ?>"
    													src="/media/item/<?php echo $this->inventory[$idx]['icon']; ?>"/>
    												<?php endif; ?>
                                                </td><?php endfor; ?>
                                            </tr>
                                            <?php endfor; ?>
                                        </tbody>
                                    </table>
                                    <div class="inventory-foot">
                                        <div class="inventory-money">
                                            <div class="money-icon inline">
                                                <img src="/templates/kertas/assets/images/money-icon.png" alt="">
                                            </div>
                                            <div class="money-wrap"><?php echo $this->adena; ?></div><!-- money -->
                                        </div>
                                    </div>
                                </div>
                                <div class="clear"></div>

                                <div class="modal-l2-wp modal-l2-auctioned-items" style="display: none;">
                                    <div class="modal-l2-price">
                                        <input type="number" placeholder="<?php echo _s("START_PRICE"); ?>"/>
                                    </div>

                                    <div class="modal-l2-priceBuyNow">
                                        <input type="number" placeholder="<?php echo _s("BUY_NOW"); ?>"/>
                                    </div>

                                    <div class="modal-l2-priceStep">
                                        <input type="number" placeholder="<?php echo _s("PRICE_UP_BY"); ?>"/>
                                    </div>

                                    <div class="modal-l2-period">
                                        <input type="hidden" value="1"/>
                                    </div>

                                    <div class="modal-l2-count">
                                        <input type="number" placeholder="<?php echo _s("COUNT"); ?>"/>
                                    </div>

            						<div class="modal-l2-renewal">
            							<input type="hidden" value="0">
            						</div>

                                    <div class="modal-l2-auctioned-items-button">
                                        <div class="modal-l2-confirm">
                                            <a href="#" class="l2-button" modal-l2-type="auctioned"><?php echo _s("CONFIRM"); ?></a>
                                        </div>
                                        <div class="modal-l2-cancel">
                                            <a href="#" class="l2-button"><?php echo _s("CANCEL"); ?></a>
                                        </div>
                                    </div>
                                </div>

                            <div class="modal-l2-wp modal-l2-no-auctioned" style="display: none;">
                                <div class="delete-l2-items-info_left">
                                    <img src="/templates/kertas/assets/img/l2/attention-l2-modal.jpg"/>
                                </div>
                                <div class="delete-l2-items-info_right">
                                    <?php echo _s("ITEM_NOT_AUCTION"); ?>                        </div>
                                <div class="modal-l2-auctioned-items-button">
                                    <div class="modal-l2-cancel">
                                        <a href="#" class="l2-button"><?php echo _s("CANCEL"); ?></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                    </div>
                </div>
            </section>
            <!-- END MAIN CONTENT -->
        </aside>
        <div id="item_title" >
            <div class="item_enchant"></div>
            <div class="item_name"></div>
            <div class="item_ls"></div>
            <div class="item_grade"><div class="#item_grade"></div><img id="grade_img" src="/media/grade/a-grade.jpg"></div>
            <div class="item_amount"></div>
        </div>
        <div id="item-actions">
            <div class="item-actions-el">
                <div class="send-auction"><?php echo _s("ITEM_ACOUNTION"); ?></div>
            </div>
        </div>
    </div>
</div>