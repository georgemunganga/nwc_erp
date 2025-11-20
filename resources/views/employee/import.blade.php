{{ Form::open(array('route' => 'employee.import', 'method'=>'post', 'enctype' => "multipart/form-data", 'class' => 'needs-validation', 'novalidate', 'id' => 'employee-import-form')) }}

<div class="modal-body">
    <div class="row" id="import-form-content">
        <div class="col-md-12 mb-3">
            {{Form::label('file',__('Download sample employee file'),['class'=>'form-label'])}}
            <a href="{{asset(Storage::url('uploads/sample')).'/sample-employee.csv'}}" class="btn btn-sm btn-primary" download="">
                <i class="ti ti-download"></i> {{__('Download Sample CSV')}}
            </a>
        </div>
        <div class="col-md-12">
            <div class="alert alert-info">
                <strong>{{__('Import Instructions:')}}</strong>
                <ul class="mb-0 mt-2">
                    <li>{{__('Download the sample CSV file above to see the required format')}}</li>
                    <li>{{__('Fill in your employee data using the exact column names from the sample')}}</li>
                    <li>{{__('You can provide either Full Name OR First Name/Middle Name/Last Name (system will auto-construct full name from parts)')}}</li>
                    <li>{{__('If an employee email already exists, their information will be updated')}}</li>
                    <li>{{__('Branch, Department, and Designation names are case-insensitive (e.g., "HR" = "hr" = "Hr")')}}</li>
                    <li>{{__('Make sure Branch, Department, and Designation names match existing records in your system')}}</li>
                    <li>{{__('Reports To field should contain the Employee ID of the manager')}}</li>
                    <li>{{__('Rows with errors will be skipped automatically')}}</li>
                    <li>{{__('Supported formats: CSV, XLS, XLSX')}}</li>
                </ul>
            </div>
        </div>
        <div class="col-md-12">
            {{Form::label('file',__('Select File'),['class'=>'form-label'])}}
            <div class="choose-file form-group">
                <label for="file" class="form-label">
                    <input type="file" class="form-control" name="file" id="file" data-filename="upload_file" required accept=".csv,.xls,.xlsx">
                </label>
                <p class="upload_file"></p>
            </div>
        </div>
    </div>

    <!-- Loading indicator (hidden by default) -->
    <div class="row" id="import-loading" style="display: none;">
        <div class="col-12 text-center py-5">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">{{__('Loading...')}}</span>
            </div>
            <h5 class="mt-3">{{__('Processing import...')}}</h5>
            <p class="text-muted">{{__('Please wait, this may take a few moments for large files.')}}</p>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-secondary" data-bs-dismiss="modal" id="cancel-btn">

    <button type="submit" value="{{ __('Import') }}" class="btn btn-primary" id="import-btn">
        {{__('Import Employees')}}
    </button>
</div>
{{ Form::close() }}

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('employee-import-form');
    const formContent = document.getElementById('import-form-content');
    const loadingIndicator = document.getElementById('import-loading');
    const importBtn = document.getElementById('import-btn');
    const cancelBtn = document.getElementById('cancel-btn');

    form.addEventListener('submit', function(e) {
        // Show loading, hide form
        formContent.style.display = 'none';
        loadingIndicator.style.display = 'block';
        importBtn.disabled = true;
        cancelBtn.disabled = true;

        // Form will submit normally (server-side processing)
    });
});
</script>
