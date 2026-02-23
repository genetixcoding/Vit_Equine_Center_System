@extends('layouts.app')
<title>Vit Equine Center System</title>
@section('content')


    @if ($notes->isNotEmpty()|| $tasks->isNotEmpty())
        @if ($notes->isNotEmpty())
        <div class="container-fluid py-4">
        <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">{{ __('language.my') }} {{ __('language.notes') }}</h4>
                    </div>
                </div>
                <div class="container-fluid p-2 mt-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="">
                                            <h6 class="text-primary">{{ __('language.my') }} {{ __('language.notes') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <table class="table  text-center  table-striped mb-0">
                                        @foreach ($notes as $note)
                                            <tbody class="text-center">
                                                <tr>
                                                    <td>
                                                        {{ $note->description }}
                                                    </td>
                                                    <td>{{ $note->created_at->format('d/m/Y h:i A') }}</td>

                                                </tr>
                                            </tbody>
                                        @endforeach
                                        <div class="pages text-center">
                                            {{ $notes->links() }}
                                        </div>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
        @endif
        @if ($tasks->isNotEmpty())
        <div class="container-fluid py-4">
        <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">{{ __('language.my') }} {{ __('language.tasks') }}</h4>
                    </div>
                </div>
                <div class="container-fluid p-2 mt-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="">
                                            <h6 class="text-primary">{{ __('language.my') }} {{ __('language.tasks') }}</h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <table class="table  text-center  table-striped mb-0">
                                        @foreach ($tasks as $taskitem)
                                        <thead class="text-center">
                                            <tr>
                                                <th colspan="2" style="background-color: #338ded; color: #FFF;">
                                                    @if ($taskitem->taskdesc->where('status', 1)->count() == $taskitem->taskdesc->count())
                                                    {{ __('language.tasks') }} {{ __('language.completed') }}
                                                    @elseif($taskitem->taskdesc->where('status', 0)->count() == $taskitem->taskdesc->count())
                                                    {{ __('language.tasks') }} {{ __('language.uncompleted') }}
                                                    @else
                                                    {{ __('language.achieved') }}  {{ $taskitem->taskdesc->where('status', 1)->count() }} {{ __('language.tasks') }}
                                                    <br>
                                                    {{ __('language.uncompleted') }} {{ $taskitem->taskdesc->count() }} {{ __('language.tasks') }}
                                                    @endif
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            @foreach ($taskitem->taskdesc as $item)
                                                <tr>
                                                    <td class="text-primary">
                                                        {{ $item->task }}
                                                    </td>
                                                    @if ($item->status == '0')
                                                    <div class="modal fade" id="exampleModal{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="card-body py-5">
                                                                    <form action="{{ url('update-taskdesc/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="row m-2 text-center">
                                                                            <div>
                                                                                <input type="hidden"  class="form-control" value="{{ $item->task }}" name="task">
                                                                                <input type="hidden"  class="form-control" value="{{ $item->hores_id }}" name="hores_id">
                                                                                <input type="hidden" name="status" value="0"> <!-- Hidden input ensures unchecked value is sent -->
                                                                                <input type="checkbox" value="1" {{ $item->status == '1' ? 'checked' : '' }} name="status">
                                                                                <label for="">{{ __('language.checktask') }}</label>
                                                                            </div>
                                                                            <div class="mt-3">
                                                                                <button type="submit" class="btn btn-primary">{{ __('language.done') }}</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <td class="">
                                                        <a class="text-primary m-1 text-sm" data-bs-toggle="modal" data-bs-target="#exampleModal{{ $item->id }}">{{ __('language.done') }} {{ __('language.???!') }}</a>
                                                    </td>
                                                    @else
                                                        <td class="text-success">
                                                            <i class="fa fa-check"></i> at  <br><label for="" class="text-info">{{ date(' h:iA . .  d/M/y', strtotime($item->updated_at)) }}</label>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        @endforeach
                                    </table>
                                    <div class="pages text-center">
                                        {{ $tasks->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        </div>
        </div>
        @endif
    @else
    <h1 class="text-center text-primary my-3">{{ __('language.no_data_found') }}</h1>
    @endif
    <div class="container-fluid py-4">
        <div class="row  mb-4">
            <div class="col mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a data-bs-toggle="modal" data-bs-target="#exampleModalnote" class="btn btn-link float-end text-sm">{{ __('language.addnew') }} {{ __('language.notes') }}</a>
                                <div class="modal fade" id="exampleModalnote" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="card-body py-5">
                                                <form action="{{ url('insert-note') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row m-2">
                                                        <h3 class="text-center text-primary">{{ __('language.addnew') }} {{ __('language.notes') }}</h3>
                                                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                                        <div class="mb-2 col-12">
                                                            <select name="manager_id" class="form-control">
                                                                <option value="">{{ __('language.select') }} {{ __('language.manager') }}</option>
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-12"><label for="">{{ __('language.description') }}</label>
                                                            <textarea type="text" class="form-control"  name="description" required placeholder="{{ __('language.insert') }} {{ __('language.description') }}"></textarea>
                                                        </div>
                                                        <div class="m-2">
                                                            <button type="submit" class="btn btn-primary">{{ __('language.submit') }}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-tasks" aria-hidden="true"></i> Finished </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a data-bs-toggle="modal" data-bs-target="#exampleModalexpenses" class="btn btn-link float-end text-sm">{{ __('language.addnew') }} {{ __('language.expenses') }}</a>
                                <div class="modal fade" id="exampleModalexpenses" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="card-body py-5">
                                                <form action="{{ url('insert-expense') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row m-2">
                                                        <h3 class="text-center text-primary">{{ __('language.addnew') }} {{ __('language.expenses') }}</h3>
                                                        <div class="col-6 m-auto">
                                                            <select class="form-select" required name="finance_id">
                                                            <option value="">{{ __('language.select') }} {{ __('language.finance') }}</option>
                                                            @foreach ($finances as $item)
                                                            <option value="{{ $item->id }}">{{ $item->amount }}  / {{ $item->decamount }}  / {{ date('h:iA d-M-y', strtotime($item->created_at)) }}</option>
                                                            @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-6 m-auto">
                                                            <input type="number" class="form-control" required name="cost" placeholder="{{ __('language.insert') }} {{ __('language.cost') }}">
                                                        </div>
                                                        <div class="col-12 mt-4">
                                                            <input type="text" class="form-control" required name="item" placeholder="{{ __('language.insert') }} {{ __('language.item') }}">
                                                        </div>
                                                        <div class="m-2">
                                                            <button type="submit" class="btn btn-primary">{{ __('language.submit') }}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-check-square-o fa-1x text-gray-300"></i> Finished </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             @if (Auth::user()->major == 2)
            <div class="col mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a data-bs-toggle="modal" data-bs-target="#exampleModalvisit" class="btn btn-link float-end text-sm">{{ __('language.addnew') }} {{ __('language.visits') }}</a>
                                <div class="modal fade" id="exampleModalvisit" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="card-body py-5">
                                                <form action="{{ url('insert-visit') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="m-2 col-6">
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
                                                            @php $key = 0; @endphp
                                                            <div class="mb-3 col-6">
                                                                <select class="form-select horseSelect" required name="visitdescs[0][horse_id]" id="horseSelect">
                                                                    <option value="">Select a Horse</option>
                                                                    @foreach ($horses as $item)
                                                                    <option value="{{ $item->id }}" data-stud="{{ $item->stud_id }}">{{ $item->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3 col-6">
                                                                <input type="text" class="form-control" required name="visitdescs[0][case]" placeholder="{{ __('language.case') }}">
                                                            </div>
                                                            <div class="mb-3 col-6">
                                                                <textarea name="visitdescs[0][description]" placeholder="{{ __('language.case') }} {{ __('language.description') }}" class="form-control"></textarea>
                                                            </div>
                                                            <div class="mb-3 col-6">
                                                                <textarea name="visitdescs[0][treatment]" placeholder="{{ __('language.case') }} {{ __('language.treatment') }}" class="form-control"></textarea>
                                                            </div>
                                                            <div class="col-6">
                                                                <label for="">{{ __('language.chooseimage') }}</label>
                                                                <input type="file" name="visitdescs[0][image]" required class="w-100">
                                                            </div>
                                                            <div class="my-4 col-6">
                                                                <input type="number" min="1" class="form-control" name="visitdescs[0][caseprice]" placeholder='{{ __('language.cost') }} {{ __('language.price') }}'>
                                                            </div>
                                                            <div class="mb-3 col-12">
                                                                <button type="button" id="addMoreVisit" class="btn btn-primary float-end" title="add more row">+</button>
                                                            </div>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                    <div class="row">

                                                        <div class="mb-3 col">
                                                            <input type="number" placeholder="{{ __('language.cost') }} {{ __('language.visits') }}" class="form-control" name="visitprice">
                                                        </div>
                                                        <div class="mb-3 col">
                                                            <input type="number" placeholder="{{ __('language.discount') }}" class="form-control" name="discount">
                                                        </div>

                                                        <div class="mb-3 col">
                                                            <input type="number" placeholder="{{ __('language.paid') }}" class="form-control" name="paid">
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                                    <button type="submit" class="btn btn-primary float-end">{{ __('language.submit') }}</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-hospital-o fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a data-bs-toggle="modal" data-bs-target="#exampleModaltreatment" class="btn btn-link float-end text-sm">{{ __('language.addnew') }} {{ __('language.treatments') }}</a>
                                <div class="modal fade" id="exampleModaltreatment" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="card-body py-5">
                                                 <form action="{{ url('insert-treatment') }}" method="POST" enctype="multipart/form-data">
                                                @csrf

                                                <div class="row">
                                                    @if (Auth::user()->major == 2)
                                                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                                    @else
                                                    <div class="mb-3 col-12 w-100">
                                                        <label for=""> {{ __('language.optional') }}</label>
                                                        <select class="form-select"  name="user_id">
                                                            <option value="{{ Auth::user()->id }}">{{ __('language.select') }} {{ __('language.doctor') }}</option>
                                                            @foreach ($doctors as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @endif
                                                    <div class="mb-2 col-12">
                                                        <label for="">({{ __('language.optional') }})</label>
                                                        <select class="form-select" name="embryo_id">
                                                            <option value="">{{ __('language.select') }} {{ __('language.embryo') }}</option>
                                                            @foreach ($embryos as $item)
                                                            <option value="{{ $item->id }}">{{ $item->localhorsename }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-2 col-6">
                                                        <label for="">({{ __('language.optional') }})</label>
                                                        <select class="form-select" required name="stud_id" id="treatmentStudSelect">
                                                            <option value="">Select Stud</option>
                                                            @foreach ($studs as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-2 col-6">
                                                        <label for="">({{ __('language.optional') }})</label>
                                                        <select class="form-select horseSelectTreatment" name="horse_id" id="treatmentHorseSelect" disabled>
                                                            <option value="">{{ __('language.select') }} {{ __('language.horse') }}</option>
                                                            @foreach ($horses as $item)
                                                            <option value="{{ $item->id }}" data-stud="{{ $item->stud_id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                </div>
                                                <div class="itemtreatments">
                                                    <div class="row">
                                                        @php $treatmentKey = 0; @endphp
                                                        <div class="mb-2 col-6">
                                                            <select class="form-select" required name="treatmentdesc[0][pharmacy_id]">
                                                                <option value="">{{ __('language.select') }} {{ __('language.medicine') }}</option>
                                                                @foreach ($pharmacy as $item)
                                                                <option value="{{ $item->id }}">{{ $item->item }} / {{ $item->unitqty }} {{ $item->type }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-2 col-12">
                                                            <textarea name="treatmentdesc[0][description]" required class="form-control" placeholder="{{ __('language.insert') }} {{ __('language.description') }}"></textarea>
                                                        </div>
                                                        <div class="mb-2 col-5">
                                                            <input type="number" required class="form-control" placeholder="{{ __('language.insert') }} {{ __('language.doses') }}" name="treatmentdesc[0][qty]">
                                                        </div>
                                                        <div class="mb-2 col-5">
                                                            <input type="text" required class="form-control" placeholder="{{ __('language.insert') }} {{ __('language.type') }}" name="treatmentdesc[0][type]">
                                                        </div>
                                                        <div class="p-2 col-2">
                                                            <button type="button" id="addMoreTreatments" class="btn btn-primary" title="add more row">+</button>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                </div>
                                                <button type="submit" class="btn btn-primary float-end">{{ __('language.submit') }}</button>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-hospital-o fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if (Auth::user()->major == 3)
            <div class="col mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a data-bs-toggle="modal" data-bs-target="#exampleModalfeeding" class="btn btn-link float-end text-sm">{{ __('language.addnew') }} {{ __('language.feeding') }}</a>
                                <div class="modal fade" id="exampleModalfeeding" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="card-body py-5">
                                                <form action="{{ url('insert-feeding') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <h3 class="text-center text-primary">{{ __('language.addnew') }} {{ __('language.feeding') }}</h3>
                                                        <div class="col-6 m-auto my-4">
                                                            <select class="form-select" required name="feedbed_id">
                                                            <option value="">{{ __('language.select') }} {{ __('language.feedingbedding') }}</option>
                                                            @foreach ($feedingbedings as $itemfb)
                                                            <option value="{{ $itemfb->id }}">
                                                                {{ $itemfb->item }} / {{ $itemfb->qty }} / {{ $itemfb->decqty }}
                                                                / {{ date('h:iA d-M-y', strtotime($itemfb->created_at)) }}</option>
                                                            @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-6 my-4">
                                                            <select class="form-select" required name="horse_id">
                                                            <option value="">{{ __('language.select') }} {{ __('language.horse') }}</option>
                                                            @foreach ($horses as $itemh)
                                                                @if ($itemh->shelter !== null || $itemh->stud_id == 1)
                                                                <option value="{{ $itemh->id }}">{{ $itemh->name }}</option>
                                                                @endif
                                                            @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-8 mb-2">
                                                            <input type="text" class="form-control" placeholder="{{ __('language.item') }}" required name="item">
                                                        </div>
                                                        <div class="col-4 mb-2">
                                                            <input type="number" class="form-control" placeholder="{{ __('language.qty') }}" required name="qty">
                                                        </div>
                                                        <div class="col-12 mb-2">
                                                            <button type="submit" class="btn btn-primary">{{ __('language.submit') }}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-calendar fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a data-bs-toggle="modal" data-bs-target="#exampleModalbedding" class="btn btn-link float-end text-sm">{{ __('language.addnew') }} {{ __('language.bedding') }}</a>
                                <div class="modal fade" id="exampleModalbedding" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="card-body py-5">
                                                <form action="{{ url('insert-bedding') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <h3 class="text-center text-primary">{{ __('language.addnew') }} {{ __('language.bedding') }}</h3>
                                                        <div class="col-6 my-4">
                                                            <select class="form-select" required name="feedbed_id">
                                                            <option value="">{{ __('language.select') }} {{ __('language.feedingbedding') }}</option>
                                                            @foreach ($feedingbedings as $item)
                                                            <option value="{{ $item->id }}">{{ $item->item }} / {{ $item->qty }} / {{ $item->decqty }} / {{ date('h:iA d-M-y', strtotime($item->created_at)) }}</option>
                                                            @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-6 my-4">
                                                            <select class="form-select" required name="horse_id">
                                                            <option value="">{{ __('language.select') }} {{ __('language.horse') }}</option>
                                                            @foreach ($horses as $item)
                                                                @if ($item->shelter !== null || $item->stud_id == 1)
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                @endif
                                                            @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-8 mb-2">
                                                            <input type="text" class="form-control" placeholder="{{ __('language.item') }}" required name="item">
                                                        </div>
                                                        <div class="col-4 mb-2">
                                                            <input type="number" class="form-control" placeholder="{{ __('language.qty') }}" required name="qty">
                                                        </div>
                                                        <div class="col-12 mb-2">
                                                            <button type="submit" class="btn btn-primary">{{ __('language.submit') }}</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-calendar fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a data-bs-toggle="modal" data-bs-target="#exampleModaltreatment" class="btn btn-link float-end text-sm">{{ __('language.addnew') }} {{ __('language.treatments') }}</a>
                                <div class="modal fade" id="exampleModaltreatment" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="card-body py-5">
                                                 <form action="{{ url('insert-treatment') }}" method="POST" enctype="multipart/form-data">
                                                @csrf


                                                <div class="row">
                                                    @if (Auth::user()->major == 2)
                                                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                                        @else
                                                        <div class="mb-3 col-12 w-100">
                                                            <label for=""> {{ __('language.optional') }}</label>
                                                            <select class="form-select"  name="user_id">
                                                                <option value="{{ Auth::user()->id }}">{{ __('language.select') }} {{ __('language.doctor') }}</option>
                                                                @foreach ($doctors as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    @endif
                                                    <div class="mb-2 col-12">
                                                        <label for="">({{ __('language.optional') }})</label>
                                                        <select class="form-select" name="embryo_id">
                                                            <option value="">{{ __('language.select') }} {{ __('language.embryo') }}</option>
                                                            @foreach ($embryos as $item)
                                                            <option value="{{ $item->id }}">{{ $item->localhorsename }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-2 col-6">
                                                        <label for="">({{ __('language.optional') }})</label>
                                                        <select class="form-select" required name="stud_id" id="treatmentStudSelect">
                                                            <option value="">Select Stud</option>
                                                            @foreach ($studs as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-2 col-6">
                                                        <label for="">({{ __('language.optional') }})</label>
                                                        <select class="form-select horseSelectTreatment" name="horse_id" id="treatmentHorseSelect" disabled>
                                                            <option value="">{{ __('language.select') }} {{ __('language.horse') }}</option>
                                                            @foreach ($horses as $item)
                                                            <option value="{{ $item->id }}" data-stud="{{ $item->stud_id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="itemtreatments">
                                                    <div class="row">
                                                        @php $treatmentKey = 0; @endphp
                                                        <div class="mb-2 col-6">
                                                            <select class="form-select" required name="treatmentdesc[0][pharmacy_id]">
                                                                <option value="">{{ __('language.select') }} {{ __('language.medicine') }}</option>
                                                                @foreach ($pharmacy as $item)
                                                                <option value="{{ $item->id }}">{{ $item->item }} / {{ $item->unitqty }} {{ $item->type }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-2 col-12">
                                                            <textarea name="treatmentdesc[0][description]" required class="form-control" placeholder="{{ __('language.insert') }} {{ __('language.description') }}"></textarea>
                                                        </div>
                                                        <div class="mb-2 col-5">
                                                            <input type="number" required class="form-control" placeholder="{{ __('language.insert') }} {{ __('language.doses') }}" name="treatmentdesc[0][qty]">
                                                        </div>
                                                        <div class="mb-2 col-5">
                                                            <input type="text" required class="form-control" placeholder="{{ __('language.insert') }} {{ __('language.type') }}" name="treatmentdesc[0][type]">
                                                        </div>
                                                        <div class="p-2 col-2">
                                                            <button type="button" id="addMoreTreatments" class="btn btn-primary" title="add more row">+</button>
                                                        </div>
                                                        <hr>
                                                    </div>
                                                </div>
                                                <button
                                                    type="submit"
                                                    class="btn btn-primary float-end">
                                                    {{ __('language.submit') }}
                                                </button>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-hospital-o fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col mb-2">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a data-bs-toggle="modal" data-bs-target="#exampleModalvaccine" class="btn btn-link float-end text-sm">{{ __('language.add') }} {{ __('language.vaccine') }}</a>
                                <div class="modal fade" id="exampleModalvaccine" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="card-body py-5">
                                                <form action="{{ url('insert-vaccine') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="itemvaccines">
                                                        <div class="row">
                                                            @php
                                                                $key = 0;
                                                            @endphp

                                                            <div class="col-6 m-2">
                                                                <select class="form-select" name="vaccinedesc[0][horse_id]">
                                                                    <option value="">{{ __('language.select') }} {{ __('language.horse') }}</option>
                                                                    @foreach ($horses as $item)
                                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="mb-3 col-11">
                                                                <input type="text" required class="form-control" name="vaccinedesc[0][description]" placeholder="{{ __('language.write') }} {{ __('language.description') }}"/>
                                                            </div>
                                                            <div class="col-4">
                                                                <label for="">{{ __('language.choose') }} {{ __('language.image') }}</label>
                                                                <input type="file" name="vaccinedesc[0][image]" required class="w-100">
                                                            </div>
                                                            <div class="mb-3 col-4">
                                                                <button type="button" id ="addMoreVaccines" class="btn btn-primary float-end" title="add more row">+</button>
                                                            </div>
                                                            <hr>
                                                        </div>
                                                    </div>
                                                    <button
                                                        type="submit"
                                                        class="btn btn-primary float-end">
                                                        Send
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <div class="col-auto">
                                <div class="h6 mb-0 font-weight-bold text-gray-300"><i class="fa fa-calendar fa-1x text-gray-300"></i> Attatched</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

@endsection

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>

    $(document).ready(function(){
        var i = 0;
        $(".horseSelect").prop("disabled", true);

        function filterHorseSelects(studId, $context) {
            var $targets = $context ? $context.find('.horseSelect') : $('.horseSelect');
            $targets.each(function(){
                var $sel = $(this);
                $sel.find('option').each(function(){
                    var $opt = $(this);
                    if ($opt.val() === "" || $opt.data('stud') == studId) {
                        $opt.show();
                    } else {
                        if ($sel.val() == $opt.val()) { $sel.val(""); }
                        $opt.hide();
                    }
                });
            });
        }

        $("#studSelect").change(function() {
            var selectedStud = $(this).val();
            if (!selectedStud) {
                $(".horseSelect").val("").prop("disabled", true);
                $("#addMoreVisit").prop("disabled", true);
                return;
            }
            $(".horseSelect").prop("disabled", false);
            $("#addMoreVisit").prop("disabled", false);
            filterHorseSelects(selectedStud);
        });

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
                <div class="mb-3 col-md-6 col-6">
                    <input type="text" class="form-control" required name="visitdescs[${i}][case]" placeholder="{{ __('language.case') }}">
                </div>
                <div class="mb-3 col-md-6 col-6">
                    <textarea name="visitdescs[${i}][description]" placeholder="{{ __('language.case') }} {{ __('language.description') }}" class="form-control"></textarea>
                </div>
                <div class="mb-3 col-md-6 col-6">
                    <textarea name="visitdescs[${i}][treatment]" placeholder="{{ __('language.case') }} {{ __('language.treatment') }}" class="form-control"></textarea>
                </div>
                <div class="col-md-3 col-6">
                    <label for="">{{ __('language.chooseimage') }}</label>
                    <input type="file" name="visitdescs[${i}][image]" required class="w-100">
                </div>
                <div class="my-4 col-md-3 col-6">
                    <input type="number" min="1" class="form-control" name="visitdescs[${i}][caseprice]" placeholder='{{ __('language.cost') }} {{ __('language.price') }}'>
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
            var $newRow = $(".itemvisits .addlRow").last();
            filterHorseSelects(selectedStud, $newRow);
        });

    });

    $(document).ready(function(){
        let i = parseInt("{{ $key }}");

        $("#addMoreTreatments").click(function(e){
            e.preventDefault();
            i++;
            $(".itemtreatments").append('<div class="row addlRow"><div class="my-2 col-6"><select class="form-select" required name="treatmentdesc['+i+'][pharmacy_id]"><option value="">Select Medicine</option>@foreach ($pharmacy as $item)<option value="{{ $item->id }}">{{ $item->item }}</option>@endforeach</select></div><div class="mt-1 col-6"><input type="text" required class="form-control" name="treatmentdesc['+i+'][description]" placeholder="Insert Description"></div><div class="mb-2 col-5"><input type="number" required class="form-control" name="treatmentdesc['+i+'][qty]" placeholder="Insert Doses"></div><div class="mb-2 col-5"><input type="text" required class="form-control" name="treatmentdesc['+i+'][type]" placeholder="Insert Type"></div><div class="p-1 col-2"><button type="button" id="remove" class="btn btn-danger text-center" title="remove"><i class="fa fa-trash"></i></button></div><hr></div>');
        });
    });

    $(document).ready(function(){
        let i = "{{$key}}";

        $("#addMoreVaccines").click(function(e){
            e.preventDefault();
            i++;
            $(".itemvaccines").append('<div class="mb-3 col-6"><label for="">(Optional)</label><select class="form-select" required name="vaccinedesc['+i+'][horse_id]"><option value="">Select a Horse</option>@foreach ($horses as $item)<option value="{{ $item->id }}">{{ $item->name }}</option>@endforeach</select></div><div class="row"><div class="mb-3 col-11"><input type="text" required class="form-control"name="vaccinedesc['+i+'][description]" placeholder="Write  Description"/> <div class="col-6"><label for="">Choose Image</label><input type="file" name="vaccinedesc['+i+'][image]" required class="w-100"></div></div><div class="col-6"><button type="button" id ="remove" class="btn btn-danger float-end my-2 mx-2" title="remove"><i class="fa fa-trash"></i></button></div><hr></div>');
        });
    });

    $(document).on('click', '.removeRow', function(e){
        e.preventDefault();
        $(this).closest('.addlRow').remove();
    });
</script>
