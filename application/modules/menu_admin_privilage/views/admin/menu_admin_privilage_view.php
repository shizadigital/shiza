<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/************************************
		Register style (CSS)
************************************/
$request_css_files = array(
);
$request_style = "";
$this->assetsloc->reg_admin_style($request_css_files,$request_style);

/*******************************************
		Register Script (JavaScript)
*******************************************/
$request_script_files = array(
    'vendors/parsley/parsley.config.js',
    'vendors/parsley/parsley.min.js',
);
$request_script = "
$( document ).ready(function() {
    $('#valid').parsley();
});
";
$this->assetsloc->reg_admin_script($request_script_files,$request_script);

include V_ADMIN_PATH . "header.php";
include V_ADMIN_PATH . "sidebar.php";
include V_ADMIN_PATH . "topbar.php";

?>
<?php 
if( is_view() ):
echo form_open( admin_url( $this->uri->segment(2).'/' ), array( 'id'=>'valid', 'method'=>'get' ) ); ?>
<input type="hidden" name="act" value="detail_access">
<!-- start-clients contant-->
<div class="row">
    <div class="col-12">
        <div class="card card-statistics">
            <div class="card-body">
                <div class="form-row align-items-center">
                    <div class="col-auto form-inline">
                        <div class="input-group">
                            <select name="levelaccess" class="form-control custom-select" data-parsley-required="true">
                                <option value="">-- <?php echo t('level'); ?> --</option>
                                <?php 
                                foreach ($data['datalevel'] as $r) {                                    
                                    echo "<option value=\"{$r['levelId']}\"";
                                    if( $r['levelId'] == $this->input->get('levelaccess') ){
                                    	echo " selected=\"selected\"";
                                    }
                                    echo ">{$r['levelName']}</option>";
                                }
                                ?>
                            </select>
                            <div class="input-group-append">
                                <button class="btn btn-light btn-sm" type="submit"><?php echo t('choose'); ?> <span class="fa fa-angle-double-right"></span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php 
echo form_close();
endif;

