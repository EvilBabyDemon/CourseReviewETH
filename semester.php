<?php
function addSemester($semester)
{
?>
    Took it in Semester: <br>
    <select name="semester">
        <option <?php if ($semester == null || $semester == "") {
                    print "selected";
                } ?>></option>
        <?php
        for ($i = 24; $i > 17;) {
            $fs = "FS" . $i;
            $hs = "HS" . --$i;
        ?>
            <option <?php if ($semester == $fs) {
                        print "selected";
                    } ?>> <?php print $fs ?> </option>
            <option <?php if ($semester == $hs) {
                        print "selected";
                    } ?>> <?php print $hs ?> </option>
        <?php
        }
        ?>
    </select>
<?php
}
?>