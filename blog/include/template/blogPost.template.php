<h2 style="margin-bottom:4px"><?= $subject ?><?= $draft?" <span class=\"draft\">KLADDE</span>":"" ?></h2>
<div class="small1" style="color:#666666"><?= $posted ?> <?= $draft?" <span class=\"draft\">".convertToUppercase($lGeneral["Draft"])."</span>":"" ?></div>

<?= !empty($text)?$text:"" ?>

<p class="small1" style="color:#666666"><?= !empty($readMoreLink)?$readMoreLink." | ":"" ?> <?= !empty($categoryLinks)?$lBlogPost["PostedIn"]." ".$categoryLinks:"" ?> <?= $lBlogPost["By"] ?> <?= $authorLink ?></p>