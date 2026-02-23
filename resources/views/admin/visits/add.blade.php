@extends('layouts.admin')
<title>Add Visit</title>

@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                        <h4 class="text-white text-capitalize ps-3">Add Visit</h4>
                    </div>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-link float-end" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add New Horse ??
                    </button>
                </div>
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-3">
                                <div>
                                    <form action="{{ url('insert-horse') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <h3 class="text-center text-primary">Add New Horse</h3>
                                            <div class="col-6 mt-4">
                                                <select class="form-select" required name="stud_id">
                                                    <option value="">Select Stud</option>
                                                    @foreach ($studs as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label for="">Name</label>
                                                <input type="text" class="form-control" required name="name">
                                            </div>
                                            <div class="col-8">
                                                <label for="">Shelter ?!!</label>
                                                <input type="text" class="form-control"  name="shelter">
                                            </div>
                                            <div class="col-4 mt-4">
                                                <label for="">Male ?!!</label>
                                                <input type="checkbox" name="gender">
                                            </div>
                                            <div class="m-2">
                                                <label for="">Description</label>
                                                <textarea required name="description" class="form-control"></textarea>
                                            </div>
                                            <div class="m-2">
                                                <label for="">Choose Image</label>
                                                <input type="file" name="image" required class="w-100">
                                            </div>
                                            <div class="m-2">
                                                <button type="submit" class="btn btn-primary">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2 m-3">
                    <form action="{{ url('insert-visit') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="mr-2 col-6 col-md-4">
                                <select class="form-select" required name="stud_id" id="studSelect">
                                    <option value="">Select Stud</option>
                                    @foreach ($studs as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="itemvisits">
                            <div class="row">
                                @php
                                    $key = 0;
                                @endphp
                                <div class="mb-3 col-md-3 col-6">
                                    <select class="form-select horseSelect" required name="visitdescs[0][horse_id]" id="horseSelect">
                                        <option value="">Select a Horse</option>
                                        @foreach ($horses as $item)
                                        <option value="{{ $item->id }}" data-stud="{{ $item->stud_id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3 col-md-3 col-6">
                                    <input type="text" class="form-control" required name="visitdescs[0][case]" placeholder="Case">
                                </div>
                                <div class="mb-3 col-md-6 col-6">
                                    <textarea name="visitdescs[0][description]" placeholder="Case Description" class="form-control"></textarea>
                                </div>
                                <div class="mb-3 col-md-6 col-6">
                                    <textarea name="visitdescs[0][treatment]" placeholder="Case Treatment" class="form-control"></textarea>
                                </div>
                                <div class="col-md-3 col-6">
                                    <label for="">Choose Image</label>
                                    <input type="file" name="visitdescs[0][image]" required class="w-100">
                                </div>
                                <div class="my-4 col-md-3 col-6">
                                    <input type="number" min="1" class="form-control" name="visitdescs[0][caseprice]" placeholder='Case Cost'>
                                </div>
                                <div class="mb-3 col-12">
                                    <button type="button" id="addMoreVisit" class="btn btn-primary float-end" title="add more row">+</button>

                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-6 col-md-3 ">
                                <select class="form-select"  name="user_id">
                                    <option value="{{ Auth::user()->id }}">Select Doctor (Optional)</option>
                                    @foreach ($users as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-6 col-md-3">
                                <input type="number" placeholder="Visit Cost" class="form-control" name="visitprice">
                            </div>
                            <div class="mb-3 col-6 col-md-3">
                                <input type="number" placeholder="Discount" class="form-control" name="discount">
                            </div>

                            <div class="mb-3 col-6 col-md-3">
                                <input type="number" placeholder="Paid" class="form-control" name="paid">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary float-end">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
    var i = 0;

    // مبدئيًا: اقفل اختيار الخيل + زر الإضافة لحد ما يختار Stud
    // $("#addMoreVisit").prop("disabled", true);
    $(".horseSelect").prop("disabled", true);

    // دالة فلترة الخيول حسب الـ Stud
    function filterHorseSelects(studId, $context) {
        var $targets = $context ? $context.find('.horseSelect') : $('.horseSelect');
        $targets.each(function(){
            var $sel = $(this);
            $sel.find('option').each(function(){
                var $opt = $(this);
                if ($opt.val() === "" || $opt.data('stud') == studId) {
                    $opt.show();
                } else {
                    // اخفي الاختيارات من مزارع أخرى ولو كانت مختارة فضّيها
                    if ($sel.val() == $opt.val()) { $sel.val(""); }
                    $opt.hide();
                }
            });
        });
    }

    // عند تغيير الـ Stud
    $("#studSelect").change(function() {
        var selectedStud = $(this).val();

        if (!selectedStud) {
            $(".horseSelect").val("").prop("disabled", true);
            $("#addMoreVisit").prop("disabled", true);
            return;
        }

        $(".horseSelect").prop("disabled", false);
        $("#addMoreVisit").prop("disabled", false);
        filterHorseSelects(selectedStud); // فلترة الموجود
    });

    // إضافة صف جديد
    $("#addMoreVisit").click(function(e){
        e.preventDefault();

        var selectedStud = $("#studSelect").val();
        if (!selectedStud) {
            alert("Choose The Stud First");
            return;
        }

        i++;
        var newRow = `
        <div class="row addlRow">
            <div class="mb-3 col-md-3 col-6">
                <select class="form-select horseSelect" required name="visitdescs[${i}][horse_id]">
                    <option value="">Select a Horse</option>
                    @foreach ($horses as $item)
                        <option value="{{ $item->id }}" data-stud="{{ $item->stud_id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3 col-md-3 col-6">
                <input type="text" min="1" class="form-control" name="visitdescs[${i}][case]" required placeholder="Case"/>
            </div>
            <div class="mb-3 col-md-6 col-6">
                <textarea name="visitdescs[${i}][description]" placeholder="Case Description" class="form-control"></textarea>
            </div>
            <div class="mb-3 col-md-6 col-6">
                <textarea name="visitdescs[${i}][treatment]" placeholder="Case Treatment" class="form-control"></textarea>
            </div>
            <div class="col-md-3 col-6">
                <label>Choose Image</label>
                <input type="file" name="visitdescs[${i}][image]" required class="w-100">
            </div>
            <div class="my-4 col-md-3 col-6">
                <input type="number" min="1" class="form-control" placeholder="Case Cost" name="visitdescs[${i}][caseprice]" />
            </div>
            <div class="mb-3 col-12">
                <button type="button" id="addMoreVisit" class="btn btn-primary float-end" title="add more row">+</button>
                <button type="button" class="btn btn-danger float-end mx-2 mt-2 removeRow" title="remove">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
            <hr>
        </div>`;

        $(".itemvisits").append(newRow);

        // فلترة صف الخيول اللي لسه مضاف حسب الـ Stud الحالي
        var $newRow = $(".itemvisits .addlRow").last();
        filterHorseSelects(selectedStud, $newRow);
    });

    // حذف صف
    $(document).on('click', '.removeRow', function(e){
        e.preventDefault();
        $(this).closest('.addlRow').remove();
    });
});
</script>

