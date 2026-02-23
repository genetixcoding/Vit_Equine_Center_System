@extends('layouts.admin')
<title>Treatments Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Treatment Table</h4>
                        <h4 class="text-white text-capitalize pe-3 text-end">{{ $alltreatments->count() }} Treatment</h4>
                    </div>
                </div>

                @if ($treatmentsByMonth->isNotEmpty())
                <div class="container-fluid p-2 mt-5">
                    <div class="row">
                        <div class="col-12">
                            <div class="card m-auto">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="">
                                            <h6 class="text-primary">All Treatments by Month
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                                {{-- Treatments By Month --}}
                                    @if(isset($treatmentsByMonth) && $treatmentsByMonth->count())
                                        <div class="mx-4 mt-2 table-responsive">
                                            <h5 class="text-primary">Treatments By Month</h5>
                                            <table class="table table-bordered table-striped">

                                                <tbody>
                                                @php
                                                    $groupedByYear = $treatmentsByMonth->groupBy('year');
                                                @endphp
                                                @foreach($groupedByYear as $year => $months)
                                                    <tr>
                                                        <th colspan="2" style="background-color: #e3f2fd;">Year: {{ $year }}</th>
                                                    </tr>
                                                    @foreach($months as $month)
                                                        <tr>
                                                            <td>{{ DateTime::createFromFormat('!m', $month->month)->format('F') }}</td>
                                                            <td>{{ $month->count }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endforeach
                                                </tbody>
                                            </table>
                                            <div class="pages text-center">
                                                {{ $treatmentsByMonth->links() }}
                                            </div>
                                        </div>
                                    @endif
                                <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                    <table class="table  text-center  table-striped mb-0">
                                        {{-- Loop through months --}}
                                        @foreach ($treatmentsByMonth as $month)
                                            @php
                                                $treatments = $alltreatments->filter(function($item) use ($month) {
                                                    return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                });
                                            @endphp

                                            @if($treatments->count())
                                                <thead>
                                                    <tr>
                                                        <th style="background-color: #4a91ee;">
                                                            {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                        </th>
                                                        <th style="background-color: #4a91ee;">
                                                            ({{ $treatments->count() }} Treatments)
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {{-- Loop through treatments --}}
                                                    @foreach ($treatments as $treatmentitem)
                                                        <tr>
                                                            <td style="background-color: #338ded; color: #FFF;">
                                                                {{ $treatmentitem->user->name ?? '' }}
                                                                <br>
                                                                @if ($treatmentitem->horse_id !== null && $treatmentitem->horse)
                                                                    <a style="color: #FFF" href="{{url('Horse/Details/'.$treatmentitem->horse->name)}}">
                                                                        {{ $treatmentitem->horse->name }}
                                                                    </a>
                                                                @elseif ($treatmentitem->embryo && $treatmentitem->embryo->localhorsename !== null)
                                                                    <a style="color: #FFF;" href="{{url('Details/Embryo/'.$treatmentitem->embryo->id)}}">
                                                                        {{ $treatmentitem->embryo->localhorsename }}
                                                                    </a>
                                                                @else
                                                                    <span class="text-white">No Horse</span>
                                                                @endif
                                                            </td>
                                                            <td style="background-color: #338ded; color: #FFF;">
                                                                <a class="text-white m-1" href="{{url('Treatment/Details/'.$treatmentitem->id)}}">
                                                                    Day:
                                                                    {{ date('d h:iA', strtotime($treatmentitem->created_at)) }}
                                                                </a>
                                                                <br>
                                                                <a data-bs-toggle="modal" data-bs-target="#exampleModalTreatment{{ $treatmentitem->id }}" class="text-sm text-info">Edit</a>
                                                                <a href="{{ url('delete-treatment/'.$treatmentitem->id) }}" class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete this treatment?')"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                            </td>
                                                        </tr>
                                                        {{-- Modal for editing treatment --}}
                                                        <div class="modal fade" id="exampleModalTreatment{{ $treatmentitem->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="card-body py-5">
                                                                        <form action="{{ url('update-treatment/'.$treatmentitem->id) }}" method="POST" enctype="multipart/form-data">
                                                                            @csrf
                                                                            @method('PUT')
                                                                            <div class="row">
                                                                                <h3 class="text-center text-primary">Edit Treatment Details</h3>
                                                                                <div class="mb-2 col-6">
                                                                                    <label for="">(Optional)</label>
                                                                                    <select class="form-select" name="horse_id">
                                                                                        @if ($treatmentitem->horse_id)
                                                                                        <option value="{{ $treatmentitem->horse_id }}">{{ $treatmentitem->horse->name }}</option>
                                                                                        <option value="">Remove Horse ??</option>
                                                                                        @else
                                                                                        <option value="">Select Horse</option>
                                                                                        @endif
                                                                                        @foreach ($horses as $itemhorse)
                                                                                        <option value="{{ $itemhorse->id }}">{{ $itemhorse->name }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-2 col-6">
                                                                                    <label for="">(Optional)</label>
                                                                                    <select class="form-select" name="embryo_id">
                                                                                        @if ($treatmentitem->embryo_id)
                                                                                        <option value="{{ $treatmentitem->embryo_id }}">{{ $treatmentitem->embryo->localhorsename }}</option>
                                                                                        <option value="">Remove Embryo ??</option>
                                                                                        @else
                                                                                        <option value="">Select Embryo</option>
                                                                                        @endif
                                                                                        @foreach ($embryos as $itemembryo)
                                                                                        <option value="{{ $itemembryo->id }}">{{ $itemembryo->localhorsename }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="mb-2 col-8">
                                                                                    <label for=""></label>
                                                                                    <select class="form-select" name="user_id">
                                                                                        <option value="{{ $treatmentitem->user->id }}">{{ $treatmentitem->user->name ?? 'Name' }}</option>
                                                                                        @foreach ($users as $itemuser)
                                                                                        <option value="{{ $itemuser->id }}">{{ $itemuser->name }}</option>
                                                                                        @endforeach
                                                                                    </select>
                                                                                </div>
                                                                                <div class="my-2 col-4">
                                                                                    <button
                                                                                        type="submit"
                                                                                        class="btn btn-primary float-center">
                                                                                        Update
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        {{-- Treatment descriptions --}}
                                                        @foreach ($treatmentitem->treatmentdesc as $item)
                                                            <tr>
                                                                <td>
                                                                    {{ $item->pharmacy->item ?? '' }}

                                                                    <br> {{ $item->description ?? '' }}
                                                                </td>
                                                                <td>
                                                                    {{ $item->qty ?? '' }}
                                                                    {{ $item->type ?? '' }}
                                                                <br>
                                                                    <a data-bs-toggle="modal" data-bs-target="#exampleModalDesc{{ $item->id }}" class="text-sm text-info">Edit</a>
                                                                    <a href="{{ url('delete-treatmentdesc/'.$item->id) }}" class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete this treatment?')"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                                </td>
                                                            </tr>
                                                            {{-- Modal for editing treatmentdesc --}}
                                                            <div class="modal fade" id="exampleModalDesc{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <div class="card-body py-5">
                                                                            <form action="{{ url('update-treatmentdesc/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                                                                @csrf
                                                                                @method('PUT')
                                                                                <div class="row">
                                                                                    <h3 class="text-center text-primary">Edit Treatment Details</h3>
                                                                                    <div class="mt-4 col-6">
                                                                                        <select class="form-select" required name="pharmacy_id">
                                                                                            <option value="{{ $item->pharmacy_id }}">{{ $item->pharmacy->item ?? 'Select Medicine' }}</option>
                                                                                            @foreach ($pharmacy as $itempharmacy)
                                                                                            <option value="{{ $itempharmacy->id }}">{{ $itempharmacy->item }} / {{ $itempharmacy->unitqty }} {{ $itempharmacy->type }}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                    </div>
                                                                                    <div class="mb-2 col-6">
                                                                                        <label for="">Insert Description</label>
                                                                                        <input type="text" required class="form-control" name="description" value="{{ $item->description }}" placeholder="Insert Description">
                                                                                    </div>
                                                                                    <div class="mb-2 col-6">
                                                                                        <label for="">Insert Doses</label>
                                                                                        <input type="number" required class="form-control" placeholder="Insert Doses" name="qty" value="{{ $item->qty }}" min="1">
                                                                                    </div>
                                                                                    <div class="mb-2 col-6">
                                                                                        <label for="">Insert Type</label>
                                                                                        <input type="text" required class="form-control" placeholder="Insert Type" name="type" value="{{ $item->type }}">
                                                                                    </div>
                                                                                    <button type="submit" class="btn btn-primary float-end">
                                                                                        Update
                                                                                    </button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        @endforeach

                                                    @endforeach
                                                </tbody>
                                            @endif
                                        @endforeach
                                        <div class="pages text-center">
                                            {{ $treatmentsByMonth->links() }}
                                        </div>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                    <div class="card-body text-center">
                        <h5 class="text-primary text-center">No Treatments Found</h5>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
