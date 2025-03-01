@extends('layouts.app')
@section('title',  __('barcode.add_barcode_setting'))

@section('content')
<style type="text/css">



</style>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1 class="tw-text-xl md:tw-text-3xl tw-font-bold tw-text-black">@lang('barcode.add_barcode_setting')</h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content">
{!! Form::open(['url' => action([\App\Http\Controllers\BarcodeController::class, 'store']), 'method' => 'post', 
'id' => 'add_barcode_settings_form' ]) !!}
	@component('components.widget')
  <div class="row">
     <!-- Box to preview the barcode size -->
<div id="preview-box" style="border: 1px solid #000; margin-top: 20px;">
  <p>Preview:</p>
  <div id="barcode-preview" style="width: 100px; height: 100px; background-color: #ddd;"></div>
</div>
    <div class="col-sm-12">
      <div class="form-group">
        {!! Form::label('name', __('barcode.setting_name') . ':*') !!}
          {!! Form::text('name', null, ['class' => 'form-control', 'required',
          'placeholder' => __('barcode.setting_name')]); !!}
      </div>
    </div>
    <div class="col-sm-12">
      <div class="form-group">
        {!! Form::label('description', __('barcode.setting_description') ) !!}
          {!! Form::textarea('description', null, ['class' => 'form-control',
          'placeholder' => __('barcode.setting_description'), 'rows' => 3]); !!}
      </div>
    </div>
    <div class="col-sm-12">
      <div class="form-group">
        <div class="checkbox">
          <label>
            {!! Form::checkbox('is_continuous', 1, false, ['id' => 'is_continuous']); !!} @lang('barcode.is_continuous')</label>
          </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
         {!! Form::label('top_margin', __('barcode.top_margin') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>
          </span>
          {!! Form::number('top_margin', 0, ['class' => 'form-control',
          'placeholder' => __('barcode.top_margin'), 'min' => 0, 'step' => 0.00001, 'required']); !!}
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! Form::label('left_margin', __('barcode.left_margin') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
          </span>
          {!! Form::number('left_margin', 0, ['class' => 'form-control',
          'placeholder' => __('barcode.left_margin'), 'min' => 0, 'step' => 0.00001, 'required']); !!}
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! Form::label('width', __('barcode.width') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-text-width" aria-hidden="true"></i>
          </span>
          {!! Form::number('width', null, ['class' => 'form-control',
          'placeholder' => __('barcode.width'), 'min' => 0.1, 'step' => 0.00001, 'required']); !!}
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! Form::label('height', __('barcode.height') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-text-height" aria-hidden="true"></i>
          </span>
          {!! Form::number('height', null, ['class' => 'form-control',
          'placeholder' => __('barcode.height'), 'min' => 0.1, 'step' => 0.00001, 'required']); !!}
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! Form::label('paper_width', __('barcode.paper_width') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-text-width" aria-hidden="true"></i>
          </span>
          {!! Form::number('paper_width', null, ['class' => 'form-control',
          'placeholder' => __('barcode.paper_width'), 'min' => 0.1, 'step' => 0.00001, 'required']); !!}
        </div>
      </div>
    </div>
    <div class="col-sm-6 paper_height_div">
      <div class="form-group">
        {!! Form::label('paper_height', __('barcode.paper_height') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-text-height" aria-hidden="true"></i>
          </span>
          {!! Form::number('paper_height', null, ['class' => 'form-control',
          'placeholder' => __('barcode.paper_height'), 'min' => 0.1, 'step' => 0.00001, 'required']); !!}
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! Form::label('stickers_in_one_row', __('barcode.stickers_in_one_row') . ':*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
          </span>
          {!! Form::number('stickers_in_one_row', null, ['class' => 'form-control',
          'placeholder' => __('barcode.stickers_in_one_row'), 'min' => 1, 'required']); !!}
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! Form::label('row_distance', __('barcode.row_distance') . ' ('. __('barcode.in_in') . '):*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-resize-vertical" aria-hidden="true"></span>
          </span>
          {!! Form::number('row_distance', 0, ['class' => 'form-control',
          'placeholder' => __('barcode.row_distance'), 'min' => 0, 'step' => 0.00001, 'required']); !!}
        </div>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="form-group">
        {!! Form::label('col_distance', __('barcode.col_distance') . ' ('. __('barcode.in_in') . '):*') !!}
         <div class="input-group">
          <span class="input-group-addon">
            <span class="glyphicon glyphicon-resize-horizontal" aria-hidden="true"></span>
          </span>
          {!! Form::number('col_distance', 0, ['class' => 'form-control',
          'placeholder' => __('barcode.col_distance'), 'min' => 0, 'step' => 0.00001, 'required']); !!}
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-6 stickers_per_sheet_div">
      <div class="form-group">
        {!! Form::label('stickers_in_one_sheet', __('barcode.stickers_in_one_sheet') . ':*') !!}
        <div class="input-group">
          <span class="input-group-addon">
            <i class="fa fa-th" aria-hidden="true"></i>
          </span>
          {!! Form::number('stickers_in_one_sheet', null, ['class' => 'form-control',
          'placeholder' => __('barcode.stickers_in_one_sheet'), 'min' => 1, 'required']); !!}
        </div>
      </div>
    </div>
    <div class="clearfix"></div>
    <div class="col-sm-6">
      <div class="form-group">
        <div class="checkbox">
          <label>
            {!! Form::checkbox('is_default', 1); !!} @lang('barcode.set_as_default')</label>
          </div>
      </div>
    </div>
    <div class="col-sm-12 text-center">
      <button type="submit" class="tw-dw-btn tw-dw-btn-primary tw-dw-btn-lg tw-text-white">@lang('messages.save')</button>
    </div>
  </div>

 

<style>
  #barcode-preview {
  display: inline-block;
  background-color: #ddd;
  transition: all 0.3s ease;
}

</style>
<script>
// Script to dynamically update the preview box with inch-to-pixel conversion
document.addEventListener('DOMContentLoaded', function () {
  // Constants to convert inches to pixels (1 inch = 96 pixels)
  const INCH_TO_PIXEL = 96;

  // Get all the input fields that affect the size
  const widthInput = document.querySelector('input[name="width"]');
  const heightInput = document.querySelector('input[name="height"]');
  const topMarginInput = document.querySelector('input[name="top_margin"]');
  const leftMarginInput = document.querySelector('input[name="left_margin"]');

  // Get the preview element
  const barcodePreview = document.getElementById('barcode-preview');

  // Function to update the preview box
  function updatePreview() {
    // Get the current values in inches and convert them to pixels
    const widthInches = parseFloat(widthInput.value) || 0;
    const heightInches = parseFloat(heightInput.value) || 0;
    const topMarginInches = parseFloat(topMarginInput.value) || 0;
    const leftMarginInches = parseFloat(leftMarginInput.value) || 0;

    // Convert inches to pixels
    const widthPixels = widthInches * INCH_TO_PIXEL;
    const heightPixels = heightInches * INCH_TO_PIXEL;
    const topMarginPixels = topMarginInches * INCH_TO_PIXEL;
    const leftMarginPixels = leftMarginInches * INCH_TO_PIXEL;

    // Update the style of the preview box with the pixel values
    barcodePreview.style.width = `${widthPixels}px`;
    barcodePreview.style.height = `${heightPixels}px`;
    barcodePreview.style.marginTop = `${topMarginPixels}px`;
    barcodePreview.style.marginLeft = `${leftMarginPixels}px`;
  }

  // Event listeners to update preview when input values change
  widthInput.addEventListener('input', updatePreview);
  heightInput.addEventListener('input', updatePreview);
  topMarginInput.addEventListener('input', updatePreview);
  leftMarginInput.addEventListener('input', updatePreview);
});


</script>
  @endcomponent
  {!! Form::close() !!}
</section>
<!-- /.content -->
@endsection