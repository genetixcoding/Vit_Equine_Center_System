@extends('layouts.app')
<title>Vit Equine Center System</title>
<!-- Section: Design Block -->
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                        <h4 class="text-white text-capitalize ps-3">Stud Details Table</h4>
                        <h6 class="text-white text-capitalize ps-3">Stud Name:- {{ $stud->name }}</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2 m-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0 text-center">
                            <thead>
                                <tr>
                                    <th colspan="2"> @if ($stud->image == null)
                                        <img src="{{ asset ('assets/img/image.png') }}" style="width: 100px">
                                        @else
                                        <img src="{{ asset('assets/img/'.$stud->image)}}" style="width: 50px">
                                        @endif
                                    </th>
                                    <th colspan="2">{{$stud->name}}</th>
                                </tr>
                                <tr>
                                    <th colspan="2">{{$stud->description}}</th>
                                    <th colspan="2">{{ $stud->horse->count()}} Horses Attached</th>
                                </tr>
                            </thead>
                        </table>
                        <br>
                    </div>
                </div>
                <div class="text-center">
                    <button type="button" class="btn btn-link float-end">
                        <a href="{{ url('visit/'.$stud->id) }}" class="text-primary m-2">
                            Visits Table
                        </a>
                    </button>
                    <button type="button" class="btn btn-link float-end">
                        <a href="{{ url('invoice/'.$stud->id) }}" class="text-primary m-2">
                            Invoices Table
                        </a>
                    </button>
                </div>
                <div class="container-fluid py-4">
                    <div class="row mb-4">
                        <div class="col-12 col-md-6 col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <div class="row">
                                        <div class="col-lg-6 col-7">
                                            <h6>Attached Mares</h6>
                                                <i class="fa fa-check text-info" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-lg-6 col-5 my-auto text-end">
                                            <div class="dropdown float-lg-end pe-4">
                                                <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v text-secondary"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body px-0 pb-2">
                                    <table class="table text-center table-responsive table-striped mb-0">
                                        <thead>
                                            <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Mares name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Visits count</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table text-center mb-0">
                                            @foreach ($horse as $item)
                                                @if ($item->gender == 0)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex flex-column justify-content-center">
                                                            <a href="{{ url('user/stud/'.$stud->name.'/'.$item->name) }}"><h6 class="mb-0 text-sm">{{ $item->name }}</h6></a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm"> Contain {{ $item->visitdesc->count() }} Visit</h6>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endif
                                            @endforeach
                                            <div class="pages text-center">
                                                {{ $horse->links() }}
                                            </div>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 mb-4">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <div class="row">
                                        <div class="col-lg-6 col-7">
                                            <h6>Attached Horses</h6>
                                                <i class="fa fa-check text-info" aria-hidden="true"></i>
                                        </div>
                                        <div class="col-lg-6 col-5 my-auto text-end">
                                            <div class="dropdown float-lg-end pe-4">
                                                <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v text-secondary"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body px-0 pb-2">
                                        <table class="table text-center table-responsive table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Horses name</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Visits count</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table text-center mb-0">
                                                @foreach ($horse as $item)
                                                    @if ($item->gender == 1)
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <a href="{{ url('user/stud/'.$stud->name.'/'.$item->name) }}"><h6 class="mb-0 text-sm">{{ $item->name }}</h6></a>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex flex-column justify-content-center">
                                                            <h6 class="mb-0 text-sm"> Contain {{ $item->visitdesc->count() }} Visit</h6>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                @endforeach
                                                <div class="pages text-center">
                                                    {{ $horse->links() }}
                                                </div>
                                            </tbody>
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

@endsection
