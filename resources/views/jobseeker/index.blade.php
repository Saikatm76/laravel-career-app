@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            Send Your Details to Laravel Career
                        </h4>
                    </div>
                    <div class="card-body">
                        <form id="AddJobFORM" method="POST" enctype="multipart/form-data">
                            <div class="card-body">

                                <ul class="alert alert-warning d-none" id="save_errorList"></ul>

                                <div class="form-group mb-3">
                                    <label>*Name:</label>
                                    <input type="text" name="name" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label>*Contact Number:</label>
                                    <input type="text" name="contact" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label>*Email:</label>
                                    <input type="email" name="email" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label>*Skillsets:</label>
                                    <textarea name="skillsets" placeholder="add your skills by giving 'comma'" rows="2" cols="3"
                                        class="form-control"></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label>*Total Experience:</label>
                                    <input type="number" name="experience" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Current Organization:</label>
                                    <input type="text" name="org" class="form-control">
                                </div>
                                <div class="form-group mb-3">
                                    <label>Additional Remarks:</label>
                                    <textarea name="remarks" rows="4" cols="5" class="form-control"></textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label>*Resume:</label>
                                    <input type="file" name="resume" class="form-control">
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')
    <script type="text/javascript">
        $(document).ready(function() {


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });



            $(document).on('submit', '#AddJobFORM', function(e) {

                e.preventDefault();

                let formData = new FormData($('#AddJobFORM')[0]);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('submit_form') }}",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 400) {
                            $('#save_errorList').html('');
                            $('#save_errorList').removeClass('d-none');
                            $.each(response.errors, function(key, err_value) {
                                $('#save_errorList').append(`<li>${err_value}</li>`)
                            })
                        } else if (response.status == 200) {
                            $('#save_errorList').html('');
                            $('#save_errorList').addClass('d-none');

                            $('#AddJobFORM').find('input').val('');
                            alert(response.message);

                        }
                    }
                });
            });
        });
    </script>
@endpush
