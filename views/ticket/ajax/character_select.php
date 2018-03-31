
                                                <select name="character" class="char-selectlist">
                                                    <?php foreach ($this->characters as $char): ?>
                                                    <option value="<?php echo $char['char_name']; ?>"><?php echo $char['char_name']; ?></option>
                                                <?php endforeach; ?>
                                                </select>
