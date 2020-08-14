<?php
/*
 * PHP Pagination Class
 * @author admin@catchmyfame.com - http://www.catchmyfame.com
 * @version 2.0.0
 * @date October 18, 2011
 * @copyright (c) admin@catchmyfame.com (www.catchmyfame.com)
 * @license CC Attribution-ShareAlike 3.0 Unported (CC BY-SA 3.0) - http://creativecommons.org/licenses/by-sa/3.0/
 */
class Paginator{
	var $items_per_page;
	var $items_total;
	var $current_page;
	var $num_pages;
	var $mid_range;
	var $low;
	var $limit;
	var $return;
	var $default_ipp;
	var $querystring;
	var $ipp_array;

	    public function __construct()
    {
		$this->mid_range = 7;
		$this->ipp_array = array(10,25,50,100,'All');
		$this->items_per_page = (!empty($_GET['ipp'])) ? $_GET['ipp']:$this->default_ipp;    	
    }

	function Paginator()
	{
		self::__construct();
	}

	function paginate()
	{
		if(!isset($this->default_ipp)) $this->default_ipp=9;
		 if ($this->items_per_page == 'All')
		{
			$this->num_pages = 1;
//			$this->items_per_page = $this->default_ipp;
		}
		else
		{ 
			if(!is_numeric($this->items_per_page) OR $this->items_per_page <= 0) $this->items_per_page = $this->default_ipp;
			$this->num_pages = ceil($this->items_total/$this->items_per_page);
		}
		$this->current_page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1 ; // must be numeric > 0
		$prev_page = $this->current_page-1;
		$next_page = $this->current_page+1;
		if($_GET)
		{
			$args = explode("&",$_SERVER['QUERY_STRING']);
			foreach($args as $arg)
			{
				$keyval = explode("=",$arg);
				if($keyval[0] != "page" And $keyval[0] != "ipp") $this->querystring .= "&" . $arg;
			}
		}

		if($_POST)
		{
			foreach($_POST as $key=>$val)
			{
				if($key != "page" And $key != "ipp") $this->querystring .= "&$key=$val";
			}
		}
	//	if($this->num_pages > 10)
		{
			$this->return = ($this->current_page > 1 And $this->items_total >= 10) ? "<li><a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$prev_page$this->querystring#results\">&laquo;</a></li> ":"<li><span class=\"inactive\" href=\"#\">&laquo;</span></li> ";

			$this->start_range = $this->current_page - floor($this->mid_range/2);
			$this->end_range = $this->current_page + floor($this->mid_range/2);

			if($this->start_range <= 0)
			{
				$this->end_range += abs($this->start_range)+1;
				$this->start_range = 1;
			}
			if($this->end_range > $this->num_pages)
			{
				$this->start_range -= $this->end_range-$this->num_pages;
				$this->end_range = $this->num_pages;
			}
			$this->range = range($this->start_range,$this->end_range);

			for($i=1;$i<=$this->num_pages;$i++)
			{
				if($this->range[0] > 2 And $i == $this->range[0]) $this->return .= "<li><a href=\"#\" class=\"disableClick\">...</a></li>";
				// loop through all pages. if first, last, or in range, display
				if($i==1 Or $i==$this->num_pages Or in_array($i,$this->range))
				{
					$this->return .= ($i == $this->current_page And $this->current_page != 'All') ? "<li><a title=\"Go to page $i of $this->num_pages\" class=\"current\" href=\"#\">$i</a></li> ":"<li><a class=\"paginate\" title=\"Go to page $i of $this->num_pages\" href=\"$_SERVER[PHP_SELF]?page=$i$this->querystring#results\">$i</a></li> ";
				}
				if($this->range[$this->mid_range-1] < $this->num_pages-1 And $i == $this->range[$this->mid_range-1]) $this->return .= "<li class=\"inactive\"><a href=\"#\" class=\"disableClick\">...</a></li>";
			}
			$this->return .= (($this->current_page < $this->num_pages And $this->items_total >= 10) And isset($_GET['page']) And ($_GET['page'] != 'All') And $this->current_page > 0) ? "<li><a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$next_page$this->querystring#results\">&raquo;</a></li>\n":"<li><span class=\"inactive\" href=\"#\">&raquo;</span></li>\n";
			//$this->return .= ($this->current_page == 'All') ? "<li><a class=\"current\" style=\"margin-left:10px\" href=\"#\">All</a></li> \n":"<li><a class=\"paginate\" style=\"margin-left:10px\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All$this->querystring\">All</a></li> \n";
		}
	/*	else
		{
			for($i=1;$i<=$this->num_pages;$i++)
			{
				$this->return .= ($i == $this->current_page) ? "<li><a class=\"current\" href=\"#\">$i</a></li> ":"<li><a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=$i$this->querystring\">$i</a></li> "; //&ipp=$this->items_per_page$this->querystring
			}
			$this->return .= "<li><a class=\"paginate\" href=\"$_SERVER[PHP_SELF]?page=1&ipp=All$this->querystring\">Toate</a></li> \n";
		}*/
		$this->low = ($this->current_page <= 0) ? 0:($this->current_page-1) * $this->items_per_page;
		if($this->current_page <= 0) $this->items_per_page = 0;
		$this->limit = ($this->items_per_page == 'All') ? "0":" $this->low";
	}
	function display_items_per_page()
	{
		$items = '';
		if(!isset($_GET[ipp])) $this->items_per_page = $this->default_ipp;
		foreach($this->ipp_array as $ipp_opt) $items .= ($ipp_opt == $this->items_per_page) ? "<option selected value=\"$ipp_opt\">$ipp_opt</option>\n":"<option value=\"$ipp_opt\">$ipp_opt</option>\n";
		return "<li><span class=\"paginate\">Items per page:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page=1&ipp='+this[this.selectedIndex].value+'$this->querystring';return false\">$items</select></li>\n";
	}
	function display_jump_menu()
	{
		for($i=1;$i<=$this->num_pages;$i++)
		{
			$option .= ($i==$this->current_page) ? "<option value=\"$i\" selected>$i</option><\n":"<option value=\"$i\">$i</option>\n";
		}
		return "</i><span class=\"paginate\">Page:</span><select class=\"paginate\" onchange=\"window.location='$_SERVER[PHP_SELF]?page='+this[this.selectedIndex].value+'&ipp=$this->items_per_page$this->querystring';return false\">$option</select></li>\n";
	}
	function display_pages()
	{
		return $this->return;
	}
}
