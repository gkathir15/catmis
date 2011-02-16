<h2 style="margin-bottom:4px"><?= $subject ?></h2>
<div class="small1" style="color:#666666"><?= $posted ?> <?= $draft?" <span class=\"draft\">".convertToUppercase($lGeneral["Draft"])."</span>":"" ?></div>

<?= !empty($summary)?$summary:"" ?>

<p class="small1" style="color:#666666"><?= !empty($readMoreLink)?$readMoreLink." | ":"" ?><?= !empty($categoryLinks)?$lBlogPost["PostedIn"]." ".$categoryLinks:"" ?> <?= $lBlogPost["By"] ?> <?= $authorLink ?><?= !empty($commentsLink)?" | ".$commentsLink:"" ?><?= !empty($editLink)?" | ".$editLink:"" ?></p>
