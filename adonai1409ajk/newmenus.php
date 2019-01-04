<?php
include "../includes/fu6106.php";
if (isset($_SESSION['nm_user'])) {
	function get_menu($data, $parent = 0) {
		static $i = 1;
		$tab = str_repeat("\t\t", $i);
		if (isset($data[$parent])) {
			$html = "\n$tab<ul>";
			$i++;
			foreach ($data[$parent] as $v) {
				$child = get_menu($data, $v->id);
				$html .= "\n\t$tab<li>";
				$html .= '<a href="'.$v->menuurl.'">'.$v->menu.'</a>';
				if ($child) {
					$i--;
					$html .= $child;
					$html .= "\n\t$tab";
				}
				$html .= '</li>';
			}
			$html .= "\n$tab</ul>";
			return $html;
		} else {
			return false;
		}
	}

	$result = mysql_query("SELECT * FROM fu_ajk_menus ORDER BY sub");
	while ($row = mysql_fetch_object($result)) {
		$data[$row->parent][] = $row;
	}

	$menu = get_menu($data);


echo '<link rel="stylesheet" type="text/css" href="metmenus/jquerycssmenu.css" />
	  <script type="text/javascript" src="metmenus/jquery-1.3.2.min.js"></script>
	  <script type="text/javascript" src="metmenus/jquerycssmenu.js"></script>
	  <div id="myjquerymenu" class="jquerycssmenu">'.$menu.' <div align="right"><b>{user}</b><br /><br /></div><style="clear: left" /></div>';
}else{

}
?>
