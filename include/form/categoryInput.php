<style type="text/css">
<!--<
#categorymod {position:relative;}
#categoryautocomplete, #categoryautocomplete2 {position:relative;width:40%;margin-bottom:1em;}/* set width of widget here*/
#categoryautocomplete {z-index:9000} /* for IE z-index of absolute divs inside relative divs issue */
#categoryinput, #categoryinput2 {_position:absolute;width:100%;height:1.4em;z-index:0;} /* abs for ie quirks */
#categorycontainer, #categorycontainer2 {position:absolute;top:1.7em;width:100%}
#categorycontainer .yui-ac-content, #categorycontainer2 .yui-ac-content {position:absolute;width:100%;border:1px solid #cccccc;background:#fff;overflow:hidden;z-index:9050;}
#categorycontainer .yui-ac-shadow, #categorycontainer2 .yui-ac-shadow {position:absolute;margin:.3em;width:100%;background:#a0a0a0;z-index:9049;}
#categorycontainer ul, #categorycontainer2 ul {padding:5px 0;width:100%;}
#categorycontainer li, #categorycontainer2 li {padding:0 5px;cursor:default;white-space:nowrap;}
#categorycontainer li.yui-ac-highlight, #categorycontainer2 li.yui-ac-highlight {color:#ffffff;background:#003366;}
#categorycontainer li.yui-ac-prehighlight,#categorycontainer2 li.yui-ac-prehighlight {color:#ffffff;background:#003366;}
-->
</style>

<!-- AutoComplete begins -->
<span id="categorymod">
    <div id="categoryautocomplete">
		<input type="text" name="categories" id="categoryinput" value="<?= !empty($categories)?$categories:"" ?>" class="shortInput" />
        <div id="categorycontainer"></div>
    </div>
</span>
<!-- AutoComplete ends -->

<!-- Libary begins -->
<script type="text/javascript" src="<?= scriptUrl ?>/javascript/yahoo/yahoo/yahoo.js"></script>
<script type="text/javascript" src="<?= scriptUrl ?>/javascript/yahoo/dom/dom.js"></script>
<script type="text/javascript" src="<?= scriptUrl ?>/javascript/yahoo/event/event-debug.js"></script>
<script type="text/javascript" src="<?= scriptUrl ?>/javascript/yahoo/autocomplete/autocomplete-debug.js"></script>
<!-- Library ends -->

<!-- In-memory JS array begins-->
<script type="text/javascript">
<?
// Get categories
$result = $dbi->query("SELECT title FROM ".categoryTableName." ORDER BY title");
if ($result->rows()) {
	echo 'var categoryArray = [';
	for ($i=0; list($title)=$result->fetchrow_array(); $i++) {
		echo ($i!=0?",":"")."\"".stripslashes($title)."\"";
	}
	echo '];';
}
?>
</script>
<!-- In-memory JS array ends-->

<script type="text/javascript">
YAHOO.example.ACJSArray = function() {
    var mylogger;
    var oACDS;
    var oAutoComp;
    return {
        init: function() {
            //Logger
            //mylogger = new YAHOO.widget.LogReader("logger");

            // Instantiate first JS Array DataSource
            oACDS = new YAHOO.widget.DS_JSArray(categoryArray);

            // Instantiate first AutoComplete
            oAutoComp = new YAHOO.widget.AutoComplete('categoryinput','categorycontainer', oACDS);
            oAutoComp.queryDelay = 0.5;
            oAutoComp.prehighlightClassName = "yui-ac-prehighlight";
            oAutoComp.delimChar = [","];
            oAutoComp.typeAhead = true;
            //oAutoComp.useShadow = true;
            oAutoComp.minQueryLength = 1;
            oAutoComp.textboxFocusEvent.subscribe(function(){oAutoComp.sendQuery("");});
        },
    };
}();

YAHOO.util.Event.addListener(this,'load',YAHOO.example.ACJSArray.init);
</script>
<script type="text/javascript" src="<?= scriptUrl ?>/javascript/yahoo/assets/dpSyntaxHighlighter.js"></script>
<script type="text/javascript">
dp.SyntaxHighlighter.HighlightAll('code');
</script>
            