@extends('layouts.admin')
<title>Embryo Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-info border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">Embryo Table</h4>
                <a class="text-white float-end p-1" data-bs-toggle="modal" data-bs-target="#exampleModalembryo">Add New Embryo</a>
              </div>
            </div>
            <div>
                <div class="modal fade" id="exampleModalembryo" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="card-body py-5">
                                <form action="{{ url('insert-embryo') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">

                                        <h3 class="text-center text-primary">Add New Embryo Details</h3>

                                        <div class="col-6 mt-4">
                                            <select class="form-select" required name="breeding_id">
                                                <option value="">Select a Breeding</option>
                                                @foreach ($breedings as $item)
                                                <option value="{{ $item->id }}"> ({{ $item->femaleHorse->name ?? '' }}  {{ $item->maleHorse->name ?? '' }}  {{ $item->horsename ?? '' }}) / {{ date('h:iA d-M-y', strtotime($item->created_at)) }}</option>
                                            @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6 mt-4">
                                            <select class="form-select" required name="user_id">
                                                <option value="">Select a User</option>
                                                @foreach ($users as $item)
                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>


                                        <div class="col-8">
                                            <input type="text" class="form-control" required name="localhorsename" placeholder="Insert Your Local Horse Name">
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control" required name="cost" placeholder="Cost">
                                        </div>
                                        <div class="col-8 mt-4">
                                            <select class="form-select" name="finance_id">
                                                <option value="">Select a Finance</option>
                                                @foreach ($finances as $item)
                                                <option value="{{ $item->id }}">({{ $item->description }}  /  {{ $item->decamount }})  /  {{ date('h:iA d-M-y', strtotime($item->created_at)) }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4 mt-4">
                                            <input type="number" class="form-control"  name="paid" placeholder="Paid">
                                        </div>
                                        <div class="col-12">
                                            <label for="">Description</label>
                                            <textarea name="description" class="form-control"></textarea>
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

            @if ($embryosByMonth->isNotEmpty())
            <div class="container-fluid p-2 mt-5">
                <div class="row">
                    <div class="col-12">
                        <div class="card m-auto">
                            <div class="card-header">
                                <div class="row">
                                    <div class="">
                                        <h6 class="text-primary">All Embryos {{ $allembryos->count() }} Items</h6>
                                    </div>
                                </div>
                            </div>
                                {{-- Embryos By Month --}}
                                    @if(isset($embryosByMonth) && $embryosByMonth->count())
                                        <div class="p-1 table-responsive">
                                            <h5 class="text-primary">Embryos By Year & Month</h5>
                                            <table class="table table-bordered table-striped">
                                                <tbody>
                                                    @php
                                                        $groupedByYear = $embryosByMonth->groupBy('year');
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
                                                {{ $embryosByMonth->links() }}
                                            </div>
                                        </div>
                                    @endif
                            <div class="card-body table-responsive m-2 px-0 pb-2px-0 pb-2">
                                <table class="table text-center table-striped mb-0">
                                    <tbody class="table text-center mb-0">
                                        @foreach ($embryosByMonth as $month)
                                            @php
                                                $embryos = $allembryos->filter(function($item) use ($month) {
                                                    return $item->created_at->year == $month->year && $item->created_at->month == $month->month;
                                                });
                                                $groupedEmbryos = $embryos->groupBy('breeding_id');
                                            @endphp

                                            @if($embryos->count())
                                                <tr>
                                                    <th colspan="2" style="background-color: #4a91ee;">
                                                        {{ DateTime::createFromFormat('!m', $month->month)->format('F') }} {{ $month->year }}
                                                        ({{ $embryos->count() }} Embryos)
                                                    </th>
                                                </tr>
                                                @foreach ($embryos as $item)
                                                <tr>
                                                    <td>
                                                        {{ $item->localhorsename }}
                                                    </td>
                                                    <td>
                                                        Day: {{ date('d h:iA', strtotime($item->created_at)) }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        {{ $item->description ?? 'No Description' }}
                                                    </td>
                                                    <td>
                                                        <a data-bs-toggle="modal" data-bs-target="#exampleModalEmbryo{{ $item->id }}" class="text-sm text-info">Edit</a>
                                                        <a href="{{ url('Details/Breeding/'.$item->breeding->id) }}" class="text-sm text-success">Details</a>
                                                        <a href="{{ url('delete-embryo/'.$item->id) }}" class="text-sm text-danger" onclick="return confirm('Are you sure you want to delete this embryo?')"onclick="return confirm('Are you sure you want to delete it ?')">Delete</a>
                                                    </td>
                                                </tr>
                                                <div class="">
                                                    <div class="modal fade" id="exampleModalEmbryo{{ $item->id }}" tabindex="-1" aria-labelledby="exampleModallabelEmbryo{{ $item->id }}" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="card-body py-5">
                                                                    <form action="{{ url('update-embryo/'.$item->id) }}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <div class="row">
                                                                            <h3 class="text-center text-primary">Edit Embryo Details</h3>

                                                                            <div class="col-6 mt-4">
                                                                                <label for="">Breeding</label>
                                                                                <select class="form-select" required name="breeding_id">
                                                                                    <option value="{{ $item->breeding->id }}">({{ $item->breeding->femaleHorse->name ?? '' }}  {{ $item->breeding->maleHorse->name ?? '' }}  {{ $item->breeding->horsename ?? '' }}){{ date('h:iA d-M-y', strtotime($item->breeding->created_at)) }}</option>
                                                                                    @foreach ($breedings as $breeding)
                                                                                    <option value="{{ $breeding->id }}">({{ $breeding->femaleHorse->name ?? '' }}  {{ $breeding->maleHorse->name ?? '' }}  {{ $breeding->horsename ?? '' }}){{ date('h:iA d-M-y', strtotime($breeding->created_at)) }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-6 mt-4">
                                                                                <label for="">Status</label>
                                                                                <select name="status" class="form-select" required>
                                                                                    <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>Still In Progress</option>
                                                                                    <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Pregnant</option>
                                                                                    <option value="2" {{ $item->status == 2 ? 'selected' : '' }}>Not Pregnant</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-8 mt-2">
                                                                                <label for="">Local Horse Name</label>
                                                                                <input type="text" class="form-control" value="{{ $item->localhorsename }}" required name="localhorsename" placeholder="Insert Your Local Horse Name">
                                                                            </div>
                                                                            <div class="col-4 mt-2">
                                                                                <label for="">Cost</label>
                                                                                <input type="number" class="form-control" value="{{ $item->cost }}" required name="cost" placeholder="InsertCost">
                                                                            </div>
                                                                            <div class="col-8 mt-4">
                                                                                <label for="">Financial</label>
                                                                                <select class="form-select" name="finance_id">
                                                                                    <option value="{{ optional($item->finance)->id }}">{{ optional($item->finance)->decamount }}  /  {{ date('h:iA d-M-y', strtotime(optional($item->finance)->created_at)) }}</option>
                                                                                    @foreach ($finances as $finance)
                                                                                        <option value="{{ $finance->id }}">{{ $finance->amount }} /  {{ $finance->decamount }}  /  {{ date('h:iA d-M-y', strtotime($finance->created_at)) }}</option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div class="col-4 mt-4">
                                                                                <label for="">Paid</label>
                                                                                <input type="number" class="form-control" value="{{ $item->paid }}"  name="paid" placeholder="Insert Your Payment">
                                                                            </div>
                                                                            <div class="col-12 mt-2">
                                                                                <label for="">Description</label>
                                                                                <textarea name="description" class="form-control">{{ $item->description }}</textarea>
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


                                                @endforeach

                                            @endif
                                        @endforeach
                                        <div class="pages text-center">
                                            {{ $embryosByMonth->links() }}
                                        </div>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
                <div class="text-primary text-center">
                   <h1>No Embryos Found</h1>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
