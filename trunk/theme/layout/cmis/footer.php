							<br /><br />
							<? $this->printMetaLinks() ?>
						</div>
					</div>
					
					<div class="layoutWidgets">
						<? 
						// Display menu
						if(file_exists(widgetPath."/system/menu.php")) include widgetPath."/system/menu.php";

						// Display module widget(s)
						if(!empty($this->path)) {
							if(file_exists(scriptPath."/".$this->path."/include/widgets.php")) {
								include scriptPath."/".$this->path."/include/widgets.php";
							}
						}
						else if (file_exists(scriptPath."/data/widgets.php")) {					
							// Include custom widgets
							include scriptPath."/data/widgets.php";
						}				

						// Display login widget
						if(file_exists(widgetPath."/system/login.php")) include widgetPath."/system/login.php";
						?>
					</div>
				</div>

				<div class="layoutNavigation">
					<? $default = new Page(pageDefaultPage) ?>
					<a href="<?= $default->getPageLink() ?>" class="navigation1"><?= $default->title ?></a>
					<?
						if (!empty($this->navigationLinks)) {
							for ($i=0;$i<sizeof($this->navigationLinks);$i++) {
								echo " » <a href=\"".$this->navigationLinks[$i][0]."\" class=\"navigation1\">".$this->navigationLinks[$i][1]."</a>";
							}
						}
					?>		
				</div>
			</div>
		</div>

		<div class="layoutCreditContainer">
			<div class="layoutCreditLogo">
				<img src="<?= imgUrl ?>/logo.jpg" />
			</div>
			<div class="layoutCreditText">
				"<?= pageTitle ?>" is powered by <a href="http://www.krosweb.dk">CMIS</a>.
			</div>
		</div>

		<? if (file_exists(layoutPath."/tracking.php")) include "tracking.php" ?>
	</body>
</html>