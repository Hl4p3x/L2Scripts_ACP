
                                                <select name="account" class="acc-selectlist" onchange="refreshChars();">
                                                    <?php foreach ($this->accounts as $account): ?>
                                                    <option value="<?php echo $account; ?>"<?php echo $this->selected_account == $account ? " selected=selected" : ""?>><?php echo $account; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
