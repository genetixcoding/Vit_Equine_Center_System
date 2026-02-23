@extends('layouts.admin')
<title>Doctors Page</title>
@section('content')
<div class="container-fluid py-4">
    <div class="row pt-4 p-2">
        <div class="col-12">
          <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n5 mx-3 z-index-2">
              <div class="bg-gradient-info shadow-primary border-radius-lg pt-4 pb-5">
                <h4 class="text-white text-capitalize ps-3">Doctors Table

                </h4>
              </div>
            </div>

            <div class="card-body m-2 table-responsive m-2 px-0 pb-2p-2">
                <table class="table table-bordered text-center table-responsive m-2 px-0 pb-2table-striped">
                   <thead>
                        <tr>
                            <th style="background-color: #338ded; color: #FFF;">Name</th>
                            <th style="background-color: #338ded; color: #FFF;">Description</th>
                        </tr>
                    </thead>
                    @foreach ($users as $item)
                    <tbody>
                        <tr>
                            <td>
                                <a href="{{ url('Doctor/Accountants/'.$item->name) }}">{{$item->name}}</a>
                            </td>
                            <td>{{$item->description}}</td>

                        </tr>

                        <tr>
                            <td>
                                @if ($item->phone == null)
                                    No PHone Information
                                @else
                                    {{$item->phone}}
                                @endif
                            </td>
                            <td>
                                @if ($item->email == null)
                                    No Email Information
                                @else
                                    {{$item->email}}
                                @endif
                            </td>

                        </tr>
                    </tbody>
                    @endforeach
                </table>
                <div class="pages text-center">
                    {{ $users->links() }}
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection



