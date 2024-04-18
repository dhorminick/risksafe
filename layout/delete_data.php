<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-weight: 400;">
                    Are You Sure You Want To Delete <span class="view-type" style="text-transform: capitalize;"></span> With ID:
                    <p id="view-id" style="text-transform: uppercase;font-weight: bolder;margin-top:5px;"></p>
                </div>
                <div class="modal-footer bg-whitesmoke">
                    <form id='deleteData' style="width:100%;" method='post' action=''>
                        <input type="hidden" name="data-id" id="data-id" required>
                        <input type="hidden" name="data-type" id="data-type" required>
                        <button type="submit" class="btn btn-primary btn-icon icon-left" name="delete-data" style="width:100%;"><i class="fas fa-trash-alt"></i> Delete <span class="view-type" style="text-transform: capitalize;"></span></button>
                    </form>
                </div>
            </div>
          </div>
    </div>
    
    <div class="modal fade" id="exportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirm Action</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="font-weight: 400;">
                    Select Export Data Type:
                    <div class="form-group">
                        <!--<label>Company Id:</label>-->
                        <select class="form-control" id='export_file_type'>
                            <option value='xls' selected>XLS</option>
                            <option value='xlsx'>XLSX</option>
                            <option value='csv'>CSV</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke">
                    <form id='exportData' style="width:100%;" method='post' action='export'>
                        <input type="hidden" name="export-id" id="export-id" required>
                        <input type="hidden" name="export-type" value='xls' id="export-type" required>
                        <input type="hidden" name="export-data" id="export-data" required>
                        <button type="submit" class="btn btn-primary btn-icon icon-left" name="export_data" style="width:100%;"><i class="fas fa-trash-alt"></i> Export Data As <span class="export_type">xls</span> File</button>
                    </form>
                </div>
            </div>
          </div>
    </div>
    