@extends('layouts.admin')
<title>{{ $horse->name }} Details Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-3">
                    <h4 class="text-white text-capitalize ps-3">Horse Details:</h4>
                    <h5 class="text-white mx-2 text-capitalize">
                        {{ $horse->stud->name }}
                    /
                    {{ $horse->name }}
                    <p class="text-end">{{$horse->gender ? 'Horse' : 'Mare'}}</p>
                    </h5>
                    <h6 class="text-white text-capitalize mx-2">
                        {{ $horse->description }}
                        <p class="text-end"> {{ $horse->shelter ? 'as Shelter' : '' }}</p>
                    </h6>
                </div>
                </div>
                <div class="card-body table-responsive px-0 pb-2 m-2">
                <h6 class="text-end">has {{$horse->horsevisit->count()}} Visits</h6>
                @foreach ($horsevisits as $item)
                    <table class="table table-bordered table-striped p-3">
                        <tbody class="text-center">
                            <tr>
                                <td>
                                    {{ $item->case }}
                                    /
                                    @if ($item->caseprice == null)
                                    Under Servation
                                    @else
                                    {{ $item->caseprice }}.EGP Case
                                    @endif
                                </td>
                                <td>
                                    One Of
                                    {{ $item->visit->count() }} Horses

                                    in Same Visit
                                </td>
                                <td>
                                    {{ date(' H:i  d/M/y', strtotime($item->created_at)) }}
                                </td>
                            </tr>
                            <tr>
                                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content mt-3 p-2">
                                             @if ($item->image == null)
                                            <img src="{{ asset ('assets/Empiare.png')}}" style="width: auto;
                                            height: 10cm;">
                                            @else
                                            <img src="{{ asset('assets/img/'.$item->image)}}" style="width: auto;
                                            height: 10cm;">
                                            @endif
                                            </div>
                                        </div>
                                    </div>
                                <td data-bs-toggle="modal" data-bs-target="#exampleModal">
                                    @if ($item->image == null)
                                    <img src="{{ asset ('assets/Empiare.png')}}" style="width: 100px;">
                                    @else
                                    <img src="{{ asset('assets/img/'.$item->image) }}" style="width: 50px;">
                                    @endif
                                </td>
                                <td >{{ $item->description }}</td>
                                <td >{{ $item->treatment }}</td>
                            </tr>
                        </tbody>
                        <br>
                    </table>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
