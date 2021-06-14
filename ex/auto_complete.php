<?php

include_once("./functions.php");

// searching and showing autocomplete result
if(!empty($_POST["keyword"])) {
    $keyword = $_POST["keyword"];

    $result = keyword_search($keyword);
    if(!empty($result)) { ?>

        <ul id="suggestion-list">
        <?php
        foreach($result as $this_item) {
        ?>
            <li onClick="selectText('<?php echo $this_item; ?>');"><?php echo $this_item; ?></li>
        <?php } ?>
        </ul>

<?php } } ?>
<?php

// searching and showing autocomplete result for charts
if(!empty($_POST["keyword2"])) {
    $keyword2 = $_POST["keyword2"];

    $result = keyword_search($keyword2);
    if(!empty($result)) { ?>

        <ul id="suggestion-list">
        <?php
        foreach($result as $this_item) {
        ?>
            <li onClick="selectText2('<?php echo $this_item; ?>');"><?php echo $this_item; ?></li>
        <?php } ?>
        </ul>

<?php } } ?>
<?php

// searching and showing autocomplete result for delays
if(!empty($_POST["keyword3"])) {
    $keyword3 = $_POST["keyword3"];

    $result = route_keyword_search($keyword3);
    if(!empty($result)) { ?>

        <ul id="suggestion-list">
        <?php
        foreach($result as $this_item) {
        ?>
            <li onClick="selectText3('<?php echo $this_item; ?>');"><?php echo $this_item; ?></li>
        <?php } ?>
        </ul>

<?php } } ?>
