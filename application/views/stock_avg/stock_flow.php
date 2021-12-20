<!--  
 To work this need the script 'js/stock_avg/stock_flow.js', 'bcmath-min.js' and 'php_function_equivalent_for_js.js' 
 And include following line of code in your controller index() function
      $cstr_mbrtp_id = $this->central_stores->get_member_type_id();
      $data['cstr_mbr_option'] = $this->central_stores->get_members_option(array('mbr_fk_clients' => $this->clnt_id), active, $cstr_mbrtp_id);
      $data['prd_option'] = $this->products->get_active_option(array('prd_fk_clients' => $this->clnt_id));
-->


<div class="modal fade modal-fullscreen-sm" id="stock-flow-modal" style="z-index: 1100;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h4 class="modal-title">STOCK FLOW</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php $this->load->view('stock_avg/search_stock_flow'); ?>
                <?php $this->load->view('stock_avg/list_stock_flow'); ?>
            </div>
            <!-- <div class="modal-footer">
                    <div class="sr-wraper-bold">
                        <button type="submit" class="btn btn-primary save">SAVE</button>
                    </div>
                </div> -->
        </div>
    </div>
</div>