if( $this->input->get('act')=='detail_access' ):
?>
<div class="card card-statistics">
    <div class="card-header">
        <h5 class="card-title mb-0"><?php echo sprintf(t('privilegefor'), $data['accessname']); ?></h5>
    </div>

    <?php 
	if( is_edit() ):
	echo form_open( admin_url( $this->uri->segment(2).'/updatecrudacc/' ) ); ?>
    <input type="hidden" name="levelaccess" value="<?php echo $this->input->get('levelaccess'); ?>">
    <div class="card-body">
    	<?php 
		if( !empty( $this->session->has_userdata('succeed') ) ){
            echo '
			<div class="alert alert-icon alert-success alert-dismissible fade show" role="alert">
				<i class="fa fa-check"></i> ' . $this->session->flashdata('succeed') . '
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fe fe-x"></i></button>
			</div>
			';
		}
		if( !empty( $this->session->has_userdata('failed') ) ){
            echo '
			<div class="alert alert-icon alert-danger alert-dismissible fade show" role="alert">
				<i class="fa fa-times"></i> ' . $this->session->flashdata('failed') . '
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><i class="fe fe-x"></i></button>
			</div>
			';
		}            
        ?>

        <div class="row">
            
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary"><i class="fe fe-refresh-cw"></i> <?php echo t('updatelist'); ?></button>
            </div>
            <div class="col-md-12 py-3 table-responsive-sm">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width:25px;" class="text-center"><?php echo t('no_number'); ?></th>
                            <th style="min-width:220px;"><?php echo t('name'); ?></th>
                            <th class="text-center">
                                <?php echo t('view'); ?><br/>
                                <input type="checkbox" id="mod_view_all" />
                            </th>
                            <th class="text-center">
                                <?php echo t('add'); ?><br/>
                                <input type="checkbox" id="mod_add_all" />
                            </th>
                            <th class="text-center">
                                <?php echo t('edit'); ?><br/>
                                <input type="checkbox" id="mod_edit_all" />
                            </th>
                            <th class="text-center">
                                <?php echo t('delete'); ?><br/>
                                <input type="checkbox" id="mod_delete_all" />
                            </th>
                            <th class="text-center" style="background:#eaffe2;">
                                <?php echo t('all'); ?><br/>
                                <input type="checkbox" id="mod_all_all" />
                            </th>
                            <th class="text-center" style="min-width:90px;">
                                <?php echo t('active'); ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        function levelDataField($no, $r, $totallevel = 0){
                        	$ci =& get_instance();

                        	$dacc = $r['menuaccess'];
                            $view_checked1 = ($dacc['lmnView']=='y') ? " checked=\"checked\"" : "";
                            $add_checked1 = ($dacc['lmnAdd']=='y') ? " checked=\"checked\"" : "";
                            $edit_checked1 = ($dacc['lmnEdit']=='y') ? " checked=\"checked\"" : "";
                            $delete_checked1 = ($dacc['lmnDelete']=='y') ? " checked=\"checked\"" : "";
                		?>
                		<tr>
                            <td class="text-center"><?php echo $no; ?></td>
                            <td>
                                <?php 
                                	if($totallevel > 0){
	                                	for($i=1; $i<=$totallevel; $i++){
	                                		echo '<i class="fe fe-minus"></i>';
	                                		if($i == $totallevel){ echo ' '; }
	                                	}
	                                }
                                    echo t( array('table'=>'users_menu', 'field'=>'menuName', 'id'=>$r['menuId']) );
                                    echo "<input name=\"idmenu[]\" type=\"hidden\" value=\"{$r['menuId']}\"/>";
                                ?>
                                <script type="text/javascript"> 
                                $(document).ready(function(){
                                    
                                    //Check all Crud for a module
                                    $("#mod_all<?php echo $r['menuId']; ?>").click(function(){
                                        if ( (this).checked == true ){
                                           $('#mod_view<?php echo $r['menuId']; ?>').prop('checked', true);
                                           $('#mod_add<?php echo $r['menuId']; ?>').prop('checked', true);
                                           $('#mod_edit<?php echo $r['menuId']; ?>').prop('checked', true);
                                           $('#mod_delete<?php echo $r['menuId']; ?>').prop('checked', true);
                                        } else {
                                           $('#mod_view<?php echo $r['menuId']; ?>').prop('checked', false);
                                           $('#mod_add<?php echo $r['menuId']; ?>').prop('checked', false);
                                           $('#mod_edit<?php echo $r['menuId']; ?>').prop('checked', false);
                                           $('#mod_delete<?php echo $r['menuId']; ?>').prop('checked', false);
                                        }
                                    });

                                });
                                </script>
                            </td>
                            <td class="text-center">
                                <?php 
                                if($r['menuId']=='1' OR $r['menuId']=='2' OR $r['menuId']=='3' OR $r['menuId']=='4' OR $r['menuId']=='5'){ 
                                    if( $ci->session->userdata('leveluser')=='1' AND $ci->input->get('levelaccess')=='1'){ 
                                		echo '<input type="hidden" name="mod_view['.$r['menuId'].']" value="y" />-';
	                                } else {
	                                    echo '<input type="hidden" name="mod_view['.$r['menuId'].']" value="n" />-';
	                                }
                                } else { 
                                	if($r['menuView']=='n'){ 
                                		echo "<input type=\"hidden\" name=\"mod_view[{$r['menuId']}]\" value=\"n\" />-"; }
                                	else{ 
                                ?>
                                <input type="checkbox"<?php echo $view_checked1; ?> name="mod_view[<?php echo $r['menuId']; ?>]" id="mod_view<?php echo $r['menuId']; ?>" class="mod_view" value="y" />
                                <?php
	                                }
	                            } ?>
                            </td>
                            <td class="text-center">
                                <?php if($r['menuId']=='1' OR $r['menuId']=='2' OR $r['menuId']=='3' OR $r['menuId']=='4' OR $r['menuId']=='5'){ 
                                    if( $ci->session->userdata('leveluser')=='1' AND $ci->input->get('levelaccess')=='1'){
                                		echo '<input type="hidden" name="mod_add['.$r['menuId'].']" value="y" />-';
                                	} else {
	                                    echo '<input type="hidden" name="mod_add['.$r['menuId'].']" value="n" />-';
	                                }
                                } else { 
                                	if($r['menuAdd']=='n'){ 
                                		echo "<input type=\"hidden\" name=\"mod_add[{$r['menuId']}]\" value=\"n\" />-";
                                	} else{ 
                                ?>
                                <input type="checkbox"<?php echo $add_checked1; ?> name="mod_add[<?php echo $r['menuId']; ?>]" id="mod_add<?php echo $r['menuId']; ?>" class="mod_add" value="y" />
                                <?php } } ?>
                            </td>
                            <td class="text-center">
                                <?php if($r['menuId']=='1' OR $r['menuId']=='2' OR $r['menuId']=='3' OR $r['menuId']=='4' OR $r['menuId']=='5'){
                                    if( $ci->session->userdata('leveluser')=='1' AND $ci->input->get('levelaccess')=='1'){
                                		echo '<input type="hidden" name="mod_edit['.$r['menuId'].']" value="y" />-';
                                	} else { 
	                                    echo '<input type="hidden" name="mod_edit['.$r['menuId'].']" value="n" />-';
	                                }
                                } else {
	                                if($r['menuEdit']=='n'){
	                                	echo "<input type=\"hidden\" name=\"mod_edit[{$r['menuId']}]\" value=\"n\" />-";
	                                } else{ 
                                ?>
                                <input type="checkbox"<?php echo $edit_checked1; ?> name="mod_edit[<?php echo $r['menuId']; ?>]" id="mod_edit<?php echo $r['menuId']; ?>" class="mod_edit" value="y" />
                                <?php } } ?>
                            </td>
                            <td class="text-center">
                                <?php if($r['menuId']=='1' OR $r['menuId']=='2' OR $r['menuId']=='3' OR $r['menuId']=='4' OR $r['menuId']=='5'){
                                    if( $ci->session->userdata('leveluser')=='1' AND $ci->input->get('levelaccess')=='1'){
                                		echo '<input type="hidden" name="mod_delete['.$r['menuId'].']" value="y" />-';
	                                } else { 
	                                    echo '<input type="hidden" name="mod_delete['.$r['menuId'].']" value="n" />-';
	                                }
                                } else {
	                                if($r['menuDelete']=='n'){
	                                	echo "<input type=\"hidden\" name=\"mod_delete[{$r['menuId']}]\" value=\"n\" />-";
	                                } else{
                                ?>
                                <input type="checkbox"<?php echo $delete_checked1; ?> name="mod_delete[<?php echo $r['menuId']; ?>]" id="mod_delete<?php echo $r['menuId']; ?>" class="mod_delete" value="y" />
                                <?php } } ?>
                            </td>
                            <td class="text-center" style="background:#eaffe2;">
                                <?php if($r['menuId']=='1' OR $r['menuId']=='2' OR $r['menuId']=='3' OR $r['menuId']=='4' OR $r['menuId']=='5'){
                                    	echo '-';
                                } else {
                                	$allchecked1='';
                                	if($dacc['lmnView'] == 'y' AND $dacc['lmnAdd'] == 'y' AND $dacc['lmnEdit'] == 'y' AND $dacc['lmnDelete'] == 'y'){
                                		$allchecked1 = " checked=\"checked\"";
                                	}
                                    ?>
                                <input type="checkbox"<?php echo $allchecked1; ?> name="mod_all[]" id="mod_all<?php echo $r['menuId']; ?>" class="mod_all" value="y" />
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                if($r['menuId']=='1' OR $r['menuId']=='2' OR $r['menuId']=='3' OR $r['menuId']=='4' OR $r['menuId']=='5'){
                                    echo "<i class=\"fe fe-minus\"></i>";
                                } else { 
                                    if($r['menuActive']=='y'){ echo "<i class=\"fe fe-check\"></i>"; }
                                    else { echo "<i class=\"fe fe-minus\"></i>"; }
                                } 
                                ?>
                            </td>
                            
                        </tr>
                		<?php
                    	}

                        $no = 0;
                        foreach ($data['menudata'] as $r) {

	                        $no++; 
                        	levelDataField($no, $r);

                        	if( count($r['level_2'])>0  ){
	                            foreach ($r['level_2'] as $r2) {

		                        	$no++; 
		                        	levelDataField($no, $r2, 1);
		                            
		                            if( count($r2['level_3'])>0 ){
			                        	foreach ($r2['level_3'] as $r3) {

				                        	$no++; 
				                            levelDataField($no, $r3, 2);

				                            if( count($r3['level_4'])>0 ){
				                            	foreach ($r3['level_4'] as $r4) {
				                            		$no++; 
				                            		levelDataField($no, $r4, 3);
				                            	}
				                            }
				                        }
				                    }
	                            }
	                        }
	                    }
	                    ?>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary"><i class="fe fe-refresh-cw"></i> <?php echo t('updatelist'); ?></button>
            </div>
            <div class="col-md-6">
                <?php                                               
                    echo "<div class=\"float-right\" style=\"margin-top:5px;font-style:italic;\">".t('total')." {$data['totalmenudata']}</div>"
                ?>
            </div>
        </div>
    </div>
    <?php 
	echo form_close();
	endif;
	?>
<script type="text/javascript"> 
$(document).ready(function(){
    
    //Check all view
    $("#mod_view_all").click(function(){
        if ( (this).checked == true ){
           $('.mod_view').prop('checked', true);
        } else {
           $('.mod_view').prop('checked', false);
        }
    });

    //Check all add
    $("#mod_add_all").click(function(){
        if ( (this).checked == true ){
           $('.mod_add').prop('checked', true);
        } else {
           $('.mod_add').prop('checked', false);
        }
    });

    //Check all edit
    $("#mod_edit_all").click(function(){
        if ( (this).checked == true ){
           $('.mod_edit').prop('checked', true);
        } else {
           $('.mod_edit').prop('checked', false);
        }
    });

    //Check all delete
    $("#mod_delete_all").click(function(){
        if ( (this).checked == true ){
           $('.mod_delete').prop('checked', true);
        } else {
           $('.mod_delete').prop('checked', false);
        }
    });

    //Check all all
    $("#mod_all_all").click(function(){
        if ( (this).checked == true ){
           $('.mod_view').prop('checked', true);
           $('.mod_add').prop('checked', true);
           $('.mod_edit').prop('checked', true);
           $('.mod_delete').prop('checked', true);
           $('.mod_all').prop('checked', true);
        } else {
           $('.mod_view').prop('checked', false);
           $('.mod_add').prop('checked', false);
           $('.mod_edit').prop('checked', false);
           $('.mod_delete').prop('checked', false);
           $('.mod_all').prop('checked', false);
        }
    });


});
</script>
</div>
<?php endif; ?>

<?php
include V_ADMIN_PATH . "footer.php";
?>