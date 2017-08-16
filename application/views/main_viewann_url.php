     <div class="row">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">相關網址</h3>
            </div>
            <ul class="list-group">
                <?php foreach ($annurl as $index => $row) :?>
                <li class="list-group-item"><a href="<?php echo $row?>">★相關網址 <?php echo $index + 1?>：<?php echo $annurlreadable[$index]?></a></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>