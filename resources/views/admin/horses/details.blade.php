@extends('layouts.admin')
<title>{{ $horse->name }} Details</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                        <h4 class="text-white text-capitalize ps-3">Horse Details Table</h4>
                        <h6 class="text-white text-capitalize ps-3">Stud Name:- {{ $horse->stud->name }}</h6>
                        <h6 class="text-white text-capitalize ps-3">Horse Name:- {{ $horse->name }}</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2 m-2">
                    <div class="table-responsive m-2 px-0 pb-2p-0">
                        <table class="table mb-0 text-center">
                            <tbody>
                                <tr>
                                    <th colspan="3">
                                        @if ($horse->image == null)
                                            <img src="{{ asset ('assets/img/image.png') }}" style="width: 100px">
                                        @else
                                            <img src="{{ asset('assets/Uploads/Horses/'.$horse->image)}}" data-bs-toggle="modal" data-bs-target="#exampleModalImage{{ $horse->id }}" style="width: 100px">
                                        @endif
                                        <div class="modal fade" style="background-color: unset;" id="exampleModalImage{{ $horse->id }}" tabindex="-1" aria-labelledby="exampleModallabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="card-body">
                                                        <img src="{{ asset('assets/Uploads/Horses/'.$horse->image)}}" class="img-fluid" alt="Horse Image">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                <tr>
                                    <td>{{ $horse->name }}</td>
                                    @if ($horse->gender == '0')
                                            <td>Female</td>

                                    @elseif($horse->gender == '1')
                                            <td>Male</td>
                                    @endif
                                    {{-- <td>{{ $horse->birth_date }}</td> --}}
                                </tr>
                                <tr>
                                    @if ($horse->shelter == !null)
                                        <td>{{ $horse->shelter }}</td>
                                    @endif
                                    @if($horse->status == '1')
                                        <td>Rejected</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        {{ $horse->description }}
                                    </td>
                                </tr>
                            </tbody>
                      </table>
                        <br>
                    </div>
                </div>
                <div class="text-center">
                    @if ($horse->vaccine->count() > 0)
                        <button type="button" class="btn btn-link">
                            <a href="{{ url('Vaccine/Table/'.$horse->name) }}" class="text-primary">
                                Vaccine Table
                            </a>
                        </button>
                    @if ($horse->visitdesc->count() > 0)
                    <button type="button" class="btn btn-link">
                        <a href="{{ url('Visit/Table/'.$horse->name) }}" class="text-primary">
                            Visits Table
                        </a>
                    </button>
                    @endif
                    @if ($horse->treatment->count() > 0)
                    <button type="button" class="btn btn-link">
                        <a href="{{ url('Treatment/Table/'.$horse->name) }}" class="text-primary">
                            Treatment Table
                        </a>
                    </button>
                    @endif
                    @if ($horse->taskdesc->count() > 0)
                    <button type="button" class="btn btn-link">
                        <a href="{{ url('Task/Table/'.$horse->name) }}" class="text-primary">
                            Task Table
                        </a>
                    </button>
                    @endif
                    @if ($horse->femaleHorse->count() > 0)
                    <button type="button" class="btn btn-link">
                        <a href="{{ url('Breeding/Table/'.$horse->name) }}" class="text-primary">
                            Breeding Table
                        </a>
                    </button>
                    @endif
                    @if (!empty($horse->femaleHorse) && is_object($horse->femaleHorse) && method_exists($horse->femaleHorse, 'embryo') && $horse->femaleHorse->embryo->count() > 0)
                    <button type="button" class="btn btn-link">
                        <a href="{{ url('Breeding/Table/'.$horse->name) }}" class="text-primary">
                            Embryo Table
                        </a>
                    </button>
                    @endif
                    @if ($horse->maleHorse->count() > 0)
                    <button type="button" class="btn btn-link">
                        <a href="{{ url('Breeding/Table/'.$horse->name) }}" class="text-primary">
                            Breeding Table
                        </a>
                    </button>
                    @endif

                    @if (!empty($horse->maleHorse) && is_object($horse->maleHorse) && method_exists($horse->maleHorse, 'embryo') && $horse->maleHorse->embryo->count() > 0)
                    <button type="button" class="btn btn-link">
                        <a href="{{ url('Breeding/Table/'.$horse->name) }}" class="text-primary">
                            Embryo Table
                        </a>
                    </button>
                    @endif

                    @if ($horse->beddingdesc->count() > 0 || $horse->feedingdesc->count() > 0)
                    <button type="button" class="btn btn-link">
                        <a href="{{ url('Feeding&Bedding/Table/'.$horse->name) }}" class="text-primary">
                            Feeding & Bedding Table
                        </a>
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
