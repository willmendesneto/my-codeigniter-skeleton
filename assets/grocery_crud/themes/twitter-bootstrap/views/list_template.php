<?php
$this->set_css($this->default_theme_path.'/twitter-bootstrap/css/min/bootstrap.min.css');
$this->set_css($this->default_theme_path.'/twitter-bootstrap/css/min/bootstrap-responsive.min.css');
$this->set_css($this->default_theme_path.'/twitter-bootstrap/css/tablesorter.min.css');
$this->set_css($this->default_theme_path.'/twitter-bootstrap/css/style.css');

$this->set_js_lib($this->default_javascript_path.'/'.grocery_CRUD::JQUERY);

$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/jquery.noty.js');
$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/config/jquery.noty.config.js');
$this->set_js_lib($this->default_javascript_path.'/common/lazyload-min.js');

if (!$this->is_IE7()) {
	$this->set_js_lib($this->default_javascript_path.'/common/list.js');
}
//	JAVASCRIPTS - TWITTER BOOTSTRAP
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap.min.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap-transition.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap-alert.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap-modal.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap-dropdown.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap-scrollspy.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap-tab.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap-tooltip.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap-popover.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap-button.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap-collapse.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap-carousel.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap-typeahead.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/bootstrap-affix.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/application.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/libs/modernizr-2.6.1.custom.js');


$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/cookies.js');

//	VERIFICAR O FUNCIONAMENTO DO JAVASCRIPT
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/flexigrid.js');


$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/jquery.form.js');
$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.numeric.min.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/jquery.printElement.min.js');

$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/tablesorter/jquery.tablesorter.min.js');
$this->set_js($this->default_theme_path.'/twitter-bootstrap/js/jquery.functions.js');

/** Fancybox */
$this->set_css($this->default_css_path.'/jquery_plugins/fancybox/jquery.fancybox.css');
$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.fancybox.pack.js');
$this->set_js($this->default_javascript_path.'/jquery_plugins/jquery.easing-1.3.pack.js');

/** Jquery UI */

$this->set_css($this->default_css_path.'/ui/simple/'.grocery_CRUD::JQUERY_UI_CSS);
$this->set_js_lib($this->default_javascript_path.'/jquery_plugins/ui/'.grocery_CRUD::JQUERY_UI_JS);



$this->load_js_jqueryui();

?>
<script type="text/javascript">
var base_url = '<?php echo base_url();?>',
subject = '<?php echo $subject?>',
ajax_list_info_url = '<?php echo $ajax_list_info_url?>',
unique_hash = '<?php echo $unique_hash; ?>',
message_alert_delete = "<?php echo $this->l('alert_delete'); ?>";
</script>
<div id="hidden-operations"></div>
<div id="report-error" class="report-div error"></div>
<div id="report-success" class="report-div success report-list" <?php if($success_message !== null){?>style="display:block"<?php }?>>
	<?php if($success_message !== null){?>
		<p><?php echo $success_message; ?></p>
	<?php } ?>
</div>
<div class="twitter-bootstrap" style="width: 100%;">
	<div class="mDiv">
		<div class="ftitle">
			&nbsp;
		</div>
		<div title="<?php echo $this->l('minimize_maximize');?>" class="ptogtitle">
			<span></span>
		</div>
	</div>
	<div id="main-table-box">

		<br/>
		<?php if(!$unset_add || !$unset_export || !$unset_print){?>
		<div class="tDiv">
			<?php if(!$unset_add){?>
 					<a href="<?php echo $add_url?>" title="<?php echo $this->l('list_add'); ?> <?php echo $subject?>" class="add-anchor add_button btn"><!--
 					<a href="<?php echo $add_url?>" title="<?php echo $this->l('list_add'); ?> <?php echo $subject?>" class="btn"> -->
 						<?php echo $this->l('list_add'); ?> <?php echo $subject?>
 					</a>
 			<?php }?>
 			<?php if(!$unset_export) { ?>
	 			<a class="export-anchor btn" data-url="<?php echo $export_url; ?>" rel="external">
	 				<?php echo $this->l('list_export');?>
	 			</a>
 			<?php } ?>
 			<?php if(!$unset_print) { ?>
	 			<a class="print-anchor btn" data-url="<?php echo $print_url; ?>">
	 				<?php echo $this->l('list_print');?>
	 			</a>
 			<?php }?>
 		</div>
 		<?php }?>
		<br/>
		<div id="ajax_list"><?php echo $list_view; ?></div>
		<?php echo form_open( $ajax_list_url, 'method="post" id="filtering_form" autocomplete = "off"'); ?>
		<div class="sDiv" id="quickSearchBox" class="span12">
			<div class="sDiv2">
				<?php echo $this->l('list_search');?>: <input type="text" class="qsbsearch_fieldox" name="search_text" size="30" id="search_text">
				<select name="search_field" id="search_field">
					<option value=""><?php echo $this->l('list_search_all');?></option>
					<?php foreach($columns as $column){?>
					<option value="<?php echo $column->field_name?>"><?php echo $column->display_as; ?></option>
					<?php }?>
				</select>

				<input type="button" class="btn btn-primary" value="<?php echo $this->l('list_search');?>" id="crud_search">
				<input type="button" class="btn btn-inverse" value="<?php echo $this->l('list_clear_filtering');?>" id="search_clear">
			</div>
			<div class="pGroup">

				<select name="per_page" id="per_page">
					<?php foreach($paging_options as $option){?>
					<option value="<?php echo $option; ?>" <?php if($option == $default_per_page){?>selected="selected"<?php }?>><?php echo $option; ?>&nbsp;&nbsp;</option>
					<?php }?>
				</select>
				<input type="hidden" name="order_by[0]" id="hidden-sorting" value="<?php if(!empty($order_by[0])){?><?php echo $order_by[0]?><?php }?>" />
				<input type="hidden" name="order_by[1]" id="hidden-ordering"  value="<?php if(!empty($order_by[1])){?><?php echo $order_by[1]?><?php }?>"/>

				<span class="pPageStat">
					<?php
					$paging_starts_from = '<span id="page-starts-from">1</span>';
					$paging_ends_to = '<span id="page-ends-to">'. ($total_results < $default_per_page ? $total_results : $default_per_page) .'</span>';
					$paging_total_results = '<span id="total_items" class="badge badge-info">'.$total_results.'</span>';
					echo str_replace( array('{start}','{end}','{results}'), array($paging_starts_from, $paging_ends_to, $paging_total_results), $this->l('list_displaying')); ?>
				</span>

				<span class="pcontrol">
					<?php echo $this->l('list_page'); ?>
					<input name="page" type="text" value="1" size="4" id="crud_page">
					<?php echo $this->l('list_paging_of'); ?>
					<span id="last-page-number"><?php echo ceil($total_results / $default_per_page); ?></span>
				</span>

				<!-- DIVS DE CONFIGURACOES DO GROCERY CRUD -->
				<div class="pSearch pButton" id="quickSearchButton" title="<?php echo $this->l('list_search');?>">
					<span></span>
				</div>
				<div class="pNext pButton next-button" >
					<span></span>
				</div>
				<div class="pLast pButton last-button">
					<span></span>
				</div>
				<div class="pFirst pButton first-button">
					<span></span>
				</div>
				<div class="pPrev pButton prev-button">
					<span></span>
				</div>
				<div class="pReload pButton" id="ajax_refresh_and_loading">
					<span></span>
				</div>
				<?php echo form_close(); ?>
			</div>
		</div>
	</div>
</div>