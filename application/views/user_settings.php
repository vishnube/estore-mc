<div class="row">
    <div class="col-12">
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid bg-dark p-5">

                <form class="sr-form" role="form" id="user-settings" onsubmit="$(this).find(':submit').prop('disabled', true)">

                    <!-- Reference Table -->
                    <input type="hidden" name="user_settings_reftbl" value="<?= $user_settings_reftbl ?>">

                </form>

                <div class="row">
                    <div class="col-md-4">
                        <h2 class="text-md-left text-xs-center">SETTINGS</h2>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <input type="text" id="userSettingsQuickSearch" data-callback="" data-target="#tbl-user-settings" class="quick-search form-control float-right" placeholder="Quick Search">
                            <div class="input-group-append">
                                <button class="btn btn-default"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="btn-group rounded">
                            <button type="button" class="btn bg-gradient-info btn-flat rounded-left export-PDF" data-target="#tbl-user-settings">
                                <i class="fas fa-file-pdf"></i>&nbsp;
                                PDF
                            </button>
                            <button type="button" class="btn bg-gradient-danger btn-flat export-EXCEL" data-target="#tbl-user-settings">
                                <i class="fas fa-file-excel"></i>&nbsp;
                                Excel
                            </button>
                            <button type="button" class="btn bg-gradient-success btn-flat rounded-right export-PRINT" data-target="#tbl-user-settings">
                                <i class="fas fa-print"></i>&nbsp;
                                Print
                            </button>
                        </div>
                    </div>
                </div>








                <div class="row">
                    <div class="col-xs-12" style="height: 500px;overflow-x:auto;">
                        <table class="table table-striped" id="tbl-user-settings" data-exportTitle_1="<?= $client['clnt_print_name'] ?>" data-exportTitle_2="SETTINGS LIST" data-exportTitle_3="List of settings">
                            <thead>
                                <tr>
                                    <th>
                                        <span class="export" data-exportStyle="text-align: left;">#</span>
                                    </th>
                                    <th>
                                        <span class="export">NAME</span>
                                    </th>
                                    <th>
                                        <span class="export">VALUE</span>
                                    </th>
                                    <th>
                                        <span class="">USER</span>
                                    </th>
                                    <th>
                                        <span class="">VERSION</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>