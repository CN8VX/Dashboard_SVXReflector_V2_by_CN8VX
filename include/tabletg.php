<table class="stg">
        <tr>
        <th>TG #</th>
        <th>TG Name</th>
        </tr>
        <?php
        foreach ($tgdb_array as $tg => $tgname)
        { 
                echo "<td align=\"center\"><span>$tg</span></td>";
                echo "<td><b>".$tgname."</b></td>";
                echo"</tr>\n";
        };

        ?>
</table>

