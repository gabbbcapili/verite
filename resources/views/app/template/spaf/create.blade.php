@inject('request', 'Illuminate\Http\Request')
@extends('layouts/contentLayoutMaster')
@section('title', 'Create SPAF Template')

@section('vendor-style')
@endsection

@section('content')

<section id="basic-vertical-layouts">
    <form action="{{ route('template.spaf.store') }}" method="POST" class="form form-vertical" enctype="multipart/form-data">
        @csrf
      <div class="row">
          <div class="col-12">
              <div class="card">
                  <div class="card-content">
                      <div class="card-body">
                          <div class="row mb-2">
                            <div class="col-4">
                                <label>Template Name*:</label>
                                <input class="form-control" type="text" name="name">
                            </div>
                          </div>
                      </div>
                  </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- create question -->
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="card">
                    <div class="card-header">
                      <h4 class="card-title">Add & Edit Your Questions</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">

                                <div class="row mt-1">
                                    <div class="col-md-12 col-sm-12" id="">
                                      <div class="list-group"role="tablist"  id="sortable-pages">
                                      </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- create question -->
            <!-- show question -->
                <div class="col-lg-8 col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                          <h4 class="card-title text-center">Template Preview</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">

                                </div>
                            </div>
                    </div>
                </div>
            </div>
            <!-- show question -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-12 align-items-center justify-content-center text-center">
                              <button type="submit" class="btn btn-primary me-1 btn_save">Submit</button>
                              <button type="reset" class="btn btn-outline-secondary">Clear</button>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </form>
</section>
@endsection

@section('vendor-script')


@endsection

@section('page-script')
    <script src="{{ asset('js/scripts/forms-validation/form-normal.js') }}"></script>
@endsection
