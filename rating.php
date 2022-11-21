<?php
function rating(string $text, string $tooltip, string $name, $default, $count)
{
    print $text;
?>
    <div class="tooltip">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
            <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
        </svg>
        <span class="tooltiptext"><?php print $tooltip ?></span>
    </div>
    <div class="rating">
        <?php
        for ($i = 5; $i > 0; $i--) {
            $checked = "";
            if ($i == $default) {
                $checked = "checked";
            }
            print "<input type=\"radio\" name=\"$name\" value=\"$i\" id=\"$count$name$i\" $checked><label for=\"$count$name$i\">☆</label>";
        }
        ?>
    </div>
<?php
}
function includeRating($check, $count)
{
    if ($check == null) {
        $check = [0, 0, 0, 0, 0];
    }
    rating("Would <b>recommend</b> it ", "1 no, 5 yes", "Recommended", $check[0], $count);
    rating("<b>Interesting</b> content ", "1 boring, 5 very interesting", "Interesting", $check[1], $count);
    rating("Approriate <b>difficulty</b> ", "1 very hard, 5 very easy", "Difficulty", $check[2], $count);
    rating("Approriate amount of <b>effort</b> ", "1 worst, 5 best", "Effort", $check[3], $count);
    rating("Amount and quality of <b>resources</b> ", "1 worst, 5 best", "Resources", $check[4], $count);
}
?